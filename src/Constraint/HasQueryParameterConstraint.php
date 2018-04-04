<?php
declare(strict_types = 1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsEqual;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class HasQueryParameterConstraint extends Constraint
{
    private $nameMatcher;
    private $valueMatcher;

    public function __construct($nameMatcher, $valueMatcher = null)
    {
        parent::__construct();

        if (!($nameMatcher instanceof Constraint)) {
            $nameMatcher = new IsEqual($nameMatcher);
        }

        if ($valueMatcher === null) {
            $valueMatcher = new IsAnything();
        } else if (!($valueMatcher instanceof Constraint)) {
            $valueMatcher = new IsEqual($valueMatcher);
        }

        $this->nameMatcher = $nameMatcher;
        $this->valueMatcher = $valueMatcher;
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
        parse_str($query, $parsedQuery);

        foreach ($parsedQuery as $key => $value) {
            if (!$this->nameMatcher->evaluate($key, "", true)) {
                continue;
            }

            if (!$this->valueMatcher->evaluate($value, "", true)) {
                continue;
            }

            return true;
        }

        return false;
    }


    public function toString(): string
    {
        return 'has a query parameter with a name matching ' . $this->nameMatcher->toString() . ' and value matching ' . $this->valueMatcher->toString();
    }
}