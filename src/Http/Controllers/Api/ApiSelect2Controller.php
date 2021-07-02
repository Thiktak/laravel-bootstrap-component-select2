<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Http\Controllers\Api;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

Class ApiSelect2Controller extends Controller
{	
	protected $data = [
		'results'  => [],
        'error' => [
            'code'    => 200,
            'message' => null,
        ],
	];

    public function search() {
        $what = request()->get('what');
        $q = request()->get('q');

        $_class = 'App\Models\\' . str_replace('App\\Models\\', '', $what);

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
        }


        return $this->data;
    }

    public function formatData($data) {

    }
}