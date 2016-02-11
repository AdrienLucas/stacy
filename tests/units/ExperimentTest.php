<?php

use Stacy\Experiment;

class ExperimentTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleExperiment()
    {
        $control = function($i) {
            return $i + $i + $i;
        };
        $candidate = function($i) {
            return $i * 3;
        };

        $experiment = new Experiment();
        $experiment->run($control);
        $experiment->test($candidate);

        $this->assertSame($experiment(1), 3);
        $this->assertTrue($experiment->getResults()->current()->hasMatched());
        $this->assertStringStartsWith('Experiment #', $experiment->publish());
    }

    public function testMultipleExperiments()
    {
        $control = function($i) {
            return $i + $i +$i;
        };

        $goodCandidate = function($i) {
            return $i * 3;
        };

        $badCandidate = function($i) {
            return $i * 2;
        };

        $experiment = new Experiment();
        $experiment->run($control);
        $experiment->test($goodCandidate, 'goodCandidate');
        $experiment->test($badCandidate, 'badCandidate');

        $experiment(1);
        $this->assertTrue($experiment->getResult('goodCandidate')->hasMatched());
        $this->assertFalse($experiment->getResult('badCandidate')->hasMatched());
    }


    public function testConditionalExperiment()
    {
        $callable = 'uniqid';

        $experiment = new Experiment();
        $experiment->run($callable);
        $experiment->test($callable);
        $experiment->onlyIf(function() use (&$shouldRun) {
            return $shouldRun;
        });

        $shouldRun = false;
        $experiment();
        $this->assertNull($experiment->getResults()->current());

        $shouldRun = true;
        $experiment();
        $this->assertNotNull($experiment->getResults()->current());
    }

    public function testExceptionThrowingExperiment()
    {
        $candidate = function(){};
        $exceptionThrowingCandidate = function() {
            throw new \Exception();
        };

        $experiment = new Experiment();
        $experiment->run($candidate);
        $experiment->test($exceptionThrowingCandidate);

        $experiment();
        $this->assertNotNull($experiment->getResults()->current()->hasThrownException());
    }
}
