<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use App\dropbox_utility;
// use App\sync;

// use App\Link;

class images extends Model
{

  public function index($image_object, $GET){

    $dropbox_utility_object = new dropbox_utility;
    // $link = $dropbox_utility_object->get_var_to_link_utils($GET);
    $link = $GET[1];

    $image = storage_path().'/'.$link.'';

    // if(!File::exists( $image ))
    //   App::abort(404);

    return $image;
  }

}
