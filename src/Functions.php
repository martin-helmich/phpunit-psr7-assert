<?php
declare(strict_types=1);

use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use Helmich\Psr7Assert\Constraint\IsAbsoluteUriConstraint;
use Helmich\Psr7Assert\Psr7AssertionsClass;
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
    return Psr7AssertionsClass::hasHeaders($constraints);
}

function hasStatus($status): Constraint
{
    return Psr7AssertionsClass::hasStatus($status);
}

function hasQueryParameter($name, $value = null): Constraint
{
    return Psr7AssertionsClass::hasQueryParameter($name, $value);
}

function hasQueryParameters(array $constraints): Constraint
{
    return Psr7AssertionsClass::hasQueryParameters($constraints);
}

function isSuccess(): Constraint
{
    return Psr7AssertionsClass::isSuccess();
}

function isRedirect(): Constraint
{
    return Psr7AssertionsClass::isRedirect();
}

function isClientError(): Constraint
{
    return Psr7AssertionsClass::isClientError();
}

function isServerError(): Constraint
{
    return Psr7AssertionsClass::isServerError();
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
    return Psr7AssertionsClass::hasMethod($method);
}

function isGet(): Constraint
{
    return Psr7AssertionsClass::isGet();
}

function isPost(): Constraint
{
    return Psr7AssertionsClass::isPost();
}

function isPut(): Constraint
{
    return Psr7AssertionsClass::isPut();
}

function isDelete(): Constraint
{
    return Psr7AssertionsClass::isDelete();
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

function bodyMatchesForm(array $constraints): Constraint
{
    return Psr7AssertionsClass::bodyMatchesForm($constraints);
}

function isAbsoluteUri(): Constraint
{
    return new IsAbsoluteUriConstraint();
}
