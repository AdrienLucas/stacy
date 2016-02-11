<?php

use Stacy\Experiment;

class NotScalarExperimentTest extends \PHPUnit_Framework_TestCase
{
    public function testNotScalarExperiment()
    {
        $control = function($i) {
            $o = new \stdClass();
            $o->i = $i + $i +$i;
            return $o;
        };
        $candidate = function($i) {
            $o = new \stdClass();
            $o->i = $i * 3;
            return $o;
        };

        $experiment = new Experiment(function($control, $candidate){
            return $control->i === $candidate->i;
        });
        
        $experiment->run($control);
        $experiment->test($candidate);

        $this->assertSame($experiment(1)->i, 3);
        $this->assertTrue($experiment->getResults()->current()->hasMatched());
    }
/*
    public function testNamedExperiments()
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
        $experiment->run($control);
        $experiment->test($candidate);

        $experiment();
        $this->assertNotNull($experiment->getResults()->current()->hasThrownException());
    }
    */
}
