<?php
namespace Helmich\Psr7Assert\Constraint;

use PHPUnit_Framework_Constraint as Constraint;
use Psr\Http\Message\MessageInterface;

class BodyMatchesConstraint extends Constraint
{

    /** @var Constraint */
    private $constraint;

    public function __construct($constraint)
    {
        parent::__construct();
        $this->constraint = $constraint;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "message body matches " . $this->constraint->toString();
    }

    protected function matches($other)
    {
        if (!$other instanceof MessageInterface) {
            return false;
        }

        $body = $other->getBody()->getContents();
        return $this->constraint->evaluate($body, '', true);
    }

}