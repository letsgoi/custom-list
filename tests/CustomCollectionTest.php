<?php

namespace Letsgoi\CustomCollection\Tests;

use Exception;
use Letsgoi\CustomCollection\CustomCollection;
use Letsgoi\CustomCollection\Exceptions\CustomCollectionKeyNotExistException;
use Letsgoi\CustomCollection\Exceptions\CustomCollectionTypeErrorException;
use stdClass;

class CustomCollectionTest extends TestCase
{
    /** @test */
    public function it_should_iterate_all_his_items()
    {
        $items = ['item', 'anotherItem'];

        $stringCollection = new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        foreach ($stringCollection as $key => $string) {
            $this->assertSame($items[$key], $string);
        }
    }

    /** @test */
    public function it_should_get_all_items_of_collection()
    {
        $items = ['item', 'anotherItem'];

        $stringCollection = new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $this->assertSame($items, $stringCollection->get());
    }

    /** @test */
    public function it_should_get_items_from_collection_by_key()
    {
        $items = ['item', 'anotherItem'];

        $stringCollection = new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $this->assertSame('item', $stringCollection->get(0));
        $this->assertSame('anotherItem', $stringCollection->get(1));
    }

    /** @test */
    public function it_should_throw_exception_on_get_unknown_key_on_collection()
    {
        $this->expectException(CustomCollectionKeyNotExistException::class);

        $stringCollection = new class () extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $stringCollection->get(0);
    }

    /** @test */
    public function it_should_throw_an_exception_if_not_all_items_are_of_collection_type()
    {
        $this->expectException(CustomCollectionTypeErrorException::class);
        $this->expectExceptionMessage('All items must be of type \'stdClass\'');

        $items = [new stdClass(), new stdClass(), 'notStdClass'];

        new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'stdClass';
            }
        };
    }

    /** @test */
    public function it_should_access_to_key_of_collection()
    {
        $items = ['item', 'anotherItem'];

        $stringCollection = new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $this->assertSame('item', $stringCollection[0]);
        $this->assertSame('anotherItem', $stringCollection[1]);
    }

    /** @test */
    public function it_should_add_items_as_array()
    {
        $stringCollection = new class () extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $stringCollection[] = 'item';

        $this->assertSame('item', $stringCollection[0]);
    }

    /** @test */
    public function it_should_append_items()
    {
        $items = ['item'];

        $stringCollection = new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $stringCollection->add('anotherItem');

        $this->assertSame('anotherItem', $stringCollection[1]);
    }

    /** @test */
    public function it_should_count_items()
    {
        $items = ['item', 'anotherItem'];

        $stringCollection = new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $this->assertSame(2, count($stringCollection));
    }
}
