<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\report;
use App\dropbox_utility;
use Illuminate\Support\Facades\Auth;

class report_c extends Controller
{
  public static function show() {
    $report_object = new report;
    $dropbox_utility_object = new dropbox_utility;

    $GET = $_GET;

    $data_items = $report_object->show_array($report_object, $GET);

    $title_and_menu = $report_object->title_and_menu($report_object, $data_items, $GET, $dropbox_utility_object);
    $body = $report_object->show_html($report_object, $data_items, $GET);


    return view('welcome', compact('body','title_and_menu'));

  }

  public static function edit() {
    $report_object = new report;
    $GET = $_GET;


    $data_items = $report_object->show_array($report_object, $GET);


    $title = "";

    if (!empty($data_items)) {
      reset($data_items);
      $title = key($data_items);
      $title = $report_object->report_suffix_remove($title);

    }

    if(Auth::user()->email_verified_at) {
      return view('home', compact('title'));
    }
    return redirect("");


  }


}
