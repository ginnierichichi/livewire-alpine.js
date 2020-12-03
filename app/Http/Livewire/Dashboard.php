<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';
    /**
     * @var mixed
     */
    public $sortField = 'title';
    /**
     * @var mixed
     */
    public $sortDirection = 'desc';

    protected $queryString = ['sortField', 'sortDirection'];


    public function sortBy($field)
    {
        if($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
//        sleep(1);                     //delays search loading

        return view('livewire.dashboard', [
            'transactions' => Transaction::search('title', $this->search)->orderBy($this->sortField, $this->sortDirection)->paginate(10),
        ]);
    }
}
