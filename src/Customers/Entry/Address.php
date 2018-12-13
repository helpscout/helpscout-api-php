<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers\Entry;

use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;

class Address implements Extractable, Hydratable
{
    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string[]
     */
    private $lines = [];

    /**
     * @var string|null
     */
    private $state;

    /**
     * @var string|null
     */
    private $postalCode;

    /**
     * @var string|null
     */
    private $country;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setCity($data['city'] ?? null);
        $this->setLines($data['lines'] ?? []);
        $this->setState($data['state'] ?? null);
        $this->setPostalCode($data['postalCode'] ?? null);
        $this->setCountry($data['country'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return [
            'city' => $this->getCity(),
            'lines' => $this->getLines(),
            'state' => $this->getState(),
            'postalCode' => $this->getPostalCode(),
            'country' => $this->getCountry(),
        ];
    }

    /**
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * @param array $lines
     */
    public function setLines(array $lines)
    {
        $this->lines = $lines;
    }

    /**
     * @return string|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param string|null $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}
