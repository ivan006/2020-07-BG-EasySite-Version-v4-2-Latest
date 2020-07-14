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
    $diff_level_1 = $update_object->diff_level_1($update_object, $dropbox_utility_object);
    $diff_level_1_json = json_encode($diff_level_1, JSON_PRETTY_PRINT);
    // $diff_level_2 = $update_object->diff_level_2($update_object, $dropbox_utility_object);

    // $all2 = $update_object->all($update_object);

    // $timestamp = date('Y-m-d h:i:s a', time());
    file_put_contents(
      "updates_processing_log.txt",
      $diff_level_1_json
    );



    return $diff_level_1;


    $updates_processing_log = $dropbox_utility_object->file_get_utf8("updates_pending_log.txt");
    // $updates_processing_log = json_decode($updates_processing_log, true);

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

  public function diff_level_1($update_object, $dropbox_utility_object){

    // $dropbox_utility_object = new dropbox_utility;
    $old_all_level_2 = $dropbox_utility_object->file_get_utf8("updates_completed_log.txt");
    $old_all_level_2 = json_decode($old_all_level_2, true);


    $all_level_2 = $update_object->all_level_1($update_object, $dropbox_utility_object);
    $all_level_2 = $update_object->all_level_2_helper($all_level_2, $update_object);

    $result["remove"] = array_diff_assoc($old_all_level_2, $all_level_2);
    $result["add"] = array_diff_assoc($all_level_2, $old_all_level_2);

    return $result;
  }

  public function diff_level_2($update_object, $dropbox_utility_object){

    $diff_level_1 = $update_object->diff_level_1($update_object, $dropbox_utility_object);

    $result["remove"] = $update_object->diff_level_2_helper("remove",$diff_level_1);
    $result["add"] = $update_object->diff_level_2_helper("add",$diff_level_1);

    // $result["remove"] = array_diff_assoc($old_all_level_2, $all_level_2);
    // $result["add"] = array_diff_assoc($all_level_2, $old_all_level_2);

    return $result;
  }

  public function diff_level_2_helper($array_name,$diff_level_1){

    $folder_to_remove = array();
    foreach ($diff_level_1[$array_name] as $key => $value) {
      if ($value == 0) {
        $folder_to_remove[$key] = $value;
      }
    }
    $result = $folder_to_remove;
    foreach ($diff_level_1[$array_name] as $key => $value) {
      if ($value !== 0) {
        foreach ($folder_to_remove as $key2 => $value2) {
          if (substr($key, 0, strlen($key2)) !== $key2) {
            $result[$key] = $value;
          }
        }
      }
    }

    return $result;
  }

}
