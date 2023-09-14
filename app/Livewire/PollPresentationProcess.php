<?php

namespace App\Livewire;

use App\Http\Controllers\PresentationController;
use Livewire\Component;

class PollPresentationProcess extends Component
{
    public function render()
    {
        $currentPresentation = PresentationController::getCurrentPresentationInProgress();
        return view('livewire.poll-presentation-process', compact('currentPresentation'));
    }
}
