<?php

namespace App\Http\Controllers;

use App\Biography;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BiographyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $biographies = Biography::all();
        return response()->json([
            'data' => $biographies,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description_en' => 'required|string',
            'description_es' => 'required|string',
            'stacks_description_en' => 'required|string',
            'stacks_description_es' => 'required|string',
            'phone_1' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone_2' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email_1' => 'required|email',
            'email_2' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $biography = Biography::create($data);
        return response()->json([
            'data' => $biography
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Biography  $biography
     * @return \Illuminate\Http\Response
     */
    public function show(Biography $biography)
    {
        return response()->json([
            'biography' => $biography
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Biography  $biography
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Biography $biography)
    {
        $validator = Validator::make($request->all(), [
            'description_en' => 'string',
            'description_es' => 'string',
            'stacks_description_en' => 'string',
            'stacks_description_es' => 'string',
            'phone_1' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone_2' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email_1' => 'email',
            'email_2' => 'email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $biography->update($data);
        return response()->json([
            'data' => $biography
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Biography  $biography
     * @return \Illuminate\Http\Response
     */
    public function destroy(Biography $biography)
    {
        $biography->delete();

        return response()->json([
            'message' => 'Proyecto eliminado exitosamente'
        ]);
    }
}
