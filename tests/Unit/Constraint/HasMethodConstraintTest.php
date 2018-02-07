<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasMethodConstraint;
use PHPUnit\Framework\TestCase;

class HasMethodConstraintTest extends TestCase
{

    public function testMethodIsEvaluatedForEquality()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasMethodConstraint('GET');
        self::assertTrue($constraint->evaluate($request, '', true));
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testMethodIsEvaluatedForEqualityAndCanFail()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasMethodConstraint('POST');
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testNonMessagesAreNotEvaluated()
    {
        $constraint = new HasMethodConstraint('GET');
        $constraint->evaluate('Ho, ho, ho');
    }

}
