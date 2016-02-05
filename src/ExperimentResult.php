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
    private $control;
    private $result;

    /**
     * ExperimentResult constructor.
     * @param $control
     * @param $result
     */
    public function __construct($control, $result)
    {
        $this->control = $control;
        $this->result = $result;
    }

    /**
     * @return boolean
     */
    public function hasMatched()
    {
        return $this->control === $this->result;
    }
}