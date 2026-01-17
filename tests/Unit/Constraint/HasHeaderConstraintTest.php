<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HasHeaderConstraintTest extends TestCase
{

    public static function dataForHeaderValues(): array
    {
        return [
            ['foo'],
            [1234],
        ];
    }

    /**
     * @dataProvider dataForHeaderValues
     */
    #[DataProvider('dataForHeaderValues')]
    public function testDefaultConstraintMatchesWhenHeaderIsNotEmpty($header)
    {
        $request = new Request('POST', '/', ['x-foo' => $header]);

        $constraint = new HasHeaderConstraint('x-foo');
        self::assertTrue($constraint->evaluate($request, '', true));
    }

    public function testDefaultConstraintFailsWhenHeaderIsEmpty()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('POST', '/', ['x-foo' => '']);

        $constraint = new HasHeaderConstraint('x-foo');
        $constraint->evaluate($request);
    }

    public function testDefaultConstraintFailsWhenHeaderIsNotSet()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('POST', '/');

        $constraint = new HasHeaderConstraint('x-foo');
        $constraint->evaluate($request);
    }

    /**
     * @dataProvider dataForHeaderValues
     */
    #[DataProvider('dataForHeaderValues')]
    public function testPrimitiveConstraintMatchesOnEquality($header)
    {
        $request = new Request('POST', '/', ['x-foo' => $header]);

        $constraint = new HasHeaderConstraint('x-foo', $header);
        self::assertTrue($constraint->evaluate($request, '', true));
    }

    public function testComplexConstraintsAreMatchedOnEachHeader()
    {
        $request = new Request('POST', '/', ['x-foo' => ['foo', 'bar']]);
        $inner   = self::equalTo('foo');

        $constraint = new HasHeaderConstraint('x-foo', $inner);
        self::assertTrue($constraint->evaluate($request, '', true));
    }

    public function testComplexConstraintsAreMatchedOnEachHeaderAndCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('POST', '/', ['x-foo' => ['foo', 'bar']]);
        $inner   = self::equalTo('baz');

        $constraint = new HasHeaderConstraint('x-foo', $inner);
        $constraint->evaluate($request);
    }

    public function testNonMessagesAreNotEvaluated()
    {
        $this->expectException(AssertionFailedError::class);

        $constraint = new HasHeaderConstraint('x-foo');
        $constraint->evaluate('Ho, ho, ho');
    }

}
