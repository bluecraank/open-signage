<?php

namespace App\Livewire;

use Livewire\Component;

class ShowSlides extends Component
{
    public $presentation;

    public function render()
    {
        return view('livewire.show-slides', [
            'presentation' => $this->presentation,
        ]);
    }
}
