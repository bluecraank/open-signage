<?php

namespace App\Livewire;

use App\Http\Controllers\PresentationController;
use Livewire\Component;

class PollPresentationProcess extends Component
{
    public function render()
    {
        $currentPresentations = PresentationController::getCurrentPresentationsInProgress();
        return view('livewire.poll-presentation-process', compact('currentPresentations'));
    }
}
