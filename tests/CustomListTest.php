<?php

namespace Letsgoi\CustomList\Tests;

use Letsgoi\CustomList\CustomList;
use Letsgoi\CustomList\Exceptions\CustomListKeyNotExistException;
use Letsgoi\CustomList\Exceptions\CustomListTypeErrorException;
use stdClass;

class CustomListTest extends TestCase
{
    /** @test */
    public function it_should_iterate_all_its_items()
    {
        $items = ['item', 'anotherItem'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        foreach ($stringList as $key => $string) {
            $this->assertSame($items[$key], $string);
        }
    }

    /** @test */
    public function it_should_get_all_items_of_list()
    {
        $items = ['item', 'anotherItem'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $this->assertSame($items, $stringList->get());
    }

    /** @test */
    public function it_should_get_items_from_list_by_key()
    {
        $items = ['item', 'anotherItem'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $this->assertSame('item', $stringList->get(0));
        $this->assertSame('anotherItem', $stringList->get(1));
    }

    /** @test */
    public function it_should_throw_exception_when_get_unknown_key_on_list()
    {
        $this->expectException(CustomListKeyNotExistException::class);

        $stringList = new class () extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $stringList->get(0);
    }

    /** @test */
    public function it_should_throw_an_exception_if_not_all_items_are_of_list_type()
    {
        $this->expectException(CustomListTypeErrorException::class);
        $this->expectExceptionMessage('All items must be of type \'stdClass\'');

        $items = [new stdClass(), new stdClass(), 'notStdClass'];

        new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'stdClass';
            }
        };
    }

    /** @test */
    public function it_should_access_to_key_of_list()
    {
        $items = ['item', 'anotherItem'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $this->assertSame('item', $stringList[0]);
        $this->assertSame('anotherItem', $stringList[1]);
    }

    /** @test */
    public function it_should_add_items_as_array()
    {
        $stringList = new class () extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $stringList[] = 'item';

        $this->assertSame('item', $stringList[0]);
    }

    /** @test */
    public function it_should_append_items()
    {
        $items = ['item'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $stringList->add('anotherItem');

        $this->assertSame('anotherItem', $stringList[1]);
    }

    /** @test */
    public function it_should_count_items()
    {
        $items = ['item', 'anotherItem'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $this->assertSame(2, count($stringList));
    }
}
