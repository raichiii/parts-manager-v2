<?php

namespace App\Http\Controllers\Other;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ChatGPTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('other.chatgpt.index');
    }

    /**
     * ChatGPT用API
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function chat(Request $request)
    {
        try {
            $resp = env('APP_DEBUG')?
                $this->chatOnDebug($request) :
                $this->chatOnProduct($request);

            return response()->json($resp);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * ChatGPT用API
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function chatOnProduct(Request $request)
    {
        $apikey = env('CHATGPT_APIKEY');
        $api_url = env('CHATGPT_API_URL');
        $prefix = file_get_contents(env('CHATGPT_PROMPT_PREFIX_TXT'));
        $resp = \Http::withHeaders([
            'Content-Type' => 'application/json',
            // 'Accept' => 'application/json',
            'Authorization' => "Bearer $apikey",
        ])->post($api_url, [
            'model' => env('CHATGPT_MODEL'),
            'input' => "$prefix$request->content",
        ]);
        return $resp->json();
    }

    /**
     * ChatGPT用API（デバッグモード）
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function chatOnDebug(Request $request)
    {
        $apikey = env('CHATGPT_APIKEY_TEST');
        $api_url = env('CHATGPT_API_URL_TEST');
        $prefix = file_get_contents(env('CHATGPT_PROMPT_PREFIX_TXT'));
        $resp = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $apikey",
        ])->post($api_url, [
            'messages' => [
                [ 'role' => 'system', 'content' => $prefix ],
                [ 'role' => 'user', 'content' => $request->content ]
            ],
            'model' => env('CHATGPT_MODEL_TEST'),
            'temperature' => 1.0,
            'top_p' => 1.0,
            'max_tokens' => 1000,
        ],);
        return $resp->json();
    }
}
