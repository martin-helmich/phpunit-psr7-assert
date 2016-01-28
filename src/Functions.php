<?php

use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit_Framework_Assert as Assert;

function hasUri($uri)
{
    return new HasUriConstraint($uri);
}

function hasHeader($name, $constraint = null)
{
    return new HasHeaderConstraint($name, $constraint);
}

function hasHeaders(array $constraints)
{
    return Psr7Assertions::hasHeaders($constraints);
}

function hasStatus($status)
{
    return Psr7Assertions::hasStatus($status);
}

function isSuccess()
{
    return Psr7Assertions::isSuccess();
}

function isClientError()
{
    return Psr7Assertions::isClientError();
}

function isServerError()
{
    return Psr7Assertions::isServerError();
}

function hasContentType($contentType)
{
    return new HasHeaderConstraint(
        'Content-Type',
        Assert::matchesRegularExpression(',^' . preg_quote($contentType) . '(;.+)?$,')
    );
}

function hasMethod($method)
{
    return Psr7Assertions::hasMethod($method);
}

function isGet()
{
    return Psr7Assertions::isGet();
}

function isPost()
{
    return Psr7Assertions::isPost();
}

function isPut()
{
    return Psr7Assertions::isPut();
}

function isDelete()
{
    return Psr7Assertions::isDelete();
}

function bodyMatches($constraint)
{
    return new BodyMatchesConstraint($constraint);
}

function bodyMatchesJson($constraints)
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
