<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dropbox_utility;

class update extends Model
{
  public function pending(){
    $update_object = new update;
    $dropbox_utility = new dropbox_utility;

    $pending_log = "updates_pending_log.txt";

    $signal_status = "";

    if (isset($_GET['challenge'])) {
      $signal_status = "signal_test_passed";

      $result = $_GET['challenge'];
      // $timestamp = date('Y-m-d h:i:s a', time());
      // file_put_contents(
      //   $pending_log,
      //   "ready"." ".$timestamp
      // );
      file_put_contents($pending_log, "yes");
      return $result;

    } elseif ($dropbox_utility_object->authenticate() == 1) {
      $signal_status = "signal_security_passed";

      file_put_contents($pending_log, "yes");

    } else {
      $signal_status = "signal_security_failed";

      header('HTTP/1.0 403 Forbidden');
      // file_put_contents($pending_log, "not_ready");

    }

  }

  public function processing($update_object, $dropbox_utility_object){

    // $timestamp = date('Y-m-d h:i:s a', time());

    $updates_processing_log = $dropbox_utility_object->file_get_utf8("updates_processing_log.txt");
    $updates_pending_log = $dropbox_utility_object->file_get_utf8("updates_pending_log.txt");

    if ($updates_processing_log !== "no") {

      $update_object->add_to_completed($update_object, $dropbox_utility_object, $updates_processing_log);

    } elseif ($updates_pending_log !== "no") {

      $add_to_processing = $update_object->add_to_processing($update_object, $dropbox_utility_object);
      $add_to_processing_json = json_encode($add_to_processing, JSON_PRETTY_PRINT);

      file_put_contents(
        "updates_processing_log.txt",
        $add_to_processing_json
      );
      file_put_contents("updates_pending_log.txt", "no");

    }

    // return $add_to_processing;

    // $updates_processing_log = $dropbox_utility_object->file_get_utf8("updates_pending_log.txt");
    // // $updates_processing_log = json_decode($updates_processing_log, true);

  }

  public function add_to_processing($update_object, $dropbox_utility_object){

    // $dropbox_utility_object = new dropbox_utility;
    $completed = $dropbox_utility_object->file_get_utf8("updates_completed_log.txt");
    $completed = json_decode($completed, true);


    // $all_level_2 = $update_object->all_level_1($update_object, $dropbox_utility_object);
    $all_level_2 = $update_object->all_level_2($update_object, $dropbox_utility_object);

    $result["remove"] = array_diff_assoc($completed, $all_level_2);
    $result["add"] = array_diff_assoc($all_level_2, $completed);

    return $result;
  }


  public function all_level_1($update_object, $dropbox_utility_object){

    $path = "";

    $result = $update_object->all_level_1_helper($path, "", $update_object, $dropbox_utility_object);


    return $result;
  }

  public function all_level_1_helper($path, $called, $update_object, $dropbox_utility_object){

    $result = $dropbox_utility_object->get_from_dropbox($path, $update_object, "files/list_folder");


    if (isset($result["entries"])) {
      $result = $result["entries"];

      $called = "";

      if (isset($result)) {
        foreach ($result as $key => $entry) {

          if ($entry['.tag'] == "folder") {
            $sub_result = $update_object->all_level_1_helper($entry['path_display'], $called, $update_object, $dropbox_utility_object);
            $result[$key]["child_content"] = $sub_result;
          } else {
            $result[$key]["child_content"] = "";
          }
        }
      }
    }
    return $result;
  }

  public function all_level_2($update_object, $dropbox_utility_object){

    $all_level_1 = $update_object->all_level_1($update_object, $dropbox_utility_object);

    $result = $update_object->all_level_2_helper($all_level_1, $update_object);

    return $result;
  }

  public function all_level_2_helper($all_level_1, $update_object){
    $result = array();
    if (is_array($all_level_1)) {
      foreach ($all_level_1 as $key => $value) {
        if (isset($value[".tag"]) and isset($value['path_display'])) {
          $name = $value["path_display"];
          // $name = str_replace("\\", "", $name);
          if ($value[".tag"] == "folder") {
            $result[$name] = 0;
            $result = array_merge($result, $update_object->all_level_2_helper($value["child_content"], $update_object));
          } else {
            $result[$name] = $value["server_modified"];
          }
        }
      }
    }
    return $result;
  }

  public function add_to_completed($update_object, $dropbox_utility_object, $updates_processing_log){
    // file_put_contents(
    //   "updates_completed_log.txt",
    //   $add_to_processing_json
    // );
    // file_put_contents("updates_processing_log.txt", "no");

    $pub_store = storage_path()."/app/public/";
    $files = scandir($pub_store);

    // dd($pub_store);

    foreach ($updates_processing_log["remove"] as $key => $value) {

    }

    foreach ($updates_processing_log["add"] as $key => $value) {

    }

  }

}
