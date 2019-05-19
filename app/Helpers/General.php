<?php

function uploadFile($request, $name, $destination = '')
{
    $image = $request->file($name);

    $name = time().'.'.$image->getClientOriginalExtension();

    if($destination == '') {
        $destination = public_path('/uploads');
    }

    $image->move($destination, $name);

    return $name;
}