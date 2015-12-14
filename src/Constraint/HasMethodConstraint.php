<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit_Framework_Constraint as Constraint;
use Psr\Http\Message\RequestInterface;

class HasMethodConstraint extends Constraint
{

    /** @var string */
    private $method;

    public function __construct($method)
    {
        parent::__construct();
        $this->method = $method;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "has request method {$this->method}";
    }

    protected function matches($other)
    {
        if (!$other instanceof RequestInterface) {
            return false;
        }

        return $other->getMethod() === $this->method;
    }
}
