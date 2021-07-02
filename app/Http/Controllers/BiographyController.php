<?php

namespace App\Http\Controllers;

use App\Biography;
use App\Language;
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
            'biographies' => $biographies,
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
            'description' => 'required|string|json',
            'stacks_description' => 'required|string|json',
            'about_me' => 'required|string|json',
            'phone_1' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone_2' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email_1' => 'required|email',
            'email_2' => 'required|email',
        ]);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);

        if (isJson($request->description)) {
            $description = json_decode($request->description, true);
            $validatorDESC = Validator::make($description, $rules);
            $errors = validateInputsJson('description', $validatorDESC, $errors);
        }

        if (isJson($request->stacks_description)) {
            $stacks_description = json_decode($request->stacks_description, true);
            $validatorSTACKS = Validator::make($stacks_description, $rules);
            $errors = validateInputsJson('stacks_description', $validatorSTACKS, $errors);
        }

        if (isJson($request->about_me)) {
            $about_me = json_decode($request->about_me, true);
            $validatorABOUTME = Validator::make($about_me, $rules);
            $errors = validateInputsJson('about_me', $validatorABOUTME, $errors);
        }

        if ($validator->fails() || $validatorDESC->fails() || $validatorSTACKS->fails() || $validatorABOUTME->fails()) {
            return response()->json([
                'errors' => $errors,
            ], 400);
        }

        $data = $request->all();
        // $data['user_id'] = Auth::user()->id;
        $data['user_id'] = 1;
        $biography = Biography::create($data);
        return response()->json([
            'biography' => $biography,
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
            'biography' => $biography,
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
        $biography = Biography::findOrFail($id);
        $languages = Language::all();
        $rules = [];

        foreach ($languages as $language) {
            $rules[$language->abbreviation] = 'required|string';
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required|string|json',
            'stacks_description' => 'required|string|json',
            'about_me' => 'required|string|json',
            'phone_1' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone_2' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email_1' => 'required|email',
            'email_2' => 'required|email',
        ]);

        $description = json_decode($request->description, true);
        $validatorDESC = Validator::make($description, $rules);

        $stacks_description = json_decode($request->stacks_description, true);
        $validatorSTACKS = Validator::make($stacks_description, $rules);

        $about_me = json_decode($request->about_me, true);
        $validatorABOUTME = Validator::make($about_me, $rules);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);
        $errors = validateInputsJson('description', $validatorDESC, $errors);
        $errors = validateInputsJson('stacks_description', $validatorSTACKS, $errors);
        $errors = validateInputsJson('about_me', $validatorABOUTME, $errors);

        if ($validator->fails() || $validatorDESC->fails() || $validatorSTACKS->fails() || $validatorABOUTME->fails()) {
            return response()->json([
                'errors' => $errors,
            ], 400);
        }

        $data = $request->all();
        $biography->update($data);
        return response()->json([
            'biography' => $biography,
        ], 200);
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
            'biography' => $biography,
        ]);
    }

    /**
     * Get the last biography registered.
     *
     * @param  \App\Biography  $biography
     * @return \Illuminate\Http\Response
     */
    public function lastest()
    {
        $biography = Biography::latest('id')->first();

        return response()->json([
            'biography' => $biography,
        ]);
    }
}
