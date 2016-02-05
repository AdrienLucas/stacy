<?php

namespace Stacy;

class Experiment
{
    private $control;
    private $tests = array();
    private $results = array();

    public function run($callable)
    {
        $this->control = $callable;
    }

    public function test($callable)
    {
        $this->tests[] = $callable;
    }

    public function __invoke()
    {
        $args = func_get_args();
        $controlResult = call_user_func_array($this->control, $args);

        foreach($this->tests as $test) {
            $this->results[] = new ExperimentResult($controlResult, call_user_func_array($test, $args));
        }
        return $controlResult;
    }

    public function getResults()
    {
        return $this->results;
    }
}