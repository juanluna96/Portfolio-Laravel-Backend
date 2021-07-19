<?php

namespace App\Http\Controllers;

use App\Category;
use App\Language;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['CategoriesProyects', 'CategoriesDescriptionsLanguage', 'CategoriesWithProyects', 'CategoriesDescriptionWithAllLanguages', 'showCategoryProyects']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            $category->area;
        }

        return response()->json([
            'categories' => $categories
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
            'image' => 'required|mimes:jpeg,jpg,png,gif',
            'imageBig' => 'required|mimes:jpeg,jpg,png,gif',
            'area_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();

        $dataImage = saveNewImage($request['image'], 'image', $data, 'categories', 56, 49);

        $newCategory = saveNewImage($request['imageBig'], 'imageBig', $dataImage, 'categories', 341, 296);

        $category = Category::create($newCategory);
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
        $category->languages;
        return response()->json([
            'category' => $category
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

        $categoriesList = array_values(array_unique($array));

        return response()->json([
            'categories' => array_unique($categoriesList)
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
    public function showCategoryProyects($locale, $category)
    {
        $language = Language::where('abbreviation', $locale)->firstOrFail();
        $category = Category::where('name', $category)->firstOrFail();
        $proyects = $category->proyects()->paginate(8);

        foreach ($proyects as $proyect) {
            $location = $proyect->languages;
            $images = $proyect->images;
            $categories = $proyect->categories;
            $company = $proyect->company;
        }

        return response()->json([
            'category' => $category,
            'proyects' => $proyects
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category)
    {
        $category = Category::findOrFail($category);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'color_text' => 'required|string|max:255',
            'color_bg' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'image' => 'required',
            'area_id' => 'required',
            'imageBig' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();

        $dataImage = replaceNewImage($category->image, $request['image'], 'image', $data, 'categories', 56, 49);
        $updated = replaceNewImage($category->imageBig, $request['imageBig'], 'imageBig', $dataImage, 'categories', 341, 296);

        $category->update($updated);

        return response()->json([
            'data' => $category
        ], 200);
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
        deleteImage($category->image);
        deleteImage($category->imageBig);

        $category->delete();

        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Remove the specified description of categories from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroyDescription(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $description = $request->description;
        $category->languages()->newPivotQuery()->where('category_id', $request->category_id)->where('description', $request->description)->where('language_id', $request->language_id)->delete();

        return response()->json([
            'description' => $description
        ], 202);
    }

    /**
     * Add a description to a category.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function storeLanguageCategoryDescription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validations' => $validator->errors()
            ], 400);
        }

        $category = Category::findOrFail($request->category_id);
        $language = Language::findOrFail($request->language_id);
        $category->languages()->attach($request->language_id, $request->all());
        return response()->json([
            'language' => $language,
        ]);
    }
}
