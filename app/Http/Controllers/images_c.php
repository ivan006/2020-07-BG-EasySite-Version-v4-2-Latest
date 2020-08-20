<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\report;
use App\images;
use Illuminate\Support\Facades\Auth;

class images_c extends Controller
{
  public static function index() {
    $image_object = new images;
    $GET = $_GET;

    $image = $image_object->index($image_object, $GET, 40);

    return Image::make($image)->response('jpg');

  }

}
