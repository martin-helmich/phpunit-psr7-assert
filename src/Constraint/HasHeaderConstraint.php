<?php
namespace Helmich\Psr7Assert\Constraint;


use PHPUnit_Framework_Constraint as Constraint;
use PHPUnit_Framework_Assert as Assert;
use Psr\Http\Message\MessageInterface;


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
            $constraint = Assert::logicalNot(Assert::isEmpty());
        }
        else if (!$constraint instanceof Constraint)
        {
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
    public function toString()
    {
        return "has header '{$this->name}' that {$this->constraint->toString()}";
    }



    protected function matches($other)
    {
        if (!$other instanceof MessageInterface)
        {
            return FALSE;
        }

        if (!$other->hasHeader($this->name))
        {
            return FALSE;
        }

        foreach ($other->getHeader($this->name) as $value)
        {
            if ($this->constraint->evaluate($value, '', TRUE))
            {
                return TRUE;
            }
        }

        return FALSE;
    }



}