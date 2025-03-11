<?php

namespace App\View\Components\Personnel;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Search extends Component
{
    public $searchFields;
    public $model;
    public $selectOptions;

    public function __construct($fieldMetadata, $model, $selectOptions = [])
    {
        $this->model = $model;
        $this->selectOptions = $selectOptions;
        $this->searchFields = array_filter($fieldMetadata, function ($field) {
            return isset($field['search']) && $field['search'];
        });


    }

    public function render()
    {
        return view('components.personnel.search', ['searchFields' => $this->searchFields]);
    }
}

