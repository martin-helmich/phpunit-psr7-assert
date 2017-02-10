<?php
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use PHPUnit\Framework\TestCase;

class HasUriConstraintTest extends TestCase
{

    public function testUriIsEvaluatedForEquality()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasUriConstraint('/foo');
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testUriIsEvaluatedForEqualityAndCanFail()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasUriConstraint('/bar');
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testNonMessagesAreNotEvaluated()
    {
        $constraint = new HasUriConstraint('/foo');
        $constraint->evaluate('Ho, ho, ho');
    }

}