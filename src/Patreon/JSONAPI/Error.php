<?php

namespace Patreon\JSONAPI;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Serializer\ArraySerializer;

class Error implements Element, Accessable
{
    protected $error;

    public function __construct($data, Manager $manager, Accessable $parent)
	{
        // Coerce codes to strings
        $modified_object = clone $data;
        $modified_object->code = strval($data->code);

        $this->error = \Art4\JsonApiClient\V1\Error($modified_object, $manager, $parent);
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
        return (new ArraySerializer(['recursive' => $fullArray]))->serialize($this->error);
    }
}
