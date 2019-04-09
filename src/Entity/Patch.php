<?php

declare(strict_types=1);

namespace HelpScout\Api\Entity;

class Patch implements Extractable
{
    /**
     * @var string
     */
    protected $operation;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var mixed
     */
    protected $value;

    public function __construct(string $operation, string $path, $value = null)
    {
        $this->operation = $operation;
        $this->path = $path;
        $this->value = $value;
    }

    public function extract(): array
    {
        $path = $this->getPath();

        if ($path[0] != '/') {
            $path = '/'.$path;
        }

        $data = [
            'op' => $this->getOperation(),
            'path' => $path,
        ];

        if ($data['op'] != 'remove') {
            $data['value'] = $this->getValue();
        }

        return $data;
    }

    public static function replace(string $path, $value): self
    {
        return new self('replace', $path, $value);
    }

    public static function move(string $path, $value): self
    {
        return new self('move', $path, $value);
    }

    public static function remove(string $path): self
    {
        return new self('remove', $path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }
}
