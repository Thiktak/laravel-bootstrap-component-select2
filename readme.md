
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

### Use the component on your blades


```HTML
<x-bs::select2 :label="__('User')" :options="['1' => 'Admin']" what="User" wire:model.defer="user_id" />
```

This bs::select2 component use the bs:select of https://github.com/bastinald/laravel-bootstrap-components.
You can use all the options of this component.

Here, the important part is the `what="Model"`, with Model = App\Models\User or User


### Configure your models
```
class User extends Model
{
	// [...]

	/**
	 * Method Select2
	 * Will return a query object based on the keyword searched
	 */
    public function select2($q) {
        return self::query()
            ->where('name', 'like', '%' . $q . '%')
            ->orderBy('name');
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

[ ] Use Proxy for Model (App\Models\Search2Proxies\<ModelName>Proxy)