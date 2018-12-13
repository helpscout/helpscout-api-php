<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http\Plugin;

use HelpScout\Api\Exception\AuthenticationException;
use HelpScout\Api\Http\Plugin\AuthenticationPlugin;
use Http\Message\Authentication;
use Http\Promise\Promise;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class AuthenticationPluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var AuthenticationPlugin
     */
    private $plugin;

    public function setUp()
    {
        $this->plugin = new AuthenticationPlugin();
    }

    public function testAuthenticatesTheRequest()
    {
        /** @var RequestInterface $request */
        $request = Mockery::mock(RequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->once()
            ->with('X-Token-Request')
            ->andReturn(false);

        /** @var RequestInterface $authenticatedRequest */
        $authenticatedRequest = Mockery::mock(RequestInterface::class);

        /** @var Authentication $authentication */
        $authentication = Mockery::mock(Authentication::class);
        $authentication->shouldReceive('authenticate')
            ->once()
            ->with($request)
            ->andReturn($authenticatedRequest);

        $next = function (RequestInterface $request) use ($authenticatedRequest) {
            $this->assertSame($authenticatedRequest, $request);

            return Mockery::mock(Promise::class);
        };

        $this->plugin->setAuthentication($authentication);
        $this->plugin->handleRequest($request, $next, function () {});
    }

    public function testSkipsAuthenticationWhenHeaderPresent()
    {
        /** @var RequestInterface $request */
        $request = Mockery::mock(RequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->once()
            ->with('X-Token-Request')
            ->andReturn(true);

        /** @var Authentication $authentication */
        $authentication = Mockery::mock(Authentication::class)
            ->shouldNotReceive('authenticate')
            ->getMock();

        $next = function (RequestInterface $requestInterface) use ($request) {
            $this->assertSame($request, $requestInterface);

            return Mockery::mock(Promise::class);
        };

        $this->plugin->setAuthentication($authentication);
        $this->plugin->handleRequest($request, $next, function () {});
    }

    public function testThrowsExceptionWhenNotAuthenticated()
    {
        $this->expectException(AuthenticationException::class);

        /** @var RequestInterface $request */
        $request = Mockery::mock(RequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->once()
            ->with('X-Token-Request')
            ->andReturn(false);

        $next = function (RequestInterface $request) {};

        $this->plugin->handleRequest($request, $next, function () {});
    }
}
