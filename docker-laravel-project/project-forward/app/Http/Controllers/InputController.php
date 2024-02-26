<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class InputController extends Controller
{

    public function validate(Request $request, array $rules, array $messages = [], array $attributes = []): JsonResponse
    {
        $data = new \stdClass();
        $data->log_uuid = Uuid::uuid4()->toString();
        $data->ip = request()->ip();
        $data->agent = request()->userAgent();
        $cases = ['valid','invalid'];
        foreach ($cases as $k => $val){
            if($val === 'invalid') {
                $data->log_uuid = Carbon::now()->toDateTimeString();
            }
            $respond[$k] = $this->manageRequest($data);
        }
        return self::respond(['message' => $respond]);
    }
    public function manageRequest(object $data): object
    {
        $client = new Client();
        $return = new \stdClass();
        $url = 'http://project-receiver/public/api/analyze';
        $body = [   'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ],
            'json' => $data
        ];
        Log::error(print_r($body,1));
        try{
            $response = $client->request('POST', $url, $body);
            $return->message = json_decode($response->getBody()->getContents(),1);
            $return->status = $response->getStatusCode();
            return $return;
        }catch (ClientException $e){
            $response = $e->getResponse();
            $return->message = json_decode($response->getBody()->getContents(),1);
            $return->status = $response->getStatusCode();
            return $return;
        }
    }
}
