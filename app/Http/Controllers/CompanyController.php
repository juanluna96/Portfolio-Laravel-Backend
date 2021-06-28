<?php

namespace App\Http\Controllers;

use App\Company;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();

        return response()->json([
            'data' => $companies
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
        $languages = Language::all();
        $rules = [];

        foreach ($languages as $language) {
            $rules[$language->abbreviation] = 'required|string';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:companies|string',
            'position' => 'required|string|json',
            'image' => 'required|string'
        ]);

        $position = [
            json_decode($request->position)
        ];

        $validatorJson = Validator::make($position, $rules);

        if ($validator->fails() || $validatorJson->fails()) {
            return response()->json([
                'data' => [
                    'inputs' =>   $validator->errors(),
                    'languages' => $validatorJson->errors()
                ]
            ], 400);
        }

        $data = $request->all();
        $company = Company::create($data);
        return response()->json([
            'data' => $company
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response()->json([
            'data' => $company
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:companies|string',
            'position_es' => 'string',
            'position_en' => 'string',
            'image' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $company->update($data);
        return response()->json([
            'data' => $company
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json([
            'message' => 'Proyecto eliminado exitosamente'
        ]);
    }
}
