<?php
namespace Helmich\Psr7Assert;


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



}