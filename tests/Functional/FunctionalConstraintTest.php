<?php
declare(strict_types=1);
namespace Helmich\Psr7Assert\Tests\Functional;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class FunctionalConstraintTest extends TestCase
{

    public function testHasUriCanSucceed()
    {
        $request = new Request('GET', '/foo');
        self::assertThat($request, hasUri('/foo'));
    }

    public function testHasUriCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/foo');
        self::assertThat($request, hasUri('/bar'));
    }

    public function testHasHeaderCanSucceedWithPrimitiveValue()
    {
        $request = new Request('GET', '/', ['x-foo' => 'bar']);
        self::assertThat($request, hasHeader('X-Foo', 'bar'));
    }

    public function testHasHeaderCanFailWithPrimitiveValue()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/', ['x-foo' => 'baz']);
        self::assertThat($request, hasHeader('X-Foo', 'bar'));
    }

    public function testHasHeaderCanFailWithNonExistingHeader()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/', []);
        self::assertThat($request, hasHeader('X-Foo', 'bar'));
    }

    public function testHasHeaderCanSucceedWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 14]);
        self::assertThat($request, hasHeader('X-Foo', self::greaterThanOrEqual(10)));
    }

    public function testHasHeadersCanSucceedWithConstraint()
    {
        $request = new Request('GET', '/', ['x-foo' => 14, 'content-type' => 'text/plain']);
        self::assertThat($request, hasHeaders([
            'X-Foo'        => Assert::greaterThanOrEqual(10),
            'Content-Type' => 'text/plain',
        ]));
    }

    public function testHasHeaderCanFailWithConstraint()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/', ['x-foo' => 4]);
        self::assertThat($request, hasHeader('X-Foo', self::greaterThanOrEqual(10)));
    }

    public function testBodyMatchesCanSucceed()
    {
        $request = new Request('GET', '/', [], 'foobar');
        self::assertThat($request, bodyMatches(self::equalTo('foobar')));
    }

    public function testBodyMatchesCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        $request = new Request('GET', '/', [], 'foobar');
        self::assertThat($request, bodyMatches(self::equalTo('barbaz')));
    }

    public function testBodyMatchesJsonCanSucceed()
    {
        $request = new Request('GET', '/foo', ['content-type' => 'application/json'], json_encode(['foo' => 'bar']));
        self::assertThat($request, bodyMatchesJson(['$.foo' => 'bar']));
    }

    public static function dataForRequestMethods()
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
        self::assertThat(new Request($method, '/'), hasMethod($method));
    }

    public function testIsGetCanSucceed()
    {
        self::assertThat(new Request('GET', '/'), isGet());
    }

    public function testIsGetCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Request('POST', '/'), isGet());
    }

    public function testIsPostCanSucceed()
    {
        self::assertThat(new Request('POST', '/'), isPost());
    }

    public function testIsPostCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Request('GET', '/'), isPost());
    }

    public function testIsPutCanSucceed()
    {
        self::assertThat(new Request('PUT', '/'), isPut());
    }

    public function testIsPutCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Request('GET', '/'), isPut());
    }

    public function testIsDeleteCanSucceed()
    {
        self::assertThat(new Request('DELETE', '/'), isDelete());
    }

    public function testIsDeleteCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Request('POST', '/'), isDelete());
    }

    public static function dataForStatusCodes()
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
        self::assertThat(new Response($status), hasStatus($status));
    }

    public function testHasStatusCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Response(400), hasStatus(200));
    }

    public function testIsSuccessCanSucceed()
    {
        self::assertThat(new Response(200), isSuccess());
    }

    public function testIsSuccessCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Response(404), isSuccess());
    }

    public function testIsClientErrorCanSucceed()
    {
        self::assertThat(new Response(404), isClientError());
    }

    public function testIsClientErrorCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Response(200), isClientError());
    }

    public function testIsServerErrorCanSucceed()
    {
        self::assertThat(new Response(503), isServerError());
    }

    public function testIsServerErrorCanFail()
    {
        $this->expectException(AssertionFailedError::class);

        self::assertThat(new Response(200), isServerError());
    }

    public function testHasContentTypeSucceedsOnEquality()
    {
        self::assertThat(new Response(200, ['content-type' => ['application/json']]), hasContentType('application/json'));
    }

    public function testHasContentTypeSucceedsWithCharset()
    {
        self::assertThat(new Response(200, ['content-type' => ['application/json;charset=utf8']]), hasContentType('application/json'));
    }

}
