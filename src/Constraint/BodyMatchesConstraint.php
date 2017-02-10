<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Psr\Http\Message\MessageInterface;

class BodyMatchesConstraint extends Constraint
{

    /** @var Constraint */
    private $constraint;

    public function __construct(Constraint $constraint)
    {
        parent::__construct();
        $this->constraint = $constraint;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return "message body matches " . $this->constraint->toString();
    }

    protected function matches($other): bool
    {
        if (!$other instanceof MessageInterface) {
            return false;
        }

        $other->getBody()->rewind();
        $body = $other->getBody()->getContents();
        return $this->constraint->evaluate($body, '', true);
    }
}
