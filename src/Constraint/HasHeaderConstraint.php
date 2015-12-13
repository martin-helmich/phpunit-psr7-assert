<?php
namespace Helmich\Psr7Assert\Constraint;


use PHPUnit_Framework_Constraint as Constraint;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;


class HasHeaderConstraint extends Constraint
{



    /** @var string */
    private $name;


    /** @var Constraint */
    private $constraint;



    public function __construct($name, $constraint = NULL)
    {
        parent::__construct();

        if ($constraint === NULL)
        {
            $constraint = new \PHPUnit_Framework_Constraint_Not(new \PHPUnit_Framework_Constraint_IsEmpty());
        }
        else if (!$constraint instanceof Constraint)
        {
            $constraint = new \PHPUnit_Framework_Constraint_IsEqual($constraint);
        }

        $this->name       = strtolower($name);
        $this->constraint = $constraint;
    }



    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "has header '{$this->name}'";
    }



    protected function matches($other)
    {
        if (!$other instanceof MessageInterface)
        {
            return FALSE;
        }

        if (!$other->hasHeader($this->name)) {
            return FALSE;
        }

        $value = $other->getHeader($this->name);
        return $this->constraint->evaluate($value, '', TRUE);
    }



}