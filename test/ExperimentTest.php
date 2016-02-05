<?php

use Stacy\Experiment;

class ExperimentTest extends \PHPUnit_Framework_TestCase
{
    public function testExperimentSuccess()
    {
        $oldWay = function($i) {
            return $i + $i + $i;
        };

        $newWay = function($i) {
            return $i * 3;
        };

        $experiment = new Experiment();
        $experiment->run($oldWay);
        $experiment->test($newWay);

        $this->assertSame($experiment(1), 3);
        $this->assertTrue($experiment->getResults()[0]->hasMatched());
    }
}
