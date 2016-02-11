<?php
/**
 * Created by PhpStorm.
 * User: adrienlucas
 * Date: 05/02/16
 * Time: 16:46
 */

namespace Stacy;


class ExperimentResult
{
    private $controlCallback;
    private $result;
    private $exception;

    /**
     * ExperimentResult constructor.
     * @param $control
     * @param $result
     */
    public function __construct($controlCallback, $result, $exception)
    {
        $this->controlCallback = $controlCallback;
        $this->result = $result;
        $this->exception = $exception;
    }

    /**
     * @return boolean
     */
    public function hasMatched()
    {
        return call_user_func($this->controlCallback, $this->result);
    }

    public function hasThrownException()
    {
        return $this->exception !== null;
    }
}
