<?php

namespace Thiktak\LaravelBootstrapComponentSelect2\Models\Search2Proxies;
use Illuminate\Support\Facades\Schema;

Trait Select2Searchable {

	protected $defaultSearch2Fields = ['name', 'title'];

	public function select2($id, $term)
	{
		$model = $this->getModel();
		$query = $model->query();

		return $query
			
			// Search by ID
			->when($id, function($query) use($model, $id) {
				$query->find($id);
			})

			// Search by Wheres
			->when(!$id, function($query) use($model, $term) {
				foreach( array_filter(explode(' ', $term)) as $q_word ) {
					$query = $query->where(function($query) use($q_word, $model) {
						foreach( $this->return_select2_searchFields() as $field ) {
							// If field exists in ATTRIBUTES list
							if( Schema::hasColumn($model->getTable(), $field) ) {
								$query = $query
									->orWhere($field, 'like', '%' . $q_word . '%');
							}
						}
					});
				}
			});
	}


	public function select2_export($model)
	{
		return [
			'id'   => $model->getKey(),
			'text' => (string) $model,
		];
	}

	public function return_select2_searchFields() {
		if( isset($this->search2Fields) ) {
			return $this->search2Fields;
		}
		else if( isset($this->defaultSearch2Fields) ) {
			return $this->defaultSearch2Fields;
		}
	}
}