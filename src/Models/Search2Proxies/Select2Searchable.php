<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Models\Search2Proxies;
use Illuminate\Support\Facades\Schema;

Trait Select2Searchable {

	protected $search2Fields = ['name', 'title'];

	public function select2($q)
	{
		$query = self::query();
		
		foreach( array_filter(explode(' ', $q)) as $q_word ) {
			$query = $query->where(function($query) use($q_word) {
				foreach( $this->search2Fields as $field ) {
					// If field exists in ATTRIBUTES list
					if( Schema::hasColumn($this->getTable(), $field) ) {
						$query = $query
							->orWhere($field, 'like', '%' . $q_word . '%');
					}
				}
			});
		}

		return $query;
	}


	public function select2_export($model)
	{
		return [
			'id'   => $model->getKey(),
			'text' => (string) $model,
		];
	}
}