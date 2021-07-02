<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Http\Controllers\Api;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

Class ApiSelect2Controller extends Controller
{	
	protected $data = [
		'data' => []
	];

    public function search() {
        $q = request()->get('q');


        return $this->data;
    }

    public function formatData($data) {

    }
}