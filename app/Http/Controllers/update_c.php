<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\update;

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

      $diff_level_1 = $update_object->diff_level_1($update_object);
      $diff_level_2 = $update_object->diff_level_2($update_object);

      // $all2 = $update_object->all($update_object);

      return view('test', compact("diff_level_2", "diff_level_1"));
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
