<?php

namespace Letsgoi\CustomCollection;

use ArrayAccess;
use ArrayIterator;
use Exception;
use IteratorAggregate;
use Traversable;

abstract class CustomCollection implements IteratorAggregate, ArrayAccess
{
    /** @var array */
    protected $items;

    abstract protected function getCollectionType(): string;

    public function __construct(array $items = [])
    {
        $this->checkItems($items);

        $this->items = $items;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Add item to collection as array
     *
     * @param mixed $key
     * @param mixed $item
     * @throws Exception
     */
    public function offsetSet($key, $item): void
    {
        $this->checkItems([$item]);

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
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Convert collection to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Check if all items are of right type
     *
     * @param array $items
     * @throws Exception
     */
    private function checkItems(array $items): void
    {
        foreach ($items as $item) {
            if (!$this->checkItemType($item)) {
                throw new Exception("All items must be of type '{$this->getCollectionType()}'.");
            }
        }
    }

    /**
     * Checks if the item passed is the right type
     *
     * @param mixed $item
     * @return bool
     */
    private function checkItemType($item): bool
    {
        $type = $this->getCollectionType();

        if (is_object($item)) {
            return $item instanceof $type;
        }

        return gettype($item) === $type;
    }
}
