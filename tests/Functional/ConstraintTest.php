<?php
namespace Helmich\Psr7Assert\Tests\Functional;


use GuzzleHttp\Psr7\Request;
use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_TestCase as TestCase;


class ConstraintTest extends TestCase
{



    use Psr7Assertions;



    public function testHasUriCanSucceed()
    {
        $request = new Request('GET', '/foo');
        $this->assertRequestHasUri($request, '/foo');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasUriCanFail()
    {
        $request = new Request('GET', '/foo');
        $this->assertRequestHasUri($request, '/bar');
    }



    public function testHasHeaderCanSucceedWithPrimitiveValue()
    {
        $request = new Request('GET', '/', ['x-foo' => 'bar']);
        $this->assertMessageHasHeader($request, 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $request = new Request('GET', '/', ['x-foo' => 'baz']);
        $this->assertMessageHasHeader($request, 'X-Foo', 'bar');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithNonExistingHeader()
    {
        $request = new Request('GET', '/', []);
        $this->assertMessageHasHeader($request, 'X-Foo', 'bar');
    }



    public function testHasHeaderCanSucceedWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 14]);
        $this->assertMessageHasHeader($request, 'X-Foo', Assert::greaterThanOrEqual(10));
    }



    public function testHasHeadersCanSucceedWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 14, 'content-type' => 'text/plain']);
        $this->assertMessageHasHeaders(
            $request,
            [
                'X-Foo'        => Assert::greaterThanOrEqual(10),
                'Content-Type' => 'text/plain',
            ]
        );
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 4]);
        $this->assertMessageHasHeader($request, 'X-Foo', Assert::greaterThanOrEqual(10));
    }



    public function testBodyMatchesCanSucceed()
    {
        $request = new Request('GET', '/', [], 'foobar');
        $this->assertMessageBodyMatches($request, Assert::equalTo('foobar'));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testBodyMatchesCanFail()
    {
        $request = new Request('GET', '/', [], 'foobar');
        $this->assertMessageBodyMatches($request, Assert::equalTo('barbaz'));
    }



    public function testBodyMatchesJsonCanSucceed()
    {
        $request = new Request('GET', '/foo', ['content-type' => 'application/json'], json_encode(['foo' => 'bar']));
        $this->assertMessageBodyMatchesJson($request, array('$.foo' => 'bar'));
    }



    public function dataForRequestMethods()
    {
        return [
            ['GET'],
            ['HEAD'],
            ['OPTIONS'],
            ['POST'],
            ['PUT'],
            ['PATCH'],
            ['DELETE'],
        ];
    }



    /**
     * @param $method
     * @dataProvider dataForRequestMethods
     */
    public function testAssertRequestHasMethodCanSucceed($method)
    {
        $this->assertRequestHasMethod(new Request($method, '/'), $method);
    }



    public function testIsGetCanSucceed()
    {
        $this->assertRequestIsGet(new Request('GET', '/'));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsGetCanFail()
    {
        $this->assertRequestIsGet(new Request('POST', '/'));
    }



    public function testIsPostCanSucceed()
    {
        $this->assertRequestIsPost(new Request('POST', '/'));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsPostCanFail()
    {
        $this->assertRequestIsPost(new Request('GET', '/'));
    }



    public function testIsPutCanSucceed()
    {
        $this->assertRequestIsPut(new Request('PUT', '/'));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsPutCanFail()
    {
        $this->assertRequestIsPut(new Request('GET', '/'));
    }



    public function testIsDeleteCanSucceed()
    {
        $this->assertRequestIsDelete(new Request('DELETE', '/'));
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsDeleteCanFail()
    {
        $this->assertRequestIsDelete(new Request('POST', '/'));
    }



}