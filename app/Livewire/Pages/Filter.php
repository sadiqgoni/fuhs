<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Filter extends Component
{
    public $selectedOrganization = null;

    public function onOrganizationChange()
    {
        $this->dispatch('organization-selected', $this->selectedOrganization);
    }
    public function render()
    {
        return view('livewire.pages.filter');
    }
}
