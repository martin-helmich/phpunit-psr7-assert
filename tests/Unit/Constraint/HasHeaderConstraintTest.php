<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use PHPUnit\Framework\TestCase;

class HasHeaderConstraintTest extends TestCase
{

    public function dataForHeaderValues()
    {
        return [
            ['foo'],
            [1234],
        ];
    }

    /**
     * @param $header
     * @dataProvider dataForHeaderValues
     */
    public function testDefaultConstraintMatchesWhenHeaderIsNotEmpty($header)
    {
        $request = new Request('POST', '/', ['x-foo' => $header]);

        $constraint = new HasHeaderConstraint('x-foo');
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testDefaultConstraintFailsWhenHeaderIsEmpty()
    {
        $request = new Request('POST', '/', ['x-foo' => '']);

        $constraint = new HasHeaderConstraint('x-foo');
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testDefaultConstraintFailsWhenHeaderIsNotSet()
    {
        $request = new Request('POST', '/');

        $constraint = new HasHeaderConstraint('x-foo');
        $constraint->evaluate($request);
    }

    /**
     * @param $header
     * @dataProvider dataForHeaderValues
     */
    public function testPrimitiveConstraintMatchesOnEquality($header)
    {
        $request = new Request('POST', '/', ['x-foo' => $header]);

        $constraint = new HasHeaderConstraint('x-foo', $header);
        $constraint->evaluate($request);
    }

    public function testComplexConstraintsAreMatchedOnEachHeader()
    {
        $request = new Request('POST', '/', ['x-foo' => ['foo', 'bar']]);
        $inner   = self::equalTo('foo');

        $constraint = new HasHeaderConstraint('x-foo', $inner);
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testComplexConstraintsAreMatchedOnEachHeaderAndCanFail()
    {
        $request = new Request('POST', '/', ['x-foo' => ['foo', 'bar']]);
        $inner   = self::equalTo('baz');

        $constraint = new HasHeaderConstraint('x-foo', $inner);
        $constraint->evaluate($request);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testNonMessagesAreNotEvaluated()
    {
        $constraint = new HasHeaderConstraint('x-foo');
        $constraint->evaluate('Ho, ho, ho');
    }

}