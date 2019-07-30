<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Hal;

use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Exception\JsonException;

class HalDeserializer
{
    const LINKS = '_links';
    const EMBEDDED = '_embedded';

    /**
     * @param string $json
     *
     * @return HalDocument
     */
    public static function deserializeDocument(string $json): HalDocument
    {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException('Unable to parse HAL document JSON: '.json_last_error_msg());
        }

        return self::createDocument($data);
    }

    /**
     * @param \Closure|string $entityClass
     * @param HalDocument     $halDocument
     *
     * @return HalResource
     */
    public static function deserializeResource($entityClass, HalDocument $halDocument): HalResource
    {
        if (is_string($entityClass)) {
            /** @var Hydratable $entity */
            $entity = new $entityClass();
        } else {
            $entity = $entityClass($halDocument->getData());
        }

        $entity->hydrate($halDocument->getData(), $halDocument->getEmbeddedEntities());

        return new HalResource($entity, $halDocument->getLinks());
    }

    /**
     * @param \Closure|string $entityClass
     * @param string          $rel
     * @param HalDocument     $halDocument
     *
     * @return HalResources
     */
    public static function deserializeResources($entityClass, string $rel, HalDocument $halDocument): HalResources
    {
        if ($halDocument->hasEmbedded($rel)) {
            $resources = array_map(function (HalDocument $embeddedDocument) use ($entityClass) {
                return self::deserializeResource($entityClass, $embeddedDocument);
            }, $halDocument->getEmbedded($rel));
        } else {
            $resources = [];
        }

        $data = $halDocument->getData();
        if (array_key_exists('page', $data)) {
            return new HalPagedResources($resources, $halDocument->getLinks(), new HalPageMetadata(
                $data['page']['number'],
                $data['page']['size'],
                $data['page']['totalElements'],
                $data['page']['totalPages']
            ));
        }

        return new HalResources($resources, $halDocument->getLinks());
    }

    /**
     * @param HalDocument $halDocument
     *
     * @return VndError
     */
    public static function deserializeError(HalDocument $halDocument): VndError
    {
        $data = $halDocument->getData();
        $error = new VndError($data['message'], $data['logRef'] ?? null, $data['path'] ?? null);

        if ($halDocument->hasEmbedded('errors')) {
            $error->setErrors(array_map(function (HalDocument $halDocument) {
                return self::deserializeError($halDocument);
            }, $halDocument->getEmbedded('errors')));
        }

        return $error;
    }

    /**
     * @param array $data
     *
     * @return HalDocument
     */
    private static function createDocument(array $data): HalDocument
    {
        $links = new HalLinks();
        if (array_key_exists(self::LINKS, $data)) {
            foreach ($data[self::LINKS] as $rel => $linkData) {
                $links->add(new HalLink($rel, $linkData['href'], (bool) ($linkData['templated'] ?? false)));
            }

            unset($data[self::LINKS]);
        }

        $resources = [];
        if (array_key_exists(self::EMBEDDED, $data)) {
            foreach ($data[self::EMBEDDED] as $rel => $resourceData) {
                if (isset($resourceData[0]) && is_array($resourceData[0])) {
                    $embeddedResource = array_map(function (array $data) {
                        return self::createDocument($data);
                    }, $resourceData);
                } else {
                    $embeddedResource = self::createDocument($resourceData);
                }

                $resources[$rel] = $embeddedResource;
            }

            unset($data[self::EMBEDDED]);
        }

        return new HalDocument($data, $links, $resources);
    }
}
