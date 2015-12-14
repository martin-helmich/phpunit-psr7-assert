<?php
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;


use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasMethodConstraint;
use PHPUnit_Framework_TestCase as TestCase;


class HasMethodConstraintTest extends TestCase
{


    public function testMethodIsEvaluatedForEquality()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasMethodConstraint('GET');
        $constraint->evaluate($request);
    }


    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testMethodIsEvaluatedForEqualityAndCanFail()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasMethodConstraint('POST');
        $constraint->evaluate($request);
    }


    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testNonMessagesAreNotEvaluated()
    {
        $constraint = new HasMethodConstraint('GET');
        $constraint->evaluate('Ho, ho, ho');
    }

}