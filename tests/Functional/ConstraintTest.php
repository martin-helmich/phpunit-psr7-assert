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
        $request->getHeaders()->willReturn(['x-foo' => 'bar']);
        $request->getHeader('x-foo')->willReturn('bar');
        $request->hasHeader('x-foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn(['x-foo' => 'baz']);
        $request->getHeader('x-foo')->willReturn('baz');
        $request->hasHeader('x-foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithNonExistingHeader()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn([]);
        $request->getHeader('x-foo')->willReturn(NULL);
        $request->hasHeader('x-foo')->willReturn(FALSE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', 'bar');
    }



    public function testHasHeaderCanSucceedWithConstraint()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn(['x-foo' => 14]);
        $request->getHeader('x-foo')->willReturn(14);
        $request->hasHeader('x-foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', Assert::greaterThanOrEqual(10));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithConstraint()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn(['x-foo' => 4]);
        $request->getHeader('x-foo')->willReturn(4);
        $request->hasHeader('x-foo')->willReturn(TRUE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', Assert::greaterThanOrEqual(10));
    }


}