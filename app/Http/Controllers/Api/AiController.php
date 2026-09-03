<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\AiChatRequest;
use App\Services\Ai\AiChatService;
use Illuminate\Http\JsonResponse;

class AiController extends Controller
{
    public function __construct(protected AiChatService $service) {}

    public function chat(
        AiChatRequest $request
    ): JsonResponse {
        return response()->json(
            $this->service->chat(
                message: $request->validated('message'),
                campusId: $request->validated('campus_id'),
            )
        );
    }
}
