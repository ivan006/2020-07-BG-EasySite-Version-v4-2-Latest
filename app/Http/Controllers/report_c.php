<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\report;
use Illuminate\Support\Facades\Auth;

class report_c extends Controller
{
  public static function show() {
    $report_object = new report;


    $result = $report_object->show($_GET);

    $data_items = $report_object->show_array($_GET);

    $title = "";

    if (!empty($data_items)) {
      reset($data_items);
      $title = key($data_items);
      $title = $report_object->ends_with($title, "_report");
    }

    return view('welcome', compact('result','title'));

  }

  public static function edit() {
    $report_object = new report;
    $GET = $_GET;


    $data_items = $report_object->show_array($GET);


    $title = "";

    if (!empty($data_items)) {
      reset($data_items);
      $title = key($data_items);
      $title = $report_object->ends_with($title, "_report");

    }

    if(Auth::user()->email_verified_at) {
      return view('home', compact('title'));
    }
    return redirect("");


  }


}
