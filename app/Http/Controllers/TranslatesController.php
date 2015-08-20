<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Translation;

class TranslatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $returned = $this->translate($request->word, $request->source, $request->target);

        return response()->json($returned, 200, ["Content-Type" => "application/json; charset=UTF-8"], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function translate($word, $sourceLang, $targetLang)
    {
        $translate = Translation::where($sourceLang, strtolower($word))->first();

        if ($translate === NULL) {
            return ['data' => [
                'error' => 'There is no results for request'
            ]];
        }

        $result_word = $translate->{$targetLang};
        if ($result_word !== NULL) {
            if ($this->startsWithUpper($word)) {
                $result_word = mb_convert_case($result_word, MB_CASE_TITLE, "UTF-8");
            }
            if ($this->fullUpperString($word)) {
                $result_word = mb_convert_case($result_word, MB_CASE_UPPER, "UTF-8");
            }
        }

        return ['data' => [
            'word' => $word,
            'source' => $sourceLang,
            'target' => $targetLang,
            'result' => $result_word
        ]];
    }

    private function startsWithUpper($str)
    {
        $chr = mb_substr($str, 0, 1, "UTF-8");
        return mb_strtolower($chr, "UTF-8") !== $chr;
    }

    private function fullUpperString($str)
    {
        return mb_strtoupper($str, "UTF-8") === $str;
    }
}
