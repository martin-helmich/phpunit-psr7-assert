<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsEqual;

class UrlEncodedMatches extends Constraint
{
    private $nameMatcher;
    private $valueMatcher;

    public function __construct($nameMatcher, $valueMatcher = null)
    {
        if (!($nameMatcher instanceof Constraint)) {
            $nameMatcher = new IsEqual($nameMatcher);
        }

        if ($valueMatcher === null) {
            $valueMatcher = new IsAnything();
        } elseif (!($valueMatcher instanceof Constraint)) {
            $valueMatcher = new IsEqual($valueMatcher);
        }

        $this->nameMatcher = $nameMatcher;
        $this->valueMatcher = $valueMatcher;
    }

    protected function matches($other): bool
    {
        parse_str($other, $parsedQuery);

        foreach ($parsedQuery as $key => $value) {
            $nameMatches = $this->nameMatcher->evaluate($key, "", true);
            $valueMatches = $this->valueMatcher->evaluate($value, "", true);

            if ($nameMatches && $valueMatches) {
                return true;
            }
        }

        return false;
    }

    public function toString(): string
    {
        return 'contains a name matching ' . $this->nameMatcher->toString() . ' and value matching ' . $this->valueMatcher->toString();
    }

}
