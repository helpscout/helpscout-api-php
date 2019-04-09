<?php

declare(strict_types=1);

namespace HelpScout\Api\Exception;

use GuzzleHttp\Exception\RequestException;

class RateLimitExceededException extends RequestException implements Exception
{
}
