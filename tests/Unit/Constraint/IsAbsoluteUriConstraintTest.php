<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Unit\Constraint;

use Helmich\Psr7Assert\Constraint\IsAbsoluteUriConstraint;
use PHPUnit\Framework\TestCase;

class IsAbsoluteUriConstraintTest extends TestCase
{
    public function validUrls(): array {
        return [
            ["http://google.de"],
            ["http://localhost"],
            ["http://127.0.0.1"],
            ["http://[::1]"],
            ["http://localhost:1234"],
            ["http://localhost:1234/foobar"],
            ["http://localhost:1234/foobar?asdasd=123123"],
            ["http://horst:geheim@localhost:1234/foobar?asdasd=123123"],
            ["ftp://localhost:1234"],
        ];
    }

    public function invalidUrls(): array {
        return [
            ["http://"],
            ["asdfasdf"],
        ];
    }

    /**
     * @dataProvider validUrls
     * @param $validUrl
     */
    public function testValidUrlMatches($validUrl)
    {
        $constraint = new IsAbsoluteUriConstraint();
        assertThat($constraint->evaluate($validUrl, "", true), isTrue());
    }

    /**
     * @dataProvider invalidUrls
     * @param $invalidUrl
     */
    public function testInvalidUrlDoesNotMatch($invalidUrl)
    {
        $constraint = new IsAbsoluteUriConstraint();
        assertThat($constraint->evaluate($invalidUrl, "", true), isFalse());
    }
}