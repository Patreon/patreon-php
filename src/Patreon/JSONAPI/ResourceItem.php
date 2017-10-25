<?php

namespace Patreon\JSONAPI;

use \Art4\JsonApiClient\AccessInterface;
use \Art4\JsonApiClient\Utils\FactoryManagerInterface;

class ResourceItem implements \Art4\JsonApiClient\ResourceItemInterface
{
    protected $resource_item;

    public function attribute($attribute_name) {
        return $this->resource_item->get('attributes.' . $attribute_name);
    }

    public function relationship($relationship_name) {
        return $this->resource_item->get('relationships.' . $relationship_name . '.data');
    }

    // Implement the rest of the interface as wrappers around the inner $resource_identifier

    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
	{
		$this->resource_item = new \Art4\JsonApiClient\ResourceItem($manager, $parent);
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
        $this->resource_item->parse($object);

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
        return $this->resource_item->get($key);
	}

    /**
     * Check if a value exists in this object
     *
     * @param string $key The key of the value
     * @return bool true if data exists, false if not
     */
    public function has($key)
    {
        return $this->resource_item->has($key);
    }

    /**
     * Returns the keys of all setted values in this object
     *
     * @return array Keys of all setted values
     */
    public function getKeys()
    {
        return $this->resource_item->getKeys();
    }

    /**
     * Convert this object in an array
     *
     * @param bool $fullArray If true, objects are transformed into arrays recursively
     * @return array
     */
    public function asArray($fullArray = false)
    {
        return $this->resource_item->asArray($fullArray);
    }
}
