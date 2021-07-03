<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Http\Controllers\Api;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Thiktak\LaravelBootstrapComponentSelect2\Models\Search2Proxies\Select2Searchable;

Class ApiSelect2Controller extends Controller
{	
    protected $class;
    protected $q;
    protected $id;
	protected $data = [
		'results'  => [],
        'meta'  => [
            'used' => null,
        ],
        'error' => [
            'code'    => 200,
            'message' => null,
        ],
	];

    public function search() {
        $this->class = null;

        $get_what = request()->get('what');
        $this->q  = request()->get('q');
        $this->id = request()->get('id');

        // Sanatize
        $get_what = str_replace('App\\Models\\', '', $get_what);

        $this->searchForModel($c1 = 'App\Models\\Search2Proxies\\' . $get_what . 'Proxy');
        $this->searchForModel($c2 = 'App\Models\\' . $get_what);

        if( empty($this->class) ) {
            $this->data['error'] = [
                'code' => 404,
                'message' => sprintf(
                    'No class found. Create `%s` or `%s` and use `%s` trait',
                    $c1, $c2, Select2Searchable::class
                )
            ];
        }

        return $this->data;
    }

    public function formatData($data) {

    }

    public function searchForModel($class) {

        // Execute only if nothing found
        if( !empty($this->class) ) {
            return;
        }


        if( !class_exists($class) ) {
            return;
        }

        if( !method_exists($class, 'select2') ) {
            $this->data['error'] = [
                'code' => 404,
                'message' => 'No `select2` method exists for the model'
            ];
            return;
        }

        if( !method_exists($class, 'select2_export') ) {
            $this->data['error'] = [
                'code' => 404,
                'message' => 'No `select2_export` method exists for the model'
            ];
            return;
        }


        $this->class = app($class);
        $results = $this->class->select2($this->id, $this->q);

        $this->data['results'] = $results->get()->map(function($entity) {
            return $this->class->select2_export($entity);
        });

        $this->data['meta']['used'] = get_class($this->class);
        $this->data['meta']['like'] = $this->class->return_select2_searchFields();
    }
}