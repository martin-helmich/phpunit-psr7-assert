<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class HasUriConstraintTest extends TestCase
{

    public function testUriIsEvaluatedForEquality()
    {
        $request = new Request('GET', '/foo');

        $constraint = new HasUriConstraint('/foo');
        self::assertTrue($constraint->evaluate($request, '', true));
    }

    public function testUriIsEvaluatedForEqualityAndCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/foo');

        $constraint = new HasUriConstraint('/bar');
        $constraint->evaluate($request);
    }

    public function testNonMessagesAreNotEvaluated()
    {
        $this->expectException(AssertionFailedError::class);

        $constraint = new HasUriConstraint('/foo');
        $constraint->evaluate('Ho, ho, ho');
    }

}
