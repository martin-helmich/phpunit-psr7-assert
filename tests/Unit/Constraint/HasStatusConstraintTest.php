<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Helmich\Psr7Assert\Constraint\HasStatusConstraint;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class HasStatusConstraintTest extends TestCase
{

    public function testStatusIsEvaluatedForEquality()
    {
        $response = new Response(234);

        $constraint = new HasStatusConstraint(234);
        self::assertTrue($constraint->evaluate($response, '', true));
    }

    public function testStatusCanBeAnyConstraint()
    {
        $response = new Response(234);

        $constraint = new HasStatusConstraint(Assert::lessThan(300));
        self::assertTrue($constraint->evaluate($response, '', true));
    }

    public function testMethodIsEvaluatedForEqualityAndCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $response = new Response(404);

        $constraint = new HasStatusConstraint(200);
        $constraint->evaluate($response);
    }

    public function testStatusCanBeAnyConstraintAndCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $response = new Response(234);

        $constraint = new HasStatusConstraint(Assert::greaterThanOrEqual(300));
        $constraint->evaluate($response);
    }

    public function testNonMessagesAreNotEvaluated()
    {
        $this->expectException(AssertionFailedError::class);

        $constraint = new HasStatusConstraint(200);
        $constraint->evaluate(new Request('POST', '/foo'));
    }

}
