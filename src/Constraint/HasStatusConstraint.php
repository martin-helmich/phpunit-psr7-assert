<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit_Framework_Constraint as Constraint;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HasStatusConstraint extends Constraint
{

    /** @var int */
    private $status;

    public function __construct($status)
    {
        parent::__construct();
        $this->status = $status;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "has response status {$this->status}";
    }

    protected function matches($other)
    {
        if (!$other instanceof ResponseInterface) {
            return false;
        }

        return $other->getStatusCode() === $this->status;
    }
}
