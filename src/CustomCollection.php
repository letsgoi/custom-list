<?php

namespace Letsgoi\CustomCollection;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Letsgoi\CustomCollection\Exceptions\CustomCollectionKeyNotExistException;
use Letsgoi\CustomCollection\Exceptions\CustomCollectionTypeErrorException;
use Traversable;

abstract class CustomCollection implements IteratorAggregate, ArrayAccess
{
    /** @var array */
    protected $items;

    abstract protected function getCollectionType(): string;

    public function __construct(array $items = [])
    {
        $this->items = $items;

        $this->checkItems();
    }

    /**
     * Iterator method
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Add item to collection as array
     *
     * @param mixed $key
     * @param mixed $item
     * @return void
     * @throws CustomCollectionTypeErrorException
     */
    public function offsetSet($key, $item): void
    {
        $this->checkItemType($item);

        if ($key === null) {
            $this->items[] = $item;
        } else {
            $this->items[$key] = $item;
        }
    }

    /**
     * Get item from collection with key
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * Checks if the collection as an item by key
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Remove item from collection by key
     *
     * @param mixed $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Get item by key or all items without it
     *
     * @param null $key
     * @return mixed
     * @throws CustomCollectionKeyNotExistException
     */
    public function get($key = null)
    {
        if ($key !== null) {
            if (!array_key_exists($key, $this->items)) {
                throw new CustomCollectionKeyNotExistException();
            }

            return $this->items[$key];
        }

        return $this->items;
    }

    /**
     * Append item to collection
     *
     * @param mixed $item
     * @return void
     * @throws CustomCollectionTypeErrorException
     */
    public function add($item): void
    {
        $this->checkItemType($item);

        $this->items[] = $item;
    }

    /**
     * Check if all items are of right type
     *
     * @return void
     * @throws CustomCollectionTypeErrorException
     */
    private function checkItems(): void
    {
        foreach ($this->items as $item) {
            $this->checkItemType($item);
        }
    }

    /**
     * Checks if the item passed is the right type
     *
     * @param mixed $item
     * @return void
     * @throws CustomCollectionTypeErrorException
     */
    private function checkItemType($item): void
    {
        $type = $this->getCollectionType();

        if ((is_object($item) && !$item instanceof $type) || (!is_object($item) && gettype($item) !== $type)) {
            throw new CustomCollectionTypeErrorException("All items must be of type '{$this->getCollectionType()}'.");
        }
    }
}
