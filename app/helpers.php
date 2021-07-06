<?php

function validateInputsJson($name, $validator, $errors)
{
    if ($validator->fails()) {
        $errors += [$name =>   $validator->errors()];
    }

    return $errors;
}

function saveNewImage($requestImage, $nameRequest, $data, $folder, $width, $height)
{
    $route_image = $requestImage->store($folder, 'public');
    $img = Image::make(public_path("storage/{$route_image}"));
    $img->resize($width, $height, function ($const) {
        $const->aspectRatio();
    })->save();
    $data[$nameRequest] = $route_image;

    return $data;
}

function replaceNewImage($oldImage, $newImage, $nameRequest, $data, $folder, $width, $height)
{
    if ($newImage !== $oldImage) {
        // Borrar la imagen anterior y guardar la imagen nueva para actualizar la imagen
        if (File::exists(public_path('storage/' . $oldImage))) {
            unlink(public_path('storage/' . $oldImage));
        }
        $route_image = $newImage->store($folder, 'public');
        $img = Image::make(public_path("storage/{$route_image}"));
        $img->resize($width, $height, function ($const) {
            $const->aspectRatio();
        })->save();
        $data[$nameRequest] = $route_image;
    }

    return $data;
}

function deleteImage($image)
{
    if (File::exists(public_path('storage/' . $image))) {
        unlink(public_path('storage/' . $image));
    }
}

function isJson($string)
{
    return is_object(json_decode($string));
}

function bytesToHuman($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    for ($i = 0; $bytes > 1024; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
}
