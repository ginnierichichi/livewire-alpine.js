<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithBulkActions;
use App\Http\Livewire\DataTable\WithPerPagePagination;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
//        sleep(1);                     //delays search loading

        return view('livewire.dashboard', [
            'transactions' => Transaction::search('title', $this->search)->paginate(10),
        ]);
    }
}
