<?php
namespace Helmich\Psr7Assert\Tests\Functional;


use Helmich\Psr7Assert\Psr7Assertions;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\RequestInterface;


class ConstraintTest extends TestCase
{



    use Psr7Assertions;



    public function testHasUriCanSucceed()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getUri()->willReturn('/foo');

        $this->assertRequestHasUri($request->reveal(), '/foo');
    }



    /**
     * @expectedException \PHPUnit_Framework_AssertionFailedError
     */
    public function testHasUriCanFail()
    {
        $request = $this->prophesize(RequestInterface::class);
        $request->getUri()->willReturn('/foo');

        $this->assertRequestHasUri($request->reveal(), '/bar');
    }


}