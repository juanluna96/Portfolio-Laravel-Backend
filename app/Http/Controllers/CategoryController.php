<?php

namespace App\Http\Controllers;

use App\Proyect;
use App\Category;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories
        ], 200);
    }

    /**
     * Display a listing of the resource with proyects.
     *
     * @return \Illuminate\Http\Response
     */
    public function CategoriesProyects()
    {
        $categories = Category::with('proyects')->has('proyects')->get();

        return response()->json([
            'data' => $categories
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
            'name' => 'required|unique:categories|max:255',
            'color_text' => 'required|string|max:255',
            'color_bg' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'image' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $category = Category::create($data);
        return response()->json([
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'data' => $category
        ], 200);
    }

    /**
     * Display a listing of the resource by language.
     *
     * @return \Illuminate\Http\Response
     */
    public function CategoriesWithProyects($locale)
    {
        $language = Language::where('abbreviation', $locale)->firstOrFail();
        $languages = Language::all()->count();
        $categories = Category::with('proyects')->with('languages')->has('proyects')->get();

        $array = [];
        foreach ($categories as $category) {
            $proyects = $category->proyects;
            foreach ($proyects as $proyect) {
                if ($proyect->languages->count() === $languages) {
                    $array[] = $category;
                }
            }
        }

        return response()->json([
            'categories' => array_unique($array)
        ], 200);
    }

    /**
     * Display a listing of the resource by language.
     *
     * @return \Illuminate\Http\Response
     */
    public function CategoriesDescriptionWithAllLanguages()
    {
        $languages = Language::all()->count();
        $categories = Category::all();

        $array = [];
        foreach ($categories as $category) {
            if ($category->languages->count() === $languages) {
                $array[] = $category;
            }
        }

        return response()->json([
            'categories' => $array
        ], 200);
    }

    /**
     * Display a listing of the resource by language.
     *
     * @return \Illuminate\Http\Response
     */
    public function CategoriesDescriptionsLanguage($locale, $category)
    {
        $category = Category::findOrFail($category);
        $language = Language::where('abbreviation', $locale)->firstOrFail()->id;

        $descriptions = $category->languages()->where('language_id', $language)->get();

        return response()->json([
            'descriptions' => $descriptions
        ], 200);
    }

    /**
     * Display a listing of the resource by language.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCategoryProyects($locale, $category_id)
    {
        $language = Language::where('abbreviation', $locale)->firstOrFail();
        $category = Category::where('id', $category_id)->firstOrFail();
        $proyects = $category->proyects;

        foreach ($proyects as $proyect) {
            $location = $proyect->languages;
            $images = $proyect->images;
            $categories = $proyect->categories;
            $company = $proyect->company;
        }

        return response()->json([
            'category' => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:categories|max:255',
            'color_text' => 'string|max:255',
            'color_bg' => 'string|max:255',
            'logo' => 'string|max:255',
            'image' => 'string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $category->update($data);
        return response()->json([
            'data' => $category
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->languages()->sync([]);
        $category->proyects()->sync([]);

        $category->delete();

        return response()->json([
            'message' => 'Categoria eliminada exitosamente'
        ]);
    }
}
