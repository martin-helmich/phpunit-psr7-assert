<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\MessageInterface;

class BodyMatchesConstraint extends Constraint
{

    /** @var Constraint */
    private Constraint $constraint;

    public function __construct(Constraint $constraint)
    {
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
        return 'message body matches ' . $this->constraint->toString();
    }

    protected function matches(mixed $other): bool
    {
        if (!$other instanceof MessageInterface) {
            return false;
        }

        $other->getBody()->rewind();
        $body = $other->getBody()->getContents();
        return (bool)$this->constraint->evaluate($body, '', true);
    }
}
