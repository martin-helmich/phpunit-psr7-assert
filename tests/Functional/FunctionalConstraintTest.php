<?php
namespace Helmich\Psr7Assert\Tests\Functional;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class FunctionalConstraintTest extends TestCase
{

    public function testHasUriCanSucceed()
    {
        $request = new Request('GET', '/foo');
        assertThat($request, hasUri('/foo'));
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $request = new Request('GET', '/', ['x-foo' => 'baz']);
        assertThat($request, hasHeader('X-Foo', 'bar'));
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
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
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testHasStatusCanFail()
    {
        assertThat(new Response(400), hasStatus(200));
    }

    public function testIsSuccessCanSucceed()
    {
        assertThat(new Response(200), isSuccess());
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsSuccessCanFail()
    {
        assertThat(new Response(404), isSuccess());
    }

    public function testIsClientErrorCanSucceed()
    {
        assertThat(new Response(404), isClientError());
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsClientErrorCanFail()
    {
        assertThat(new Response(200), isClientError());
    }

    public function testIsServerErrorCanSucceed()
    {
        assertThat(new Response(503), isServerError());
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testIsServerErrorCanFail()
    {
        assertThat(new Response(200), isServerError());
    }

    public function testHasContentTypeSucceedsOnEquality()
    {
        assertThat(new Response(200, ['content-type' => ['application/json']]), hasContentType('application/json'));
    }

    public function testHasContentTypeSucceedsWithCharset()
    {
        assertThat(new Response(200, ['content-type' => ['application/json;charset=utf8']]), hasContentType('application/json'));
    }

}