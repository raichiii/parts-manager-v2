<?php

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TextInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('other.textInfos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get()
    {
        setlocale(LC_ALL, 'C.UTF-8');
        $texts_path = env('TEXTS_DIR');
        $lists = $this->search_file_extension($texts_path);
        foreach ($lists as $list) {
            $content = file_get_contents($list);
            $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
            $response[] = [basename($list), $content];
        }
        return response()->json($response);
    }

    public function search()
    {

    }

    public function search_file_extension($path, $file_extension='txt')
    {
        $path = rtrim($path, '/');
        $lists = [];
        $file_extension_len = mb_strlen($file_extension);
        $a = glob($path.'/*');
        foreach ($a as $b) {
            if (mb_substr($b, -$file_extension_len, $file_extension_len) == $file_extension) {
                array_push($lists, $b);
            } else if (strpos($b, '.') === false) {
                $lists_sub = $this->search_file_extension($b, $file_extension=$file_extension);
                $lists = array_merge($lists, $lists_sub);
            }
        }
        return $lists;
    }
}
