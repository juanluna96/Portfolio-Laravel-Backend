<?php

namespace App\Http\Controllers;

use App\Proyect;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProyectController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['index', 'show', 'languages', 'store', 'update', 'saveDescriptionLanguage', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proyects = Proyect::all();

        foreach ($proyects as $proyect) {
            $proyect->languages;
            $proyect->company;
            $proyect->categories;
        }

        return response()->json([
            'proyects' => $proyects
        ], 200);
    }

    /**
     * Display a listing of the resource by language.
     *
     * @return \Illuminate\Http\Response
     */
    public function languages($locale)
    {
        $language = Language::where('abbreviation', $locale)->firstOrFail();
        $proyects = $language->proyects;

        foreach ($proyects as $proyect) {
            $location = $proyect->languages->firstWhere('language_id', $language->id);
            $images = $proyect->images;
            $categories = $proyect->categories;
            $company = $proyect->company;
        }

        return response()->json([
            'proyects' => $proyects
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
            'title' => 'required|string|unique:proyects|max:255',
            'url' => 'required|string|max:255',
            'categories' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        // $data['user_id'] = Auth::user()->id;
        $data['user_id'] = 1;
        $proyect = Proyect::create($data);
        $proyect->categories()->attach($request->categories);
        return response()->json([
            'data' => $proyect
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proyect  $proyect
     * @return \Illuminate\Http\Response
     */
    public function show(Proyect $proyect)
    {
        $languages = $proyect->languages;
        $images = $proyect->images;
        $categories = $proyect->categories;
        $company = $proyect->company;

        return response()->json([
            'proyect' => $proyect
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proyect  $proyect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proyect $proyect)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'categories' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $proyect->categories()->sync([]);
        $proyect->categories()->attach($request->categories);

        $data = $request->all();
        $proyect->update($data);
        return response()->json([
            'data' => $proyect
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proyect  $proyect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proyect $proyect)
    {
        $proyect->categories()->sync([]);
        $proyect->languages()->sync([]);
        $proyect->delete();

        return response()->json([
            'proyect' => $proyect
        ]);
    }

    /**
     * Store a description in language and proyed created resource in storage.
     *
     * @param  \App\Proyect  $proyect
     * @return \Illuminate\Http\Response
     */
    public function saveDescriptionLanguage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'proyect_id' => 'required|numeric',
            'language_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $proyect = Proyect::findOrFail($request->proyect_id);
        $hasLanguage = $proyect->languages->contains($request->language_id);

        if ($hasLanguage) {
            $proyect->languages()->updateExistingPivot($request->language_id, $request->all(), false);
            return response()->json([
                'action' => 'editado',
            ]);
        }

        $proyect->languages()->attach($request->language_id, $request->all());
        return response()->json([
            'action' => 'añadido',
        ]);
    }
}
