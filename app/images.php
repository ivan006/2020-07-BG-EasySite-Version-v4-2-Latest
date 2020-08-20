<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\dropbox_utility;
// use App\sync;
use App\Link;

class images extends Model
{

  public function index($image_object, $GET, $size = 40){

    $dropbox_utility_object = new dropbox_utility;
    $link = $dropbox_utility_object->get_var_to_link_utils($GET);
    // dd($link);

    $image = storage_path().'/profile_pictures/'.$link['current_link'].'/profile_'.$size.'.jpg';

    if(!File::exists( $image ))
    App::abort(404);

    return $image;
  }

}
