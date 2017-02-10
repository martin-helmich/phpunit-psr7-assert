<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert;

use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasMethodConstraint;
use Helmich\Psr7Assert\Constraint\HasStatusConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait Psr7Assertions
{

    public static function assertRequestHasUri(RequestInterface $request, string $uri)
    {
        Assert::assertThat($request, static::hasUri($uri));
    }

    public static function assertMessageHasHeader(MessageInterface $message, string $headerName, $headerValue = null)
    {
        Assert::assertThat($message, static::hasHeader($headerName, $headerValue));
    }

    public static function assertMessageHasHeaders(MessageInterface $message, array $constraints)
    {
        Assert::assertThat($message, static::hasHeaders($constraints));
    }

    public static function assertMessageBodyMatches(MessageInterface $message, $constraint)
    {
        Assert::assertThat($message, static::bodyMatches($constraint));
    }

    public static function assertMessageBodyMatchesJson(MessageInterface $message, array $jsonConstraints)
    {
        Assert::assertThat($message, static::bodyMatchesJson($jsonConstraints));
    }

    public static function assertResponseHasStatus(ResponseInterface $response, int $status)
    {
        Assert::assertThat($response, static::hasStatus($status));
    }

    public static function assertResponseIsSuccess(ResponseInterface $response)
    {
        Assert::assertThat($response, static::isSuccess());
    }

    public static function assertResponseIsClientError(ResponseInterface $response)
    {
        Assert::assertThat($response, static::isClientError());
    }

    public static function assertResponseIsServerError(ResponseInterface $response)
    {
        Assert::assertThat($response, static::isServerError());
    }

    public static function assertRequestHasMethod(RequestInterface $request, string $method)
    {
        Assert::assertThat($request, static::hasMethod($method));
    }

    public static function assertRequestIsGet(RequestInterface $request)
    {
        Assert::assertThat($request, static::isGet());
    }

    public static function assertRequestIsPost(RequestInterface $request)
    {
        Assert::assertThat($request, static::isPost());
    }

    public static function assertRequestIsPut(RequestInterface $request)
    {
        Assert::assertThat($request, static::isPut());
    }

    public static function assertRequestIsDelete(RequestInterface $request)
    {
        Assert::assertThat($request, static::isDelete());
    }

    public static function hasUri(string $uri): Constraint
    {
        return new HasUriConstraint($uri);
    }

    public static function hasMethod(string $method): Constraint
    {
        return new HasMethodConstraint($method);
    }

    public static function hasStatus($status): Constraint
    {
        return new HasStatusConstraint($status);
    }

    public static function isSuccess(): Constraint
    {
        return new HasStatusConstraint(Assert::logicalAnd(Assert::greaterThanOrEqual(200), Assert::lessThan(300)));
    }

    public static function isClientError(): Constraint
    {
        return new HasStatusConstraint(Assert::logicalAnd(Assert::greaterThanOrEqual(400), Assert::lessThan(500)));
    }

    public static function isServerError(): Constraint
    {
        return new HasStatusConstraint(Assert::logicalAnd(Assert::greaterThanOrEqual(500), Assert::lessThan(600)));
    }

    public static function isGet(): Constraint
    {
        return static::hasMethod('GET');
    }

    public static function isPost(): Constraint
    {
        return static::hasMethod('POST');
    }

    public static function isPut(): Constraint
    {
        return static::hasMethod('PUT');
    }

    public static function isDelete(): Constraint
    {
        return static::hasMethod('DELETE');
    }

    public static function hasHeader(string $name, $constraint = null): Constraint
    {
        return new HasHeaderConstraint($name, $constraint);
    }

    public static function hasHeaders(array $constraints): Constraint
    {
        $headerConstraints = [];
        foreach ($constraints as $name => $constraint) {
            $headerConstraints[] = new HasHeaderConstraint($name, $constraint);
        }

        $conjunction = Assert::logicalAnd();
        $conjunction->setConstraints($headerConstraints);

        return $conjunction;
    }

    public static function hasHeaderEqualTo(string $name, string $expected): Constraint
    {
        return new HasHeaderConstraint($name, new IsEqual($expected));
    }

    public static function bodyMatches(Constraint $constraint): Constraint
    {
        return new BodyMatchesConstraint($constraint);
    }

    public static function bodyMatchesJson(array $constraints): Constraint
    {
        return Assert::logicalAnd(
            self::hasHeader('content-type', Assert::matchesRegularExpression(',^application/json(;.+)?$,')),
            self::bodyMatches(
                Assert::logicalAnd(
                    Assert::isJson(),
                    new JsonValueMatchesMany($constraints)
                )
            )
        );
    }
}
