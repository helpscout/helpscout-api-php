<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests;

trait ReflectionTestTrait
{
    /**
     * @param mixed $object
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function invokeMethod($object, string $method, array $parameters = [])
    {
        $class = \get_class($object);

        $reflection = new \ReflectionClass($class);
        $reflectedMethod = $reflection->getMethod($method);
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod->invokeArgs($object, $parameters);
    }

    /**
     * @param mixed $object
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public function getAttribute($object, string $attributeName)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($attributeName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param mixed $object
     * @param mixed $value
     *
     * @throws \ReflectionException
     */
    public function setAttribute($object, string $attributeName, $value): void
    {
        $class = $this->getClassName($object);

        $reflection = new \ReflectionProperty($class, $attributeName);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    /**
     * @param mixed $object
     */
    protected function getClassName(&$object): string
    {
        if (!\is_object($object)) {
            return $object;
        }

        return \get_class($object);
    }

    /**
     * getFullMock.
     *
     * @return mixed
     */
    public function getFullMock(string $name)
    {
        return $this->getMockBuilder($name)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * getPartialMock.
     *
     * @return mixed
     */
    public function getPartialMock(string $name, array $methods = [])
    {
        return $this->getMockBuilder($name)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }
}
