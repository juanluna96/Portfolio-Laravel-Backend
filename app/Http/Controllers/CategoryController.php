<?php

namespace App\Http\Controllers;

use App\Category;
use App\Language;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
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
            'imageBig' => 'required|mimes:jpeg,jpg,png,gif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();

        $route_image = $request['image']->store('categories', 'public');
        $img = Image::make(public_path("storage/{$route_image}"))->fit(56, 49);
        $img->save();
        $data['image'] = $route_image;

        $route_imageBig = $request['imageBig']->store('categories', 'public');
        $imgBig = Image::make(public_path("storage/{$route_imageBig}"))->fit(341, 296);
        $imgBig->save();
        $data['imageBig'] = $route_imageBig;
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
    public function update(Request $request, $category)
    {
        $category = Category::findOrFail($category);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'color_text' => 'required|string|max:255',
            'color_bg' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'image' => 'required',
            'imageBig' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();

        if ($request['image'] !== $category->image) {
            // Borrar la imagen anterior y guardar la imagen nueva para actualizar la imagen
            unlink(public_path('storage/' . $category->image));
            $route_image = $request['image']->store('categories', 'public');
            $img = Image::make(public_path("storage/{$route_image}"))->fit(56, 49);
            $img->save();
            $data['image'] = $route_image;
        }

        if ($request['imageBig'] !== $category->imageBig) {
            // Borrar la imagen anterior y guardar la imagen nueva para actualizar la imagen
            unlink(public_path('storage/' . $category->imageBig));
            $route_imageBig = $request['imageBig']->store('categories', 'public');
            $img = Image::make(public_path("storage/{$route_imageBig}"))->fit(341, 296);
            $img->save();
            $data['imageBig'] = $route_imageBig;
        }

        $category->update($data);

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
        unlink(public_path('storage/' . $category->image));
        unlink(public_path('storage/' . $category->imageBig));

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
