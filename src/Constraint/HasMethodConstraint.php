<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\RequestInterface;

class HasMethodConstraint extends Constraint
{

    /** @var string */
    private $method;

    public function __construct(string $method)
    {
        $this->method = $method;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return "has request method {$this->method}";
    }

    protected function matches($other): bool
    {
        if (!$other instanceof RequestInterface) {
            return false;
        }

        return $other->getMethod() === $this->method;
    }
}
