<?php

namespace Patreon\JSONAPI;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Serializer\ArraySerializer;

class ResourceItem implements Element, Accessable
{
    protected $resource_item;

    public function attribute($attribute_name) {
        return $this->resource_item->get('attributes.' . $attribute_name);
    }

    public function relationship($relationship_name) {
        return $this->resource_item->get('relationships.' . $relationship_name . '.data');
    }

    // Implement the rest of the interface as wrappers around the inner $resource_identifier

    public function __construct($data, Manager $manager, Accessable $parent)
	{
		$this->resource_item = new \Art4\JsonApiClient\V1\ResourceItem($data, $manager, $parent);
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
        return (new ArraySerializer(['recursive' => $fullArray]))->serialize($this->resource_item);
    }
}
