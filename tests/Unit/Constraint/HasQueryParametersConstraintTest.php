<?php
declare(strict_types = 1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use Helmich\Psr7Assert\Constraint\HasQueryParametersConstraint;
use PHPUnit\Framework\TestCase;

class HasQueryParametersConstraintTest extends TestCase
{
    public function testMatchesStringUrls()
    {
        $url = "https://some-domain.example/foo?bar=baz&baz=bar";
        $constraint = new HasQueryParametersConstraint([
            "bar" => "baz",
            "baz" => "bar"
        ]);

        self::assertThat($constraint->evaluate($url, "", true), self::isTrue());
    }

    public function testMatchesStringUrlsNegative()
    {
        $url = "https://some-domain.example/foo?bar=baz&baz=foo";
        $constraint = new HasQueryParametersConstraint([
            "bar" => "baz",
            "baz" => "bar"
        ]);

        self::assertThat($constraint->evaluate($url, "", true), self::isFalse());
    }
}