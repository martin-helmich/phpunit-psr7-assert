<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert;

use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasMethodConstraint;
use Helmich\Psr7Assert\Constraint\HasQueryParameterConstraint;
use Helmich\Psr7Assert\Constraint\HasStatusConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use Helmich\Psr7Assert\Constraint\IsAbsoluteUriConstraint;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

trait Psr7Assertions
{

    public static function assertRequestHasUri(RequestInterface $request, string $uri): void
    {
        Assert::assertThat($request, static::hasUri($uri));
    }

    public static function assertMessageHasHeader(MessageInterface $message, string $headerName, $headerValue = null): void
    {
        Assert::assertThat($message, static::hasHeader($headerName, $headerValue));
    }

    public static function assertMessageHasHeaders(MessageInterface $message, array $constraints): void
    {
        Assert::assertThat($message, static::hasHeaders($constraints));
    }

    public static function assertMessageBodyMatches(MessageInterface $message, $constraint): void
    {
        Assert::assertThat($message, static::bodyMatches($constraint));
    }

    public static function assertMessageBodyMatchesJson(MessageInterface $message, array $jsonConstraints): void
    {
        Assert::assertThat($message, static::bodyMatchesJson($jsonConstraints));
    }

    public static function assertResponseHasStatus(ResponseInterface $response, int $status): void
    {
        Assert::assertThat($response, static::hasStatus($status));
    }

    public static function assertResponseIsSuccess(ResponseInterface $response): void
    {
        Assert::assertThat($response, static::isSuccess());
    }

    public static function assertResponseIsClientError(ResponseInterface $response): void
    {
        Assert::assertThat($response, static::isClientError());
    }

    public static function assertResponseIsServerError(ResponseInterface $response): void
    {
        Assert::assertThat($response, static::isServerError());
    }

    public static function assertRequestHasMethod(RequestInterface $request, string $method): void
    {
        Assert::assertThat($request, static::hasMethod($method));
    }

    public static function assertRequestIsGet(RequestInterface $request): void
    {
        Assert::assertThat($request, static::isGet());
    }

    public static function assertRequestIsPost(RequestInterface $request): void
    {
        Assert::assertThat($request, static::isPost());
    }

    public static function assertRequestIsPut(RequestInterface $request): void
    {
        Assert::assertThat($request, static::isPut());
    }

    public static function assertRequestIsDelete(RequestInterface $request): void
    {
        Assert::assertThat($request, static::isDelete());
    }

    /**
     * @param string $uri
     */
    public static function assertStringIsAbsoluteUri(string $uri): void
    {
        Assert::assertThat($uri, static::isAbsoluteUri());
    }

    /**
     * @param string|UriInterface|RequestInterface $uriOrRequest
     * @param string|Constraint                    $name
     * @param string|Constraint|null               $value
     */
    public static function assertHasQueryParameter($uriOrRequest, $name, $value = null): void
    {
        Assert::assertThat($uriOrRequest, static::hasQueryParameter($name, $value));
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

    /**
     * @param string|Constraint      $name
     * @param string|Constraint|null $value
     * @return Constraint
     */
    public static function hasQueryParameter($name, $value = null): Constraint
    {
        return new HasQueryParameterConstraint($name, $value);
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

    /**
     * @return Constraint
     */
    public static function isAbsoluteUri(): Constraint
    {
        return new IsAbsoluteUriConstraint();
    }
}
