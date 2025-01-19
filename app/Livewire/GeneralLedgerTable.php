<?php

namespace App\Livewire;

use App\Models\GeneralLedgeMain;
use Livewire\Component;
use Livewire\WithPagination;

class GeneralLedgerTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    public function render()
    {
        $ledgers = GeneralLedgeMain::with(['user', 'mainAccount'])
        ->when($this->search, function ($query) {
            $query->where('Main_id', 'like', '%' . $this->search . '%')
                  ->orWhere('accounting_id', 'like', '%' . $this->search . '%')
                  ->orWhere('User_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('mainAccount', function ($q) {
                      $q->where('account_name', 'like', '%' . $this->search . '%');
                  });
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(10);
        // dd($ledgers); // تحقق مما يتم إرجاعه هنا
    
        return view('livewire.general-ledger-table', [
            'ledgers' => $ledgers,
        ]);
    }
    public function updatedSearch()
{
}
    // public function render()
    // {
    //     return view('livewire.general-ledger-table');
    // }
}
