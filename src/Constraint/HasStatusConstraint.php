<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_Constraint as Constraint;
use Psr\Http\Message\ResponseInterface;

class HasStatusConstraint extends Constraint
{

    /** @var Constraint */
    private $status;

    public function __construct($status)
    {
        parent::__construct();

        if (!$status instanceof Constraint) {
            $status = Assert::equalTo($status);
        }

        $this->status = $status;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "response status {$this->status->toString()}";
    }

    protected function matches($other)
    {
        if (!$other instanceof ResponseInterface) {
            return false;
        }

        return $this->status->evaluate($other->getStatusCode(), '', true);
    }
}
