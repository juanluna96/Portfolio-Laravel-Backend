<?php

namespace App\Http\Controllers;

use App\Area;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::all();

        return response()->json([
            'areas' => $areas
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
            'title' => 'required|unique:areas|string',
            'name' => 'required|string|json',
            'logo' => 'required|string'
        ]);

        $name = json_decode($request->name, true);
        $validatorJson = Validator::make($name, $rules);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);
        $errors = validateInputsJson('languages', $validatorJson, $errors);

        if ($validator->fails() || $validatorJson->fails()) {
            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $data = $request->all();
        $area = Area::create($data);
        return response()->json([
            'area' => $area
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        return response()->json([
            'area' => $area
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
        $area = Area::findOrFail($id);
        $languages = Language::all();
        $rules = [];

        foreach ($languages as $language) {
            $rules[$language->abbreviation] = 'required|string';
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:areas|string',
            'name' => 'required|string|json',
            'logo' => 'required|string'
        ]);

        $name = json_decode($request->name, true);
        $validatorJson = Validator::make($name, $rules);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);
        $errors = validateInputsJson('languages', $validatorJson, $errors);

        if ($validator->fails() || $validatorJson->fails()) {
            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $data = $request->all();
        $area->update($data);
        return response()->json([
            'area' => $area
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        $area->delete();

        return response()->json([
            'area' => $area,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function areasWithCategories()
    {
        $areas = Area::with('categories')->has('categories')->get();

        foreach ($areas as $area) {
            $categories = $area->categories()->with('proyects')->with('languages')->has('proyects');
        }

        return response()->json([
            'areas' => $areas
        ]);
    }
}
