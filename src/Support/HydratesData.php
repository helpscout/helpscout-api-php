<?php

declare(strict_types=1);

namespace HelpScout\Api\Support;

use DateTime;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Hydratable;

trait HydratesData
{
    /**
     * Hydrates an individual instance of an entity.
     */
    private function hydrateOne(string $classPath, array $data): Hydratable
    {
        /** @var Hydratable $entity */
        $entity = new $classPath();
        $entity->hydrate($data);

        return $entity;
    }

    /**
     * Hydrates a collection of many entities.
     */
    private function hydrateMany(string $classPath, array $data): Collection
    {
        $collection = new Collection();
        foreach ($data as $entityData) {
            /** @var Hydratable $entity */
            $entity = new $classPath();
            $entity->hydrate($entityData);

            $collection->append($entity);
        }

        return $collection;
    }

    private function transformDateTime(?string $dateTime): ?DateTime
    {
        if ($dateTime === null) {
            return null;
        }

        return new DateTime($dateTime);
    }
}
