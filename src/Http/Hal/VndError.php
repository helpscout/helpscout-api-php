<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

class VndError
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string|null
     */
    private $logRef;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var self[]
     */
    private $errors = [];

    public function __construct(string $message, string $logRef = null, string $path = null)
    {
        $this->message = $message;
        $this->logRef = $logRef;
        $this->path = $path;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * If populated, include this in any support requests you submit to HelpScout which will help us identify
     * any issues you encounter.
     *
     * @return string|null
     */
    public function getLogRef()
    {
        return $this->logRef;
    }

    /**
     * Alias of getLogRef.  Internally we use the term correlationId so using this in the SDK will help developers
     * understand what we're asking for when we ask for this.
     *
     * @return string|null
     */
    public function getCorrelationId()
    {
        return $this->getLogRef();
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return self[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param self[] $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = [];
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    public function addError(self $error)
    {
        $this->errors[] = $error;
    }
}
