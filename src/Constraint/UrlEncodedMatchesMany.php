<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

class UrlEncodedMatchesMany extends Constraint
{
    /** @var UrlEncodedMatches[] */
    private $constraints = [];

    public function __construct(array $constraints)
    {
        parent::__construct();

        foreach ($constraints as $key => $value) {
            $this->constraints[] = new UrlEncodedMatches($key, $value);
        }
    }

    protected function matches($other): bool
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