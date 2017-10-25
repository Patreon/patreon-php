<?php

namespace Patreon\JSONAPI;

use \Art4\JsonApiClient\AccessInterface;
use \Art4\JsonApiClient\Utils\FactoryManagerInterface;

class Error implements \Art4\JsonApiClient\ErrorInterface
{
    protected $error;

    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
	{
		$this->error = new \Art4\JsonApiClient\Error($manager, $parent);
	}

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function parse($object)
	{
        // Coerce codes to strings
        $modified_object = clone $object;
        $modified_object->code = strval($object->code);

        $this->error->parse($modified_object);

        return $this;
	}

	/**
	 * Get a value by the key of this object
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
        return $this->error->get($key);
	}

    /**
     * Check if a value exists in this object
     *
     * @param string $key The key of the value
     * @return bool true if data exists, false if not
     */
    public function has($key)
    {
        return $this->error->has($key);
    }

    /**
     * Returns the keys of all setted values in this object
     *
     * @return array Keys of all setted values
     */
    public function getKeys()
    {
        return $this->error->getKeys();
    }

    /**
     * Convert this object in an array
     *
     * @param bool $fullArray If true, objects are transformed into arrays recursively
     * @return array
     */
    public function asArray($fullArray = false)
    {
        return $this->error->asArray($fullArray);
    }
}
