<?php

namespace App\View\Components\Personnel;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditModal extends Component
{
    public $model;
    public $selectOptions;
    public $fieldMetadata;
    /**
     * Create a new component instance.
     */
    public function __construct($model, $selectOptions = [], $fieldMetadata)
    {
        $this->model = $model;
        $this->selectOptions = $selectOptions;
        $this->fieldMetadata = $fieldMetadata;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.personnel.edit_modal');
    }
}
