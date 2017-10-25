<?php

namespace Patreon\JSONAPI;

use \Art4\JsonApiClient\Document;
use \Art4\JsonApiClient\RelationshipCollectionInterface;
use \Art4\JsonApiClient\ResourceItemInterface;
use \Art4\JsonApiClient\AccessInterface;
use \Art4\JsonApiClient\Utils\FactoryManagerInterface;

class ResourceIdentifier implements \Art4\JsonApiClient\ResourceIdentifierInterface
{
    protected $resource_identifier;
    protected $manager;

    public function resolve(Document $document)
    {
        $all_data = $this->get_all_data_from_document($document);
        foreach ($all_data as $datum) {
            if (
                $datum->get('type') == $this->get('type')
                && $datum->get('id') == $this->get('id')
            ) {
                return $datum;
            }
        }
        throw new Exception("No resource found with type " . $this->get('type') . " and id " . $this->get('id'));
    }

    private function get_all_data_from_document(Document $document) {
        $all_data = [];
        if ($document->has('data')) {
            $data = $document->get('data');
            if ($data instanceof RelationshipCollectionInterface) {
                foreach ($data->getKeys() as $indexKey) {
                    array_push($all_data, $data->get($indexKey));
                }
            } else if ($data instanceof ResourceItemInterface) {
                array_push($all_data, $data);
            }
        }
        if ($document->has('included')) {
            $included = $document->get('included');
            foreach ($included->getKeys() as $indexKey) {
                array_push($all_data, $included->get($indexKey));
            }
        }
        return $all_data;
    }

    // Implement the rest of the interface as wrappers around the inner $resource_identifier

    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
	{
        $this->manager = $manager;
		$this->resource_identifier = new \Art4\JsonApiClient\ResourceIdentifier($manager, $parent);
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
        $this->resource_identifier->parse($object);

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
        return $this->resource_identifier->get($key);
	}

    /**
     * Check if a value exists in this object
     *
     * @param string $key The key of the value
     * @return bool true if data exists, false if not
     */
    public function has($key)
    {
        return $this->resource_identifier->has($key);
    }

    /**
     * Returns the keys of all setted values in this object
     *
     * @return array Keys of all setted values
     */
    public function getKeys()
    {
        return $this->resource_identifier->getKeys();
    }

    /**
     * Convert this object in an array
     *
     * @param bool $fullArray If true, objects are transformed into arrays recursively
     * @return array
     */
    public function asArray($fullArray = false)
    {
        return $this->resource_identifier->asArray($fullArray);
    }
}
