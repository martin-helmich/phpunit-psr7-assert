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
        $request = $this->buildRequestWithHeader('x-foo', 'bar');
        $this->assertMessageHasHeader($request, 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $request = $this->buildRequestWithHeader('x-foo', 'baz');
        $this->assertMessageHasHeader($request, 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithNonExistingHeader()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn([]);
        $request->getHeader('x-foo')->willReturn([]);
        $request->hasHeader('x-foo')->willReturn(FALSE);

        $this->assertMessageHasHeader($request->reveal(), 'X-Foo', 'bar');
    }



    public function testHasHeaderCanSucceedWithConstraint()
    {
        $request = $this->buildRequestWithHeader('x-foo', 14);
        $this->assertMessageHasHeader($request, 'X-Foo', Assert::greaterThanOrEqual(10));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithConstraint()
    {
        $request = $this->buildRequestWithHeader('x-foo', 4);
        $this->assertMessageHasHeader($request, 'X-Foo', Assert::greaterThanOrEqual(10));
    }



    /**
     * @param $name
     * @param $value
     * @return RequestInterface
     */
    private function buildRequestWithHeader($name, $value)
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getHeaders()->willReturn([$name => [$value]]);
        $request->getHeader($name)->willReturn([$value]);
        $request->hasHeader($name)->willReturn(TRUE);
        return $request->reveal();
    }


}