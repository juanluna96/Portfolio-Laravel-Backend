<?php

namespace App\Http\Controllers;

use App\Company;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
            'companies' => $companies
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
        $data = json_decode($request->position, true);

        foreach ($languages as $language) {
            $rules[$language->abbreviation] = 'required|string';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:companies|string',
            'position' => 'required|string|json',
            'image' => 'required|mimes:jpeg,jpg,png,gif'
        ]);

        $position = json_decode($request->position, true);
        $validatorJson = Validator::make($position, $rules);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);
        $errors = validateInputsJson('languages', $validatorJson, $errors);

        if ($validator->fails() || $validatorJson->fails()) {
            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $data = $request->all();
        $newCompany = saveNewImage($request['image'], 'image', $data, 'companies', 506, 486);
        $company = Company::create($newCompany);
        return response()->json([
            'company' => $company
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
            'company' => $company
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $languages = Language::all();
        $rules = [];
        $data = json_decode($request->position, true);

        foreach ($languages as $language) {
            $rules[$language->abbreviation] = 'required|string';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'position' => 'required|string|json',
            'image' => 'required'
        ]);

        $position = json_decode($request->position, true);
        $validatorJson = Validator::make($position, $rules);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);
        $errors = validateInputsJson('languages', $validatorJson, $errors);

        if ($validator->fails() || $validatorJson->fails()) {
            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $data = $request->all();
        $updatedData = replaceNewImage($company->image, $request['image'], 'image', $data, 'companies',  506, 486);
        $company->update($updatedData);
        return response()->json([
            'company' => $company
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        deleteImage($company->image);

        $company->delete();

        return response()->json([
            'company' => $company
        ]);
    }
}
