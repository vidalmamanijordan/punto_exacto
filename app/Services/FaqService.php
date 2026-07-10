<?php

namespace App\Services;

use App\Models\Faq;

class FaqService
{
    public function getAll()
    {
        return Faq::with([
            'category'
        ])->latest()->get();
    }

    public function create(array $data)
    {
        return Faq::create($data);
    }

    public function update(Faq $faq, array $data)
    {
        $faq->update($data);
        return $faq->fresh();
    }

    public function delete(Faq $faq)
    {
        $faq->delete();
    }
}
