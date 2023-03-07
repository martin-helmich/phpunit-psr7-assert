<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\MessageInterface;

class HasHeaderConstraint extends Constraint
{

    private string $name;
    private Constraint $constraint;

    public function __construct(string $name, mixed $constraint = null)
    {
        if ($constraint === null) {
            $constraint = Assert::logicalNot(Assert::isEmpty());
        } elseif (!$constraint instanceof Constraint) {
            $constraint = Assert::equalTo($constraint);
        }

        $this->name       = strtolower($name);
        $this->constraint = $constraint;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        /** @psalm-suppress InternalMethod */
        return "has header '{$this->name}' that {$this->constraint->toString()}";
    }

    protected function matches($other): bool
    {
        if (!$other instanceof MessageInterface) {
            return false;
        }

        if (!$other->hasHeader($this->name)) {
            return false;
        }

        foreach ($other->getHeader($this->name) as $value) {
            if ($this->constraint->evaluate($value, '', true)) {
                return true;
            }
        }

        return false;
    }
}
