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
        $llm_api_key = env('GROQ_API_KEY');
        $llm_api_url = env('GROQ_API_URL');
        $llm_api_model = env('GROQ_MODEL');
        try {
            $resp = \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $llm_api_key",
            ])->post($llm_api_url, [
                'model' => $llm_api_model,
                'input' => $request->content,
            ]);
            $resp = $resp->object()->output;
            $output = "";
            foreach ($resp as $content) {
                foreach ($content->content as $reply) {
                    if ($reply->type == "output_text") {
                        $output .= $reply->text;
                    }
                }
            }
            return response()->json(['content' => $output]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
