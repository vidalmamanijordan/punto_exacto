<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Services\FaqService;

class FaqController extends Controller
{
    public function __construct(
        protected FaqService $service
    ) {}

    public function index()
    {
        return FaqResource::collection(
            $this->service->getAll()
        );
    }

    public function store(StoreFaqRequest $request)
    {
        $faq = $this->service->create(
            $request->validated()
        );
        return new FaqResource($faq);
    }

    public function show(Faq $faq)
    {
        $faq->load('category');
        return new FaqResource($faq);
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq = $this->service->update(
            $faq,
            $request->validated()
        );
        return new FaqResource($faq);
    }

    public function destroy(Faq $faq)
    {
        $this->service->delete($faq);
        return response()->json([
            'message' => 'FAQ eliminada correctamente'
        ]);
    }
}
