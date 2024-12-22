<?php

namespace App\Http\Controllers;

use App\Models\BankOfficer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function staffName(Request $request)
    {
        // Create a unique cache key based on the search input and selected items
        $cacheKey = 'staff_name_' . md5($request->search . implode(',', $request->input('selected', [])));

        // Try to retrieve the data from the cache
        $data = Cache::remember($cacheKey, now()->addDay(), function () use ($request) {
            return BankOfficer::query()
                ->when($request->search, fn (Builder $query) =>
                    $query->whereRaw('UPPER(officer_name) LIKE ?', ['%' . strtoupper($request->search) . '%'])
                )
                ->when($request->exists('selected'), fn (Builder $query) =>
                    $query->whereIn('officer_id', $request->input('selected', []))
                )
                ->get();
        });

        return $data;
    }
}
