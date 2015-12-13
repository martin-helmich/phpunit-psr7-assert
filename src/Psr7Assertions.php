<?php
namespace Helmich\Psr7Assert;


use Helmich\Psr7Assert\Constraint\HasUriConstraint;
use PHPUnit_Framework_Assert as Assert;
use Psr\Http\Message\RequestInterface;


trait Psr7Assertions
{



    static public function assertRequestHasUri(RequestInterface $request, $uri)
    {
        Assert::assertThat($request, static::hasUri($uri));
    }



    public static function hasUri($uri)
    {
        return new HasUriConstraint($uri);
    }



}