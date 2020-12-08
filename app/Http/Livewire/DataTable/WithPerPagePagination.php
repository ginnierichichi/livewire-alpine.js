<?php

namespace App\Http\Livewire\DataTable;

use Livewire\WithPagination;

trait WithPerPagePagination
{
    use WithPagination;
    public int $perPage = 10;

    public function initializeWithPagination()
    {
        $this->perPage = session()->get('perPage', $this->perPage);
    }

    public function updatePerPage($value)
    {
        session()->put('perPage', $value);
    }

    public function applyPagination($query)
    {
        return $query->paginate($this->perPage);
    }
}
