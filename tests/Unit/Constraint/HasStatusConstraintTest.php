<?php
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Helmich\Psr7Assert\Constraint\HasStatusConstraint;
use PHPUnit_Framework_TestCase as TestCase;

class HasStatusConstraintTest extends TestCase
{

    public function testStatusIsEvaluatedForEquality()
    {
        $response = new Response(234);

        $constraint = new HasStatusConstraint(234);
        $constraint->evaluate($response);
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testMethodIsEvaluatedForEqualityAndCanFail()
    {
        $response = new Response(404);

        $constraint = new HasStatusConstraint(200);
        $constraint->evaluate($response);
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testNonMessagesAreNotEvaluated()
    {
        $constraint = new HasStatusConstraint(200);
        $constraint->evaluate(new Request('POST', '/foo'));
    }

}