<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Http\Controllers\Api;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Thiktak\LaravelBootstrapComponentSelect2\Models\Search2Proxies\Select2Searchable;

Class ApiSelect2Controller extends Controller
{	
    protected $class;
	protected $data = [
		'results'  => [],
        'error' => [
            'code'    => 200,
            'message' => null,
        ],
	];

    public function search() {
        $this->class = null;

        $get_what = request()->get('what');
        $get_q    = request()->get('q');

        // Sanatize
        $get_what = str_replace('App\\Models\\', '', $get_what);

        $this->searchForModel($c1 = 'App\Models\\Search2Proxies\\' . $get_what . 'Proxy', $get_q);
        $this->searchForModel($c2 = 'App\Models\\' . $get_what, $get_q);

        if( empty($this->class) ) {
            $this->data['error'] = [
                'code' => 404,
                'message' => sprintf(
                    'No class found. Create `%s` or `%s` and use `%s` trait',
                    $c1, $c2, Select2Searchable::class
                )
            ];
        }
/*
        if( class_exists($_class) ) {
            if( method_exists($_class, 'select2') ) {
                $oClass = app($_class);
                $results = $oClass->select2($q);


                if( method_exists($_class, 'export_select2') ) {
                    $this->data['results'] = $results->get()->map(function($entity) use($oClass) {
                        return $oClass->export_select2($entity);
                    });
                }
                else {
                    $this->data['error'] = [
                        'code' => 404, 'message' => 'No `export_select2` method exists for the model'
                    ];
                }

            }
            else {
                $this->data['error'] = [
                    'code' => 404, 'message' => 'No `select2` method exists for the model'
                ];
            }
        }
        else {
            $this->data['error'] = [
                'code' => 404, 'message' => 'Model does not exists'
            ];
        }*/


        return $this->data;
    }

    public function formatData($data) {

    }

    public function searchForModel($class, $q) {

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
        $results = $this->class->select2($q);
        $this->data['results'] = $results->get()->map(function($entity) {
            return $this->class->select2_export($entity);
        });
    }
}