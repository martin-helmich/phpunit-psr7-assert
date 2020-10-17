<?php

declare(strict_types=1);

namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use Helmich\Psr7Assert\Constraint\UrlEncodedMatches;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class UrlEncodedMatchesTest extends TestCase
{
    public function testMatchesValueWithCorrectKeyAndValue()
    {
        $constraint = new UrlEncodedMatches("foo", "bar");
        self::assertTrue($constraint->evaluate("foo=bar&baz=foo", "", true));
    }

    public function testDoesNotMatchValueWithIncorrectValue()
    {
        $constraint = new UrlEncodedMatches("foo", "wrong");
        self::assertFalse($constraint->evaluate("foo=bar&baz=foo", "", true));
    }

    public function testDoesNotMatchValueWithIncorrectKey()
    {
        $constraint = new UrlEncodedMatches("wrong", "bar");
        self::assertFalse($constraint->evaluate("foo=bar&baz=foo", "", true));
    }
}
