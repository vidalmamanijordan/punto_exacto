<?php

namespace App\Services;

use App\Models\SearchHistory;

class SearchHistoryService
{
    public function getAll()
    {
        return SearchHistory::with([
            'user',
            'place',
        ])->latest()->get();
    }

    public function create(array $data)
    {
        return SearchHistory::create($data);
    }

    public function update(
        SearchHistory $searchHistory,
        array $data
    ) {
        $searchHistory->update($data);
        return $searchHistory->fresh([
            'user',
            'place'
        ]);
    }

    public function delete(SearchHistory $searchHistory)
    {
        $searchHistory->delete();
    }
}
