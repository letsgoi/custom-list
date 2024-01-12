<?php

namespace Letsgoi\CustomList;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Letsgoi\CustomList\Exceptions\CustomListKeyNotExistException;
use Letsgoi\CustomList\Exceptions\CustomListTypeErrorException;
use Traversable;

/**
 * @template-covariant TValue
 */
abstract class CustomList implements IteratorAggregate, ArrayAccess, Countable
{
    /** @var array<TValue> */
    protected array $items;

    /** @return class-string<TValue> */
    abstract protected function getListType(): string;

    /**
     * @param iterable<TValue> $items
     * @throws CustomListTypeErrorException
     */
    public function __construct(iterable $items = [])
    {
        if ($items instanceof Traversable) {
            $this->items = iterator_to_array($items);
        } else {
            $this->items = (array)$items;
        }

        $this->checkItems();
    }

    /**
     * Iterator method
     *
     * @return Traversable<int, TValue>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Add item to list as array
     *
     * @param int $offset
     * @param TValue $value
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
     * @param int $offset
     * @return TValue
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * Checks if the list has an item by key
     *
     * @param int $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * Remove item from list by key
     *
     * @param int $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Merges the elements of one or more custom lists together
     *
     * @param CustomList<TValue> ...$lists
     * @return void
     * @throws CustomListTypeErrorException
     */
    public function merge(...$lists): void
    {
        foreach ($lists as $list) {
            $this->items = array_merge($this->items, $list->get());
        }

        $this->checkItems();
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
     * @param int|null $key
     * @return ($key is int ? TValue : self)
     * @throws CustomListKeyNotExistException
     */
    public function get(?int $key = null): mixed
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
     * @param TValue $item
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
     * @param TValue $item
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
