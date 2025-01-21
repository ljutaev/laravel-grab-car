<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\CarType;
use Illuminate\Support\Collection;

class SelectCarType extends Component
{
    public Collection $types;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->types = CarType::orderBy('name')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-car-type');
    }
}
