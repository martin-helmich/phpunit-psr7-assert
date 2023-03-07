<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\RequestInterface;

class HasUriConstraint extends Constraint
{

    private string $uri;

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return "has request URI '{$this->uri}'";
    }

    protected function matches($other): bool
    {
        if (!$other instanceof RequestInterface) {
            return false;
        }

        return $this->uri === (string) $other->getUri();
    }
}
