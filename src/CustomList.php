<?php

namespace Letsgoi\CustomList;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Letsgoi\CustomList\Exceptions\CustomListKeyNotExistException;
use Letsgoi\CustomList\Exceptions\CustomListTypeErrorException;
use Traversable;

abstract class CustomList implements IteratorAggregate, ArrayAccess, Countable
{
    /** @var array */
    protected $items;

    abstract protected function getListType(): string;

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
     * Add item to list as array
     *
     * @param mixed $key
     * @param mixed $item
     * @return void
     * @throws CustomListTypeErrorException
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
     * Get item from list with key
     *
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * Checks if the list as an item by key
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Remove item from list by key
     *
     * @param mixed $key
     * @return void
     */
    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

    /**
     * Countable method
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Get item by key or all items without it
     *
     * @param null $key
     * @return mixed
     * @throws CustomListKeyNotExistException
     */
    public function get($key = null)
    {
        if ($key !== null) {
            if (!array_key_exists($key, $this->items)) {
                throw new CustomListKeyNotExistException();
            }

            return $this->items[$key];
        }

        return $this->items;
    }

    /**
     * Append item to list
     *
     * @param mixed $item
     * @return void
     * @throws CustomListTypeErrorException
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
     * @throws CustomListTypeErrorException
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
     * @throws CustomListTypeErrorException
     */
    private function checkItemType($item): void
    {
        $type = $this->getListType();

        if ((is_object($item) && !$item instanceof $type) || (!is_object($item) && gettype($item) !== $type)) {
            throw new CustomListTypeErrorException("All items must be of type '{$this->getListType()}'.");
        }
    }
}
