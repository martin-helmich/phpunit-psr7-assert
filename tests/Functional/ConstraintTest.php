<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Functional;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class ConstraintTest extends TestCase
{

    use Psr7Assertions;

    public function testHasUriCanSucceed()
    {
        $request = new Request('GET', '/foo');
        $this->assertRequestHasUri($request, '/foo');
    }

    public function testHasUriCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/foo');
        $this->assertRequestHasUri($request, '/bar');
    }

    public function testHasHeaderCanSucceedWithPrimitiveValue()
    {
        $request = new Request('GET', '/', ['x-foo' => 'bar']);
        $this->assertMessageHasHeader($request, 'X-Foo', 'bar');
    }

    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/', ['x-foo' => 'baz']);
        $this->assertMessageHasHeader($request, 'X-Foo', 'bar');
    }

    public function testHasHeaderCanFailWithNonExistingHeader()
    {
        $this->expectException(AssertionFailedError::class);

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
                'X-Foo' => Assert::greaterThanOrEqual(10),
                'Content-Type' => 'text/plain',
            ]
        );
    }

    public function testHasHeaderCanFailWithConstraint()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/', ['x-foo' => 4]);
        $this->assertMessageHasHeader($request, 'X-Foo', Assert::greaterThanOrEqual(10));
    }

    public function testBodyMatchesCanSucceed()
    {
        $request = new Request('GET', '/', [], 'foobar');
        $this->assertMessageBodyMatches($request, Assert::equalTo('foobar'));
    }

    public function testBodyMatchesCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/', [], 'foobar');
        $this->assertMessageBodyMatches($request, Assert::equalTo('barbaz'));
    }

    public function testBodyMatchesJsonCanSucceed()
    {
        $request = new Request('GET', '/foo', ['content-type' => 'application/json'], json_encode(['foo' => 'bar']));
        $this->assertMessageBodyMatchesJson($request, array('$.foo' => 'bar'));
    }

    public function testBodyMatchesJsonCanSucceedWithCharset()
    {
        $request = new Request('GET', '/foo', ['content-type' => 'application/json;charset=utf8'], json_encode(['foo' => 'bar']));
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

    public function testIsGetCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertRequestIsGet(new Request('POST', '/'));
    }

    public function testIsPostCanSucceed()
    {
        $this->assertRequestIsPost(new Request('POST', '/'));
    }

    public function testIsPostCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertRequestIsPost(new Request('GET', '/'));
    }

    public function testIsPutCanSucceed()
    {
        $this->assertRequestIsPut(new Request('PUT', '/'));
    }

    public function testIsPutCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertRequestIsPut(new Request('GET', '/'));
    }

    public function testIsDeleteCanSucceed()
    {
        $this->assertRequestIsDelete(new Request('DELETE', '/'));
    }

    public function testIsDeleteCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertRequestIsDelete(new Request('POST', '/'));
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
        $this->assertResponseHasStatus(new Response($status), $status);
    }

    public function testHasStatusCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertResponseHasStatus(new Response(400), 200);
    }

    public function testIsSuccessCanSucceed()
    {
        $this->assertResponseIsSuccess(new Response(200));
    }

    public function testIsSuccessCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertResponseIsSuccess(new Response(404));
    }

    public function testIsRedirectCanSucceed()
    {
        $this->assertResponseIsRedirect(new Response(300));
    }

    public function testIsRedirectCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertResponseIsRedirect(new Response(200));
    }

    public function testIsClientErrorCanSucceed()
    {
        $this->assertResponseIsClientError(new Response(404));
    }

    public function testIsClientErrorCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertResponseIsClientError(new Response(200));
    }

    public function testIsServerErrorCanSucceed()
    {
        $this->assertResponseIsServerError(new Response(503));
    }

    public function testIsServerErrorCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertResponseIsServerError(new Response(200));
    }

}
