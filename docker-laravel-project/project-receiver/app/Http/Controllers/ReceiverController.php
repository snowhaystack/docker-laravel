<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputRequest;
use App\Models\Payload;
use Illuminate\Http\JsonResponse;

class ReceiverController extends Controller
{
    public function analyze(InputRequest $request): JsonResponse
    {
        $payload = new Payload();
        $payload->payload_data = $request->getContent();
        $payload->save();
        return self::respond(['message' => 'valid-request-saved']);
    }
}
