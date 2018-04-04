<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

class HasQueryParametersConstraint extends Constraint
{
    /** @var HasQueryParameterConstraint[] */
    private $constraints = [];

    public function __construct(array $constraints)
    {
        foreach ($constraints as $key => $value) {
            $this->constraints[] = new HasQueryParameterConstraint($key, $value);
        }
    }

    public function matches($other): bool
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->evaluate($other, "", true)) {
                return false;
            }
        }

        return true;
    }

    public function toString(): string
    {
        return join(" and ", array_map(function(HasQueryParameterConstraint $c) {
            return $c->toString();
        }, $this->constraints));
    }
}