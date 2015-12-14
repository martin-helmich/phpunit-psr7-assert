<?php
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use PHPUnit_Framework_TestCase as TestCase;

class HasUriConstraintTest extends TestCase
{

    public function testUriIsEvaluatedForEquality()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasUriConstraint('/foo');
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testUriIsEvaluatedForEqualityAndCanFail()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasUriConstraint('/bar');
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testNonMessagesAreNotEvaluated()
    {
        $constraint = new HasUriConstraint('/foo');
        $constraint->evaluate('Ho, ho, ho');
    }

}