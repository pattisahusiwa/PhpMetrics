<?php
namespace Hal\Violation\Class_;

use Hal\Metric\ClassMetric;
use Hal\Metric\Metric;
use Hal\ShouldNotHappenException;
use Hal\Violation\Violation;

class ProbablyBugged implements Violation
{

    /** @var Metric|null */
    private $metric;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Probably bugged';
    }

    /**
     * @inheritdoc
     */
    public function apply(Metric $metric)
    {
        if (!$metric instanceof ClassMetric) {
            return;
        }

        $this->metric = $metric;

        $suspect = 0;
        if ($this->metric->get('bugs') >= .35) {
            $this->metric->get('violations')->add($this);
            return;
        }
    }

    /**
     * @inheritdoc
     */
    public function getLevel()
    {
        return Violation::WARNING;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        if ($this->metric === null) {
            throw new ShouldNotHappenException('Metric property is null');
        }

        return <<<EOT
This component contains in theory {$this->metric->get('bugs')} bugs.

* Calculation is based on number of operators, operands, cyclomatic complexity
* See more details at https://en.wikipedia.org/wiki/Halstead_complexity_measures
* {$this->metric->get('numberOfUnitTests')} testsuites has dependency to this class.

Maybe you should check your unit tests for this class.
EOT;
    }
}
