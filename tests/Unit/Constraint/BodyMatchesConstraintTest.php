<?php
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;


use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use PHPUnit_Framework_TestCase as TestCase;


class BodyMatchesConstraintTest extends TestCase
{



    public function dataForBadTypes()
    {
        return [
            ['foo'],
            [FALSE],
            [[1, 2, 3]],
        ];
    }



    /**
     * @param $var
     * @dataProvider dataForBadTypes
     */
    public function testMatchFailsOnWrongType($var)
    {
        $inner = $this->prophesize('PHPUnit_Framework_Constraint');

        $constraint = new BodyMatchesConstraint($inner->reveal());
        self::assertThat($constraint->evaluate($var, '', TRUE), self::isFalse());
    }



    public function dataForInnerConstraintIsEvaluatedWithMessageContent()
    {
        return [
            [uniqid(), TRUE],
            [uniqid(), FALSE],
            ['', TRUE],
            ['', FALSE],
        ];
    }



    /**
     * @param $body
     * @param $matches
     * @dataProvider dataForInnerConstraintIsEvaluatedWithMessageContent
     */
    public function testInnerConstraintIsEvaluatedWithMessageContent($body, $matches)
    {
        $request = new Request('POST', '/', [], $body);

        $inner = $this->prophesize('PHPUnit_Framework_Constraint');
        $inner->evaluate($body, '', TRUE)->shouldBeCalled()->willReturn($matches);

        $constraint = new BodyMatchesConstraint($inner->reveal());
        self::assertThat($constraint->evaluate($request, '', TRUE), self::equalTo($matches));
    }

}