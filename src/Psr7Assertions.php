<?php
namespace Helmich\Psr7Assert;


use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\Psr7Assert\Constraint\BodyMatchesConstraint;
use Helmich\Psr7Assert\Constraint\HasHeaderConstraint;
use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_Constraint as Constraint;
use PHPUnit_Framework_Constraint_IsEqual as IsEqual;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;


trait Psr7Assertions
{



    public static function assertRequestHasUri(RequestInterface $request, $uri)
    {
        Assert::assertThat($request, static::hasUri($uri));
    }



    public static function assertMessageHasHeader(MessageInterface $message, $headerName, $headerValue = NULL)
    {
        Assert::assertThat($message, static::hasHeader($headerName, $headerValue));
    }



    public static function assertMessageBodyMatches(MessageInterface $message, $constraint)
    {
        Assert::assertThat($message, static::bodyMatches($constraint));
    }



    public static function assertMessageBodyMatchesJson(MessageInterface $message, array $jsonConstraints)
    {
        Assert::assertThat($message, static::bodyMatchesJson($jsonConstraints));
    }



    public static function hasUri($uri)
    {
        return new HasUriConstraint($uri);
    }



    public static function hasHeader($name, $constraint = NULL)
    {
        return new HasHeaderConstraint($name, $constraint);
    }



    public static function hasHeaderEqualTo($name, $expected)
    {
        return new HasHeaderConstraint($name, new IsEqual($expected));
    }



    public static function hasHeaderMatching($name, Constraint $constraint)
    {
        return new HasHeaderConstraint($name, $constraint);
    }



    public static function bodyMatches(Constraint $constraint)
    {
        return new BodyMatchesConstraint($constraint);
    }



    public static function bodyMatchesJson(array $constraints)
    {
        return Assert::logicalAnd(
            self::hasHeaderEqualTo('content-type', 'application/json'),
            self::bodyMatches(Assert::logicalAnd(
                Assert::isJson(),
                new JsonValueMatchesMany($constraints)
            ))
        );
    }



}