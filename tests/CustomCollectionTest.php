<?php

namespace Letsgoi\CustomCollection\Tests;

use Exception;
use Letsgoi\CustomCollection\CustomCollection;
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
    public function it_should_convert_to_array()
    {
        $items = ['item', 'anotherItem'];

        $stringCollection = new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'string';
            }
        };

        $this->assertSame($items, $stringCollection->toArray());
    }

    /** @test */
    public function it_should_throw_an_exception_if_not_all_items_are_of_collection_type()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('All items must be of type \'stdClass\'');

        $items = [new stdClass(), new stdClass(), 'notStdClass'];

        new class ($items) extends CustomCollection {
            protected function getCollectionType(): string
            {
                return 'stdClass';
            }
        };
    }
}
