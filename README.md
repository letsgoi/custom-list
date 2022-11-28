# PHP Custom List

Class to wrap array of items to force same type. This is for avoid the php array type hinting problem.

## Requirements

- PHP >= 7.2

## Usage

Extends the `CustomList` abstract class and set the type of the items with `getListType` method:

```php
use Letsgoi\CustomList\CustomList;

class ItemList extends CustomList
{
    protected function getListType(): string
    {
        return Item::class;  
    }
}

//

$items = [new Item(), new Item(), ...];
$list = new ItemList($items);
```

To iterate items:

```php
foreach ($list as $item) {
    //
}
```

You can use the list as an array (set, get, ...)

## Available methods

#### get($key = null)

Return item by key or all list without it: 

```php
$list->get(0); // 'item'

$list->get(); // ['item', 'item', ...]
```

#### add($item)

Append item to list: 

```php
$list->add($item);
```

#### merge(... $customLists)

Merges the elements of one or more custom lists together

```php
$list->merge($list1, $list2 ...);
```


## Testing

Run tests:

```bash
composer test
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](./LICENSE)
