<?php
declare(strict_types=1);

use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;

function hasUri(string $uri): HasUriConstraint
{
    return new HasUriConstraint($uri);
}

function hasHeader(string $name, $constraint = null): HasHeaderConstraint
{
    return new HasHeaderConstraint($name, $constraint);
}

function hasHeaders(array $constraints): Constraint
{
    return Psr7Assertions::hasHeaders($constraints);
}

function hasStatus($status): Constraint
{
    return Psr7Assertions::hasStatus($status);
}

function isSuccess(): Constraint
{
    return Psr7Assertions::isSuccess();
}

function isClientError(): Constraint
{
    return Psr7Assertions::isClientError();
}

function isServerError(): Constraint
{
    return Psr7Assertions::isServerError();
}

function hasContentType(string $contentType): Constraint
{
    return new HasHeaderConstraint(
        'Content-Type',
        Assert::matchesRegularExpression(',^' . preg_quote($contentType, '/') . '(;.+)?$,')
    );
}

function hasMethod(string $method): Constraint
{
    return Psr7Assertions::hasMethod($method);
}

function isGet(): Constraint
{
    return Psr7Assertions::isGet();
}

function isPost(): Constraint
{
    return Psr7Assertions::isPost();
}

function isPut(): Constraint
{
    return Psr7Assertions::isPut();
}

function isDelete(): Constraint
{
    return Psr7Assertions::isDelete();
}

function bodyMatches($constraint): Constraint
{
    return new BodyMatchesConstraint($constraint);
}

function bodyMatchesJson($constraints): Constraint
{
    return Assert::logicalAnd(
        hasContentType('application/json'),
        bodyMatches(
            Assert::logicalAnd(
                Assert::isJson(),
                new JsonValueMatchesMany($constraints)
            )
        )
    );
}
