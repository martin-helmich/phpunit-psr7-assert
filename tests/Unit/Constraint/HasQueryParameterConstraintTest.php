<?php
declare(strict_types = 1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\uri_for;
use Helmich\Psr7Assert\Constraint\HasQueryParameterConstraint;
use PHPUnit\Framework\TestCase;

class HasQueryParameterConstraintTest extends TestCase
{
    public function testMatchesStringUrls()
    {
        $url = "https://some-domain.example/foo?bar=baz";
        $constraint = new HasQueryParameterConstraint("bar", "baz");

        self::assertThat($constraint->evaluate($url, "", true), self::isTrue());
    }

    public function testMatchesStringUrlsNegative()
    {
        $url = "https://some-domain.example/foo?bar=baz";
        $constraint = new HasQueryParameterConstraint("tulpen", "bunt");

        self::assertThat($constraint->evaluate($url, "", true), self::isFalse());
    }

    public function testMatchesPsr7Uris()
    {
        $uri = uri_for("https://some-domain.example/foo?bar=baz");
        $constraint = new HasQueryParameterConstraint("bar", "baz");

        self::assertThat($constraint->evaluate($uri, "", true), self::isTrue());
    }

    public function testMatchesPsr7UrisNegative()
    {
        $uri = uri_for("https://some-domain.example/foo?autobahn=1");
        $constraint = new HasQueryParameterConstraint("bar", "baz");

        self::assertThat($constraint->evaluate($uri, "", true), self::isFalse());
    }

    public function testMatchesRequest()
    {
        $uri = uri_for("https://some-domain.example/foo?bar=baz");
        $request = new Request("GET", $uri);

        $constraint = new HasQueryParameterConstraint("bar", "baz");

        self::assertThat($constraint->evaluate($request, "", true), self::isTrue());
    }
}