<?php
namespace Hal\Violation\Class_;

use Hal\Metric\ClassMetric;
use Hal\Metric\Metric;
use Hal\ShouldNotHappenException;
use Hal\Violation\Violation;

class TooLong implements Violation
{

    /** @var Metric|null */
    private $metric;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Too long';
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

        if ($this->metric->get('lloc') >= 200) {
            $this->metric->get('violations')->add($this);
        }
    }

    /**
     * @inheritdoc
     */
    public function getLevel()
    {
        return Violation::INFO;
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
This class looks really long.

* Class has {$this->metric->get('lloc')} logical lines of code
* Class has {$this->metric->get('loc')} lines of code

Maybe your class should not exceed 200 lines of logical code
EOT;
    }
}
