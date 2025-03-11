<?php

namespace App\View\Components\Personnel;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Contracts\Pagination\Paginator;

class Paginate extends Component
{
    public $paginator;
    /**
     * Create a new component instance.
     */
    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.personnel.paginate');
    }
}
