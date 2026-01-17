<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\TestCase;

class BodyMatchesConstraintTest extends TestCase
{
    public static function dataForBadTypes(): array
    {
        return [
            ['foo'],
            [false],
            [[1, 2, 3]],
        ];
    }

    /**
     * @dataProvider dataForBadTypes
     */
    #[DataProvider('dataForBadTypes')]
    public function testMatchFailsOnWrongType($var)
    {
        $inner = Mockery::mock(Constraint::class);

        $constraint = new BodyMatchesConstraint($inner);
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
     * @dataProvider dataForInnerConstraintIsEvaluatedWithMessageContent
     */
    #[DataProvider('dataForInnerConstraintIsEvaluatedWithMessageContent')]
    public function testInnerConstraintIsEvaluatedWithMessageContent($body, $matches)
    {
        $request = new Request('POST', '/', [], $body);

        $inner = Mockery::mock(Constraint::class, function ($mock) use ($body, $matches) {
            $mock->shouldReceive('evaluate')
                ->with($body, '', true)
                ->andReturn($matches);
        });

        $constraint = new BodyMatchesConstraint($inner);
        self::assertThat($constraint->evaluate($request, '', true), self::equalTo($matches));
    }

}
