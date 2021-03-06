<?php

namespace App\Http\Livewire;


use Carbon\Carbon;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;
use App\Http\Livewire\DataTable\WithSorting;
use App\Http\Livewire\DataTable\WithCachedRows;
use App\Http\Livewire\DataTable\WithPerPagePagination;

class Dashboard extends Component
{
    use WithPerPagePagination, WithSorting, WithCachedRows;

    /**
     * @var mixed
     */
    public Transaction $editing;
    public bool $showDeleteModal = false;
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


    protected $queryString = [];

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

    public function makeBlankTransaction()
    {
        return  Transaction::make(['date' => now(), 'status' => 'success']);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();
        $this->showFilters = !$this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();
        if ($this->editing->getKey()) $this->editing = $this->makeBlankTransaction();
        $this->showEditModal = true;
    }

    public function edit(Transaction $transaction)
    {
        $this->useCachedRows();
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
           echo (clone $this->transactionsQuery)
               ->unless($this->selectAll, fn($query) => $query->whereKey($this->selected))
               ->toCsv();
        }, 'transactions.csv');
    }

    public function deleteSelected()
    {
//        $transactions = Transaction::whereKey($this->selected);
//        $transactions = $this->selectAll
//            ? $this->transactionsQuery
//            : $this->transactionsQuery->whereKey($this->selected);

        (clone $this->transactionsQuery)
            ->unless($this->selectAll, fn($query) => $query->whereKey($this->selected))
            ->delete();

        $this->showDeleteModal = false;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getTransactionsQueryProperty()
    {
        $query = Transaction::query()
            ->when($this->filters['status'], fn($query, $status) => $query->where('status', $status))
            ->when($this->filters['amount-min'], fn($query, $amount) => $query->where('amount','>=', $amount))
            ->when($this->filters['amount-max'], fn($query, $amount) => $query->where('amount','<=', $amount))
            ->when($this->filters['date-min'], fn($query, $date) => $query->where('date','>=', Carbon::parse($date)))
            ->when($this->filters['date-max'], fn($query, $date) => $query->where('date','<=', Carbon::parse($date)))
            ->when($this->filters['search'], fn($query, $search) => $query->where('title','like','%'. $search. '%'));

        return $this->applySorting($query);

    }

    public function getTransactionsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->transactionsQuery);
        });

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
