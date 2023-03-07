<?php
declare(strict_types = 1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class HasQueryParameterConstraint extends Constraint
{
    private UrlEncodedMatches $inner;

    public function __construct(mixed $nameMatcher, mixed $valueMatcher = null)
    {
        $this->inner = new UrlEncodedMatches($nameMatcher, $valueMatcher);
    }

    protected function matches($other): bool
    {
        if (is_string($other)) {
            return $this->matchesString($other);
        }

        if ($other instanceof UriInterface) {
            return $this->matchesPsr7Uri($other);
        }

        if ($other instanceof RequestInterface) {
            return $this->matchesPsr7Uri($other->getUri());
        }

        return false;
    }

    private function matchesPsr7Uri(UriInterface $other): bool
    {
        $queryString = $other->getQuery();
        return $this->matchesQueryString($queryString);
    }

    private function matchesString(string $other): bool
    {
        $parsedUrl = parse_url($other);
        if (!isset($parsedUrl["query"])) {
            return false;
        }

        return $this->matchesQueryString($parsedUrl["query"]);
    }

    private function matchesQueryString(string $query): bool
    {
        return $this->inner->evaluate($query, "", true);
    }


    public function toString(): string
    {
        return 'query string ' . $this->inner->toString();
    }
}
