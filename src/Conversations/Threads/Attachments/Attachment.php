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

    public function setId(int $id): Attachment
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     *
     * @return Attachment
     */
    public function setFilename($filename): Attachment
    {
        $this->filename = $filename;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     *
     * @return Attachment
     */
    public function setMimeType($mimeType): Attachment
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     *
     * @return Attachment
     */
    public function setData($data): Attachment
    {
        $this->data = $data;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): Attachment
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): Attachment
    {
        $this->height = $height;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): Attachment
    {
        $this->size = $size;

        return $this;
    }
}
