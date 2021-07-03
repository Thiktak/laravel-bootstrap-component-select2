
Based on the components of https://github.com/bastinald/laravel-bootstrap-components


## Installation

### Run composer to install the package

```
composer require thiktak/laravel-bootstrap-component-select2
```

### Include css

Include on your **resources/scss/app.scss** the line:

```css
@import 'vendor/thiktak/laravel-bootstrap-component-select2/resources/scss/select2';
```

### Include Javascript

Include on your **resources/js/app.js** the line:

```javascript
require('../../vendor/thiktak/laravel-bootstrap-component-select2/resources/js/select2');

```

### Run NPM

```
npm run dev
```

### Use the component on your blades


```HTML
<x-bs::select2 :label="__('User')" :options="['1' => 'Admin']" what="User" wire:model.defer="user_id" />
```

This bs::select2 component use the bs:select of https://github.com/bastinald/laravel-bootstrap-components.
You can use all the options of this component.

Here, the important part is the `what="Model"`, with Model = App\Models\User or User


### Configure your models

 1. Implement the trait `Select2Searchable`
 2. Fill the protected variable $search2Fields with list of columns to be added in the where closure
 3. Implement the `__toString()` model method if you want to have a beautiful output

 
```php

use Thiktak\LaravelBootstrapComponentSelect2\Models\Search2Proxies\Select2Searchable;

class User extends Model
{
    use Select2Searchable;

    // Will use magic search
    protected $search2Fields = ['name', 'title'];

    // [...]

}
```

You can redefine the methods `search2` and `search2_export` if required.


You can also create a proxy and build you own query. The Select2Proxy implement automatically the `Select2Searchable` trait.
The proxy should be on `App\Models\Search2Proxies` folder.

```php
namespace App\Models\Search2Proxies;

use Thiktak\LaravelBootstrapComponentSelect2\Models\Search2Proxies\Select2Proxy;

class UserProxy extends Select2Proxy
{
    /*
     * protected $search2Fields = ['name', 'title'];
     */

    /**
     * METHOD getModel
     *----------------
     * Define your own model
     * Used by magic method select2, if not overwritten
     */
    public function getModel() {
        return new \App\Models\User;
    }

    /**
     * Method Select2
     *----------------
     * Will return a query object based on the keyword searched
     * ... or the ID provided
     */
    public function select2($id, $term) {
        return $this->getModel()
            ->query()
            // Search by ID
            ->when($id, function($q) use($id) {
                // no return
                $q->find($id);
                // OR $q->where($this->getModel()->getPrimaryKey(), $id)
            })
            ->when(!$id, function($q) use($term) {
                return $q
                    ->where('name', 'like', '%' . $term . '%')
                    ->orderBy('name');
            });
    }


    /**
     * Method Export_select2
     * Will export array data based on Select2 format id/text
     */
    public function export_select2(User $user) {
        // id   => '1'
        // text => 'Admin (user@example.net)'
        return [
            'id'   => $user->id,
            'text' => sprintf('%s (%s)', $user->name, $user->email),
        ];
    }

    // [...]

}
```

### Test the API
You can test the data exported to the select by opening directly `/api/select2/search?what=User&q=a`
If needed, you can use `{{ route('api.select2.search') }}`

Parameters
- `what` = the model to be used (i.e.: User or App\Models\User)
- `q` = search keyword

Output:
- results: [{id: 1, text: "label"}]
- error: { code: 200, message: null }

Use the error code to know what happens.

## TODO
 
 - [X] Use Proxy for Model (App\Models\Search2Proxies\<ModelName>Proxy)

## Changelogs

 - Added select2/autoload values (fetch data via API in order to keep label synchronization)
 - Added possibility to fetch by ID or by term
 - Added Proxy model