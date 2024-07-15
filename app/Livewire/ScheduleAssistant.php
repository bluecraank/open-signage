<?php

namespace App\Livewire;

use Livewire\Component;

class ScheduleAssistant extends Component
{

    public $name = '';
    public $startDate = '';
    public $endDate = '';

    public $days = [];

    Public $countPresentations = 0;
    Public $countSlides = 0;

    public $onePresentation = true;

    public $showDateFields = false;
    public $showDateOptions = false;

    public function mount()
    {

        $this->days[] = [
            'startDate' => now()->format('Y-m-d 07:30'),
            'endDate' => now()->addHour()->format('Y-m-d 16:30'),
        ];
    }

    public function render()
    {
        if($this->name != '') {
            $this->showDateFields = true;
        }

        if($this->startDate != '' && $this->endDate != '') {
            $this->showDateOptions = true;
        }

        return view('livewire.schedule-assistant');
    }

    public function addDay() {
        $this->days[] = [
            'startDate' => now()->format('Y-m-d 07:30'),
            'endDate' => now()->addHour()->format('Y-m-d 16:30'),
        ];
    }

    public function removeDay($key) {
        unset($this->days[$key]);

        // Reorder the array keys
        $this->days = array_values($this->days);
    }
}
