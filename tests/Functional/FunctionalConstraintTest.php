<?php
namespace Helmich\Psr7Assert\Tests\Functional;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_TestCase as TestCase;

class FunctionalConstraintTest extends TestCase
{

    public function testHasUriCanSucceed()
    {
        $request = new Request('GET', '/foo');
        assertThat($request, hasUri('/foo'));
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasUriCanFail()
    {
        $request = new Request('GET', '/foo');
        assertThat($request, hasUri('/bar'));
    }

    public function testHasHeaderCanSucceedWithPrimitiveValue()
    {
        $request = new Request('GET', '/', ['x-foo' => 'bar']);
        assertThat($request, hasHeader('X-Foo', 'bar'));
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $request = new Request('GET', '/', ['x-foo' => 'baz']);
        assertThat($request, hasHeader('X-Foo', 'bar'));
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithNonExistingHeader()
    {
        $request = new Request('GET', '/', []);
        assertThat($request, hasHeader('X-Foo', 'bar'));
    }

    public function testHasHeaderCanSucceedWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 14]);
        assertThat($request, hasHeader('X-Foo', greaterThanOrEqual(10)));
    }

    public function testHasHeadersCanSucceedWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 14, 'content-type' => 'text/plain']);
        assertThat($request, hasHeaders([
            'X-Foo'        => Assert::greaterThanOrEqual(10),
            'Content-Type' => 'text/plain',
        ]));
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasHeaderCanFailWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 4]);
        assertThat($request, hasHeader('X-Foo', greaterThanOrEqual(10)));
    }

    public function testBodyMatchesCanSucceed()
    {
        $request = new Request('GET', '/', [], 'foobar');
        assertThat($request, bodyMatches(equalTo('foobar')));
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testBodyMatchesCanFail()
    {
        $request = new Request('GET', '/', [], 'foobar');
        assertThat($request, bodyMatches(equalTo('barbaz')));
    }

    public function testBodyMatchesJsonCanSucceed()
    {
        $request = new Request('GET', '/foo', ['content-type' => 'application/json'], json_encode(['foo' => 'bar']));
        assertThat($request, bodyMatchesJson(['$.foo' => 'bar']));
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
        assertThat(new Request($method, '/'), hasMethod($method));
    }

    public function testIsGetCanSucceed()
    {
        assertThat(new Request('GET', '/'), isGet());
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsGetCanFail()
    {
        assertThat(new Request('POST', '/'), isGet());
    }

    public function testIsPostCanSucceed()
    {
        assertThat(new Request('POST', '/'), isPost());
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsPostCanFail()
    {
        assertThat(new Request('GET', '/'), isPost());
    }

    public function testIsPutCanSucceed()
    {
        assertThat(new Request('PUT', '/'), isPut());
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsPutCanFail()
    {
        assertThat(new Request('GET', '/'), isPut());
    }

    public function testIsDeleteCanSucceed()
    {
        assertThat(new Request('DELETE', '/'), isDelete());
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testIsDeleteCanFail()
    {
        assertThat(new Request('POST', '/'), isDelete());
    }

    public function dataForStatusCodes()
    {
        return [
            [200],
            [400],
            [404],
            [500],
        ];
    }

    /**
     * @dataProvider dataForStatusCodes
     */
    public function testHasStatusCanSucceed($status)
    {
        assertThat(new Response($status), hasStatus($status));
    }

    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasStatusCanFail()
    {
        assertThat(new Response(400), hasStatus(200));
    }

}