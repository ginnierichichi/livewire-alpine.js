<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    /**
     * @var mixed
     */
    public $sortField = 'title';
    /**
     * @var mixed
     */
    public $editing;
    public $sortDirection = 'desc';
    public $showEditModal = false;
    public $showFilters = false;
    public $selectPage = false;
    public $selectAll = false;
    public $selected = [];
    public $filters = [
      'search' => '',
      'status' => '',
      'amount-min' => null,
      'amount-max' => null,
      'date-min' => null,
      'date-max' => null,
    ];


    protected $queryString = ['sortField', 'sortDirection'];

    public function rules()
    {
        return [
            'editing.title' => 'required|min:3',
            'editing.amount' => 'required',
            'editing.status' => 'required|in:'.collect(Transaction::STATUSES)->keys()->implode(','),
            'editing.date_for_editing' => 'required',
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankTransaction();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
        $this->selectPage = false;

    }

    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->selected = $this->transactions->pluck('id')->map(fn($id) => (string) $id);
        } else {
            $this->selected = [];
        }
    }

    public function selectAll()
    {
        $this->selectAll = true;
    }

    public function sortBy($field)
    {
        if($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function makeBlankTransaction()
    {
        return  Transaction::make(['date' => now(), 'status' => 'success']);
    }

    public function create()
    {
        if ($this->editing->getKey()) $this->editing = $this->makeBlankTransaction();
        $this->showEditModal = true;
    }

    public function edit(Transaction $transaction)
    {
        if($this->editing->isNot($transaction)) $this->editing = $transaction;
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->editing->save();
        $this->showEditModal = false;
    }

    public function exportSelected()
    {
        return response()->streamDownload(function () {
           echo Transaction::whereKey($this->selected)->toCsv();
        }, 'transactions.csv');
    }

    public function deleteSelected()
    {
//        $transactions = Transaction::whereKey($this->selected);

        $transactions = $this->selectAll
            ? $this->transactionsQuery
            : $this->transactionsQuery->whereKey($this->selected);

        $transactions->delete;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getTransactionsQueryProperty()
    {
        return Transaction::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['amount-min'], fn($query, $amount) => $query->where('amount','>=', $amount))
            ->when($this->filters['amount-max'], fn($query, $amount) => $query->where('amount','<=', $amount))
            ->when($this->filters['date-min'], fn($query, $date) => $query->where('date','>=', Carbon::parse($date)))
            ->when($this->filters['date-max'], fn($query, $date) => $query->where('date','<=', Carbon::parse($date)))
            ->when($this->filters['search'], fn($query, $search) => $query->where('title','like','%'. $search. '%'))
            ->orderBy($this->sortField, $this->sortDirection);

    }

    public function getTransactionsProperty()
    {
        return $this->transactionsQuery->paginate(10);
    }
    public function render()
    {
//        sleep(1);                     //delays search loading
        if ($this->selectAll) {
            $this->selected = $this->transactions->pluck('id')->map(fn($id) => (string) $id);
        }

        return view('livewire.dashboard', [
            'transactions' => $this->transactions,
        ]);
    }
}
