<?php

namespace App\Livewire;

use App\LoraInterface;
use Livewire\Component;

class LoraLivewire extends Component
{
    public function mount()
    {
        $this->fetchLora();
    }

    public function fetchLora() {}

    public function render()
    {
        return <<<'HTML'
        <div>
            {{-- Nothing in the world is as soft and yielding as water. --}}
        </div>
        HTML;
    }
}
