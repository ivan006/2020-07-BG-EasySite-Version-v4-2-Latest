<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\update;
use App\dropbox_utility;

class update_c extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function processing()
    {
      $update_object = new update;

      $dropbox_utility_object = new dropbox_utility;

      $result = $update_object->processing($update_object, $dropbox_utility_object);

      return $result;
    }

    public function pending()
    {
      $update_object = new update;
      $var1 = 1;
      $var2 = $update_object->pending();
      // $var2 = json_encode($var2, JSON_PRETTY_PRINT);
      return $var2;
    }

}
