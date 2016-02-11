<?php

namespace Stacy;

use ArrayIterator;

class Experiment
{
    private $control;
    private $tests = array();

    /**
     * @var ArrayIterator
     */
    private $results;
    private $condition;
    private $comparisonCallback;

    public function __construct($comparisonCallback = null) {
        $this->comparisonCallback = $comparisonCallback;
    }

    public function run($callable)
    {
        $this->control = $callable;
        $this->results = new ArrayIterator();
    }

    public function test($callable, $name = null)
    {
        if($name === null) {
            $name = uniqid();
        }
        $this->tests[$name] = $callable;
    }

    public function __invoke()
    {
        $args = func_get_args();
        $controlResult = call_user_func_array($this->control, $args);

        $comparisonCallback = $this->comparisonCallback;
        if($comparisonCallback === null) {
            $comparisonCallback = function($control, $candidate) { return $control === $candidate; };
        }

        $controlCallback = function($candidate) use ($controlResult, $comparisonCallback) {
            return $comparisonCallback($controlResult, $candidate);
        };

        if($this->condition === null || call_user_func($this->condition)){
            foreach($this->tests as $name => $test) {
                try{
                    $result = call_user_func_array($test, $args);
                    $exception = null;
                } catch (\Exception $e) {
                    $exception = $e;
                    $result = null;
                }
                $this->results[$name] = new ExperimentResult($controlCallback, $result, $exception);
            }
            $this->results->rewind();
        }

        return $controlResult;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getResult($name)
    {
        return $this->results[$name];
    }

    public function onlyIf($callable)
    {
        $this->condition = $callable;
    }

    public function publish()
    {
        $out = '';
        foreach($this->results as $key => $res) {
            $out = sprintf('Experiment #%d : %s'."\n", $key, var_export($res, true));
        }
        return $out;
    }
}
