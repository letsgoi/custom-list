<?php

namespace Letsgoi\CustomList\Tests;

use ArrayIterator;
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
    public function it_should_construct_with_traversable_object()
    {
        $items = new ArrayIterator(['item', 'anotherItem']);

        $list = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        foreach ($list as $key => $string) {
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

    /** @test */
    public function it_should_merge_custom_lists()
    {
        $items = ['item', 'anotherItem'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $otherItems = ['otherItem', 'anotherOtherItem'];

        $otherStringList = new class ($otherItems) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $otherItems2 = ['otherItem2', 'anotherOtherItem2'];

        $otherStringList2 = new class ($otherItems2) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $stringList->merge($otherStringList, $otherStringList2);

        $this->assertSame('item', $stringList[0]);
        $this->assertSame('anotherItem', $stringList[1]);
        $this->assertSame('otherItem', $stringList[2]);
        $this->assertSame('anotherOtherItem', $stringList[3]);
        $this->assertSame('otherItem2', $stringList[4]);
        $this->assertSame('anotherOtherItem2', $stringList[5]);
    }

    /** @test */
    public function it_should_return_error_while_merging_custom_lists_if_types_are_not_the_same()
    {
        $this->expectException(CustomListTypeErrorException::class);
        $this->expectExceptionMessage('All items must be of type \'string\'');

        $items = ['item', 'anotherItem'];

        $stringList = new class ($items) extends CustomList {
            protected function getListType(): string
            {
                return 'string';
            }
        };

        $otherItems = [1, 2];

        $intList = new class ($otherItems) extends CustomList {
            protected function getListType(): string
            {
                return 'integer';
            }
        };

        $stringList->merge($intList);
    }
}
