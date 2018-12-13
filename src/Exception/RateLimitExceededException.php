<?php

declare(strict_types=1);

namespace HelpScout\Api\Exception;

use Http\Client\Exception\HttpException;

class RateLimitExceededException extends HttpException implements Exception
{
}
