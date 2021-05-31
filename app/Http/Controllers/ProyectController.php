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
        $this->middleware('jwt', ['except' => ['index', 'all']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($locale)
    {
        $proyects = Proyect::all();

        return response()->json([
            'data' => $proyects
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
            $images = $proyect->images;
            $categories = $proyect->categories;
            $company = $proyect->company;
        }

        return response()->json([
            'data' => $proyects
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
            'title' => 'required|unique:proyects|max:255',
            'url' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

        $categories = [1, 2, 3, 4];

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $proyect = Proyect::create($data);
        $proyect->categories()->attach($categories);
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
            'data' => $proyect
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
            'title' => 'unique:proyects|max:255',
            'url' => 'string|max:255',
            'company_id' => 'numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

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
        // $proyect->categories()->sync([]);
        $proyect->delete();

        return response()->json([
            'message' => 'Proyecto eliminado exitosamente'
        ]);
    }
}
