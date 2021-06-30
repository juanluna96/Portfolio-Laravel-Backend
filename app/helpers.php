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
    $img = Image::make(public_path("storage/{$route_image}"))->fit($width, $height);
    $img->save();
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
        $img = Image::make(public_path("storage/{$route_image}"))->fit($width, $height);
        $img->save();
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
