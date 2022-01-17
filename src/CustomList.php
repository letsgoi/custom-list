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
    protected array $items;

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
     * @param mixed $offset
     * @param mixed $value
     * @return void
     * @throws CustomListTypeErrorException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->checkItemType($value);

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Get item from list with key
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * Checks if the list as an item by key
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Remove item from list by key
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
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
     * @param string|null $key
     * @return mixed
     * @throws CustomListKeyNotExistException
     */
    public function get(?string $key = null): mixed
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
    public function add(mixed $item): void
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
    private function checkItemType(mixed $item): void
    {
        $type = $this->getListType();

        if ((is_object($item) && !$item instanceof $type) || (!is_object($item) && gettype($item) !== $type)) {
            throw new CustomListTypeErrorException("All items must be of type '{$this->getListType()}'.");
        }
    }
}
