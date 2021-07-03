<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Models\Search2Proxies;
use Illuminate\Support\Facades\Schema;

Class Select2Proxy {

	use Select2Searchable;

	protected $search2Fields = ['id', 'name', 'title'];

	public function getModel() {
		throw new \Exception('Define getModel method on `' . get_class($this) . '` with you own model');
	}

}