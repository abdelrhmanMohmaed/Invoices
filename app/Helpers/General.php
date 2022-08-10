<?php

function uploadImage($folder, $image)
{
    $imgName = $image->getClientOriginalName();
    // $newName = time() . '-' . $imgName;
    $path = $image->move($folder, $imgName);
    return $path;
}
