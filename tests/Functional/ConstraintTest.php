<?php
namespace Helmich\Psr7Assert\Tests\Functional;


use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;


class ConstraintTest extends TestCase
{



    use Psr7Assertions;



    public function testHasUriCanSucceed()
    {
        $uri = $this->prophesize(UriInterface::class);
        $uri->__toString()->willReturn('/foo');

        $request = $this->prophesize(RequestInterface::class);
        $request->getUri()->willReturn($uri);

        $this->assertRequestHasUri($request->reveal(), '/foo');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasUriCanFail()
    {
        $uri = $this->prophesize(UriInterface::class);
        $uri->__toString()->willReturn('/foo');

        $request = $this->prophesize(RequestInterface::class);
        $request->getUri()->willReturn($uri);

        $this->assertRequestHasUri($request->reveal(), '/bar');
    }



    public function testHasHeaderCanSucceedWithPrimitiveValue()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn(['X-Foo' => 'bar']);
        $request->getHeader('X-Foo')->willReturn('bar');
        $request->hasHeader('X-Foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn(['X-Foo' => 'baz']);
        $request->getHeader('X-Foo')->willReturn('baz');
        $request->hasHeader('X-Foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithNonExistingHeader()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn([]);
        $request->getHeader('X-Foo')->willReturn(NULL);
        $request->hasHeader('X-Foo')->willReturn(FALSE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', 'bar');
    }



    public function testHasHeaderCanSucceedWithConstraint()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn(['X-Foo' => 14]);
        $request->getHeader('X-Foo')->willReturn(14);
        $request->hasHeader('X-Foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', Assert::greaterThanOrEqual(10));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithConstraint()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn(['X-Foo' => 4]);
        $request->getHeader('X-Foo')->willReturn(4);
        $request->hasHeader('X-Foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', Assert::greaterThanOrEqual(10));
    }


}