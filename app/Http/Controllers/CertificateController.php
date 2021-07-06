<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['paginated']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $certificates = Certificate::all();

        return response()->json([
            'certificates' => $certificates
        ], 200);
    }

    /**
     * Display a listing of the resource in a paginate.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginated()
    {
        $certificates = Certificate::paginate(12);

        return response()->json([
            'certificates' => $certificates
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
            'name' => 'required|string',
            'website' => 'required|string',
            'hours' => 'required|numeric',
            'title' => 'required|string|json',
            'image' => 'required|mimes:jpeg,jpg,png,gif'
        ]);

        $title = json_decode($request->title, true);
        $validatorTITLE = Validator::make($title, $rules);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);
        $errors = validateInputsJson('title', $validatorTITLE, $errors);

        if ($validator->fails() || $validatorTITLE->fails()) {
            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $data = $request->all();
        $newCertificate = saveNewImage($request['image'], 'image', $data, 'certificates', 1280, 720);
        $certificate = Certificate::create($newCertificate);
        return response()->json([
            'certificate' => $certificate
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        return response()->json([
            'certificate' => $certificate
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        $languages = Language::all();
        $rules = [];

        foreach ($languages as $language) {
            $rules[$language->abbreviation] = 'required|string';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'website' => 'required|string',
            'hours' => 'required|numeric',
            'title' => 'required|string|json',
            'image' => 'required'
        ]);

        $title = json_decode($request->title, true);
        $validatorTITLE = Validator::make($title, $rules);

        $errors = [];
        $errors = validateInputsJson('inputs', $validator, $errors);
        $errors = validateInputsJson('title', $validatorTITLE, $errors);

        if ($validator->fails() || $validatorTITLE->fails()) {
            return response()->json([
                'errors' => $errors
            ], 400);
        }

        $data = $request->all();
        $updatedData = replaceNewImage($certificate->image, $request['image'], 'image', $data, 'certificates',  1280, 720);
        $certificate->update($updatedData);
        return response()->json([
            'certificate' => $certificate
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return response()->json([
            'certificate' => $certificate
        ]);
    }
}
