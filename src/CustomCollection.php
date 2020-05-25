<?php

namespace Letsgoi\CustomCollection;

use ArrayIterator;
use Exception;
use IteratorAggregate;

abstract class CustomCollection implements IteratorAggregate
{
    /** @var array */
    protected $items;

    abstract protected function getCollectionType(): string;

    public function __construct(array $items = [])
    {
        $this->items = $items;

        $this->checkItems();
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    private function checkItems(): void
    {
        foreach ($this->items as $item) {
            if (!$this->checkItemType($item)) {
                throw new Exception("All items must be of type '{$this->getCollectionType()}'.");
            }
        }
    }

    /**
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
