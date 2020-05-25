# PHP Custom Collection

Class to wrap array of items to force same type. This is for avoid the php array type hinting problem.

## Requirements

- PHP >= 7.2

## Usage

Extends the `CustomCollection` abstract class and set the type of the items with `getCollectionType` method:

```php
use Letsgoi\CustomCollection\CustomCollection;

class ItemCollection extends CustomCollection
{
    protected function getCollectionType(): string
    {
        return Item::class;  
    }
}

//

$items = [new Item(), new Item(), ...];
$collection = new ItemCollection($items);
```

To iterate items:

```php
foreach ($collection as $item) {
    //
}
```

You can use the collection as an array (set, get, ...)

## Available methods

#### toArray()

Return collection as an array: 

```php
$collection->toArray(); // ['item', 'item', ...]
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
