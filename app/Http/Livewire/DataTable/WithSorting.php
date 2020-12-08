<?php

 namespace App\Http\Livewire\DataTable;

 trait WithSorting
 {
     /**
      * @var mixed
      */
     public $sorts =[];
//     public $sortField = 'title';
//     public $sortDirection = 'desc';

     public function sortBy($field)
     {
         if (! isset($this->sorts[$field])) {
             $this->sorts[$field] = 'asc';
             return;
         }

         if($this->sorts[$field] === 'asc') {
             $this->sorts[$field] = 'desc';
             return;
         }

         unset($this->sorts[$field]);
     }

//     public function sortBy($field)
//     {
//         if($this->sortField === $field) {
//             $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
//         } else {
//             $this->sortDirection = 'asc';
//         }
//         $this->sortField = $field;
//     }

     public function applySorting($query)
     {
         foreach($this->sorts as $field => $direction) {
             $query->orderBy($field, $direction);
         }
         return $query;

//         return $query->orderBy($this->sortField, $this->sortDirection);
     }
 }
