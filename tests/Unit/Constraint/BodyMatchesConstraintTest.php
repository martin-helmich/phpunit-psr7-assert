<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class BodyMatchesConstraintTest extends TestCase
{
    use ProphecyTrait;

    public static function dataForBadTypes()
    {
        return [
            ['foo'],
            [false],
            [[1, 2, 3]],
        ];
    }

    /**
     * @param $var
     * @dataProvider dataForBadTypes
     */
    public function testMatchFailsOnWrongType($var)
    {
        $inner = $this->prophesize(Constraint::class);

        $constraint = new BodyMatchesConstraint($inner->reveal());
        self::assertThat($constraint->evaluate($var, '', true), self::isFalse());
    }

    public static function dataForInnerConstraintIsEvaluatedWithMessageContent()
    {
        return [
            [uniqid(), true],
            [uniqid(), false],
            ['', true],
            ['', false],
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

        $inner = $this->prophesize(Constraint::class);
        $inner->evaluate($body, '', true)->shouldBeCalled()->willReturn($matches);

        $constraint = new BodyMatchesConstraint($inner->reveal());
        self::assertThat($constraint->evaluate($request, '', true), self::equalTo($matches));
    }

}
