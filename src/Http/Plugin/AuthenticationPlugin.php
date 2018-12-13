<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Plugin;

use HelpScout\Api\Exception\AuthenticationException;
use Http\Client\Common\Plugin;
use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;

class AuthenticationPlugin implements Plugin
{
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * @param Authentication $authentication
     */
    public function setAuthentication(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        if ($request->hasHeader('X-Token-Request')) {
            return $next($request);
        }

        if ($this->authentication === null) {
            throw new AuthenticationException('Client not authenticated');
        }

        $request = $this->authentication->authenticate($request);

        return $next($request);
    }
}
