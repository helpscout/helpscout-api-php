<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads\Attachments;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class Attachment implements Extractable, Hydratable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $data;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $size;

    /**
     * @return array
     */
    public function extract(): array
    {
        return [
            'id' => $this->id,
            'fileName' => $this->filename,
            'mimeType' => $this->mimeType,
            'data' => $this->data,
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
        ];
    }

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId((int) $data['id']);
        }

        // An inconsistency is present in the Helpscout API where the attachment fields when being defined and when
        // being fetched are of different cases, so we standardize that here
        if (isset($data['filename'])) {
            $this->setFilename($data['filename']);
        } elseif (isset($data['fileName'])) {
            $this->setFilename($data['fileName']);
        }

        if (isset($data['mimeType'])) {
            $this->setMimeType($data['mimeType']);
        }

        if (isset($data['data'])) {
            $this->setData($data['data']);
        }

        if (isset($data['width']) && is_numeric($data['width'])) {
            $this->setWidth((int) $data['width']);
        }

        if (isset($data['height']) && is_numeric($data['height'])) {
            $this->setHeight((int) $data['height']);
        }

        if (isset($data['size']) && is_numeric($data['size'])) {
            $this->setSize((int) $data['size']);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width)
    {
        Assert::greaterThan($width, 0);

        $this->width = $width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height)
    {
        Assert::greaterThan($height, 0);

        $this->height = $height;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size)
    {
        Assert::greaterThan($size, 0);

        $this->size = $size;
    }
}
