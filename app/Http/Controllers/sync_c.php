<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\sync;
use App\dropbox_utility;

class sync_c extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sync()
    {
      $sync_object = new sync;

      $dropbox_utility_object = new dropbox_utility;

      $result = $sync_object->sync($sync_object, $dropbox_utility_object);

      return $result;
    }

    public function process_queue()
    {
      $sync_object = new sync;
      $var1 = 1;
      $var2 = $sync_object->process_queue();
      // $var2 = json_encode($var2, JSON_PRETTY_PRINT);
      return $var2;
    }

    public function schedule()
    {
      $sync_object = new sync;
      $result = $sync_object->schedule();
      return $result;
    }

}
