<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsEqual;

class UrlEncodedMatches extends Constraint
{
    private Constraint $nameMatcher;
    private Constraint $valueMatcher;

    public function __construct(Constraint|string $nameMatcher, Constraint|string|null $valueMatcher = null)
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

    protected function matches(mixed $other): bool
    {
        if (!is_string($other)) {
            return false;
        }

        parse_str($other, $parsedQuery);

        /** @var array<string, string> $parsedQuery */
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
        /** @psalm-suppress InternalMethod */
        return 'contains a name matching ' . $this->nameMatcher->toString() . ' and value matching ' . $this->valueMatcher->toString();
    }

}
