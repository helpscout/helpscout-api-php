<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Http;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HelpScout\Api\Http\History;
use Http\Client\Exception\HttpException;
use Http\Client\Exception\TransferException;
use PHPUnit\Framework\TestCase;

class HistoryTest extends TestCase
{
    public function testAddSuccess()
    {
        $request = new Request('GET', '/');
        $response = new Response();

        $history = new History();
        $history->addSuccess($request, $response);

        $this->assertSame($response, $history->getLastResponse());
    }

    public function testAddFailureWithHttpException()
    {
        $request = new Request('GET', '/');
        $response = new Response();

        $history = new History();
        $history->addFailure($request, new HttpException('', $request, $response));

        $this->assertSame($response, $history->getLastResponse());
    }

    public function testAddFailureWithoutHttpException()
    {
        $request = new Request('GET', '/');

        $history = new History();
        $history->addFailure($request, new TransferException());

        $this->assertNull($history->getLastResponse());
    }
}
