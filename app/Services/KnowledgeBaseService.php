<?php

namespace App\services;

use App\Models\KnowledgeBase;
use Illuminate\Database\Eloquent\Collection;

class KnowledgeBaseService
{
    public function getAll(): Collection
    {
        return KnowledgeBase::query()
            ->latest()
            ->get();
    }

    public function getActive(): Collection
    {
        return KnowledgeBase::query()
            ->where('is_active', true)
            ->latest()
            ->get();
    }

    public function find(int $id): KnowledgeBase
    {
        return KnowledgeBase::query()
            ->findOrFail($id);
    }

    public function create(array $data): KnowledgeBase
    {
        return KnowledgeBase::create($data);
    }

    public function update(
        KnowledgeBase $knowledgeBase,
        array $data
    ): KnowledgeBase {
        $knowledgeBase->update($data);
        return $knowledgeBase->refresh();
    }

    public function delete(KnowledgeBase $knowledgeBase): bool
    {
        return $knowledgeBase->delete();
    }

    public function activate(KnowledgeBase $knowledgeBase): KnowledgeBase
    {
        $knowledgeBase->update([
            'is_active' => true,
        ]);

        return $knowledgeBase->refresh();
    }

    public function deactivate(KnowledgeBase $knowledgeBase): KnowledgeBase
    {
        $knowledgeBase->update([
            'is_active' => false,
        ]);
        return $knowledgeBase->refresh();
    }
}
