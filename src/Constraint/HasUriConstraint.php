<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\RequestInterface;

class HasUriConstraint extends Constraint
{

    /** @var string */
    private $uri;

    public function __construct($uri)
    {
        parent::__construct();
        $this->uri = $uri;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "has request URI '{$this->uri}'";
    }

    protected function matches($other)
    {
        if (!$other instanceof RequestInterface) {
            return false;
        }

        return $this->uri == $other->getUri()->__toString();
    }
}
