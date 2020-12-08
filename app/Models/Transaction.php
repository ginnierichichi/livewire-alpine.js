<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const STATUSES = [
        'success' => 'Success',
        'failed' => 'Failed',
        'processing' => 'Processing',
    ];

    protected $guarded = [];
    protected $casts = ['date' => 'date'];
    protected $appends = ['date_for_editing'];


    public function getStatusColorAttribute()
    {
        return [
                'processing' => 'yellow',
                'success' => 'green',
                'failed' => 'red',
            ][strtolower($this->status)] ?? 'cool-gray';
    }

    public function getDateForHumansAttribute()
    {
        return $this->date->format('d, M Y');
    }

    public function getDateForEditingAttribute()
    {
        return $this->date->format('d-m-Y');
    }

    public function setDateForEditingAttribute($value)
    {
        $this->date = Carbon::parse($value);
    }
}
