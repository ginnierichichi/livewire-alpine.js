<?php

namespace App\Http\Livewire\DataTable;


trait WithCachedRows
{
    protected $useCache = false;

    public function useCachedRows()
    {
        $this->useCache = true;
    }

    public function cache($callback)
    {
        //use the component id
        $cacheKey = $this->id;

        if($this->useCache && cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $result = $callback();

        cache()->put($cacheKey, $result);

        return $result;
    }
}
