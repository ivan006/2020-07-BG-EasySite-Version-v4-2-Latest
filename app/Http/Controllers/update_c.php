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
    public function state()
    {
      $update_object = new update;
      // $state = $update_object->state_raw();
      // dd($state);


      $diff_level_1 = $update_object->diff_level_1($update_object);
      $diff_level_2 = $update_object->diff_level_2($update_object);

      // $var1 = json_encode($var1, JSON_PRETTY_PRINT);

      $state2 = $update_object->state($update_object);

      return view('welcome', compact("diff_level_2", "diff_level_1"));
    }

    public function update_updates_pending_log()
    {
      $update_object = new update;
      $var1 = 1;
      $var2 = $update_object->update_updates_pending_log();
      // $var2 = json_encode($var2, JSON_PRETTY_PRINT);
      return $var2;
    }

}
