<?php

namespace App\Http\Controllers;

use App\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::orderByDesc('created_at')->paginate(8);
        foreach ($images as $image) {
            $proyect = $image->proyect;
        }
        return response()->json([
            'images' => $images
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
            'url_image' => 'required|mimes:jpeg,jpg,png,gif',
            'proyect_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $data['size'] = bytesToHuman($request['url_image']->getSize());
        $data['name'] = Carbon::now()->timestamp . '.' . $request['url_image']->extension();
        $dataImage = saveNewImage($request['url_image'], 'url_image', $data, 'proyects', 1280, 720);
        $image = Image::create($dataImage);
        return response()->json([
            'image' => $image
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return response()->json([
            'data' => $image
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        deleteImage($image->url_image);

        $image->delete();

        return response()->json([
            'image' => $image,
        ]);
    }
}
