<?php

namespace App\View\Components\Personnel;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public $rows;
    public $model;
    public $fieldMetadata;
    /**
     * Create a new component instance.
     */
    public function __construct($rows, $model, $fieldMetadata)
    {
        $this->rows = $rows;
        $this->model = $model;
        $this->fieldMetadata = $fieldMetadata;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.personnel.table');
    }
}
