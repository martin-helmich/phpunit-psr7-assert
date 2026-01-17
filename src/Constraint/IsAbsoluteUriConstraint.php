<?php
declare(strict_types = 1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

class IsAbsoluteUriConstraint extends Constraint
{
    public function toString(): string
    {
        return "is valid URI";
    }

    protected function matches(mixed $other): bool
    {
        $parts = parse_url($other);
        if ($parts === false) {
            return false;
        }

        if (!isset($parts["host"]) || !$parts["host"]) {
            return false;
        }

        if (!isset($parts["scheme"]) || !$parts["scheme"]) {
            return false;
        }

        return $parts !== false;
    }
}