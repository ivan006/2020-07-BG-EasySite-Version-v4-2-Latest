<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dropbox_utility;

class update extends Model
{

  public function stasis(){
    $result = storage_path()."/app/stasis";
    return $result;
  }

  public function webhook(){
    $update_object = new update;
    $dropbox_utility_object = new dropbox_utility;

    $sync_webhook = $update_object->stasis()."/"."sync_webhook.txt";

    $signal_status = "";

    if (isset($_GET['challenge'])) {
      $signal_status = "signal_test_passed";

      $result = $_GET['challenge'];
      // $timestamp = date('Y-m-d h:i:s a', time());
      // file_put_contents(
      //   $sync_webhook,
      //   "ready"." ".$timestamp
      // );
      file_put_contents($sync_webhook, "pending");
      return $result;

    } elseif ($dropbox_utility_object->authenticate() == 1) {
      $signal_status = "signal_security_passed";

      file_put_contents($sync_webhook, "pending");

    } else {
      $signal_status = "signal_security_failed";

      header('HTTP/1.0 403 Forbidden');
      // file_put_contents($sync_webhook, "not_ready");

    }

  }

  public function sync($update_object, $dropbox_utility_object){

    $time_i = strtotime("now");

    $sync_diff = $update_object->stasis()."/"."sync_diff.txt";
    $sync_diff = $dropbox_utility_object->file_get_utf8($sync_diff);

    $sync_webhook = $update_object->stasis()."/"."sync_webhook.txt";
    $sync_webhook = $dropbox_utility_object->file_get_utf8($sync_webhook);

    $sync_promise = $update_object->stasis()."/"."sync_promise.txt";
    $sync_promise = $dropbox_utility_object->file_get_utf8($sync_promise);

    $result = "inactive";

    if ($sync_diff !== "") {

      if ($sync_promise !== "closed") {

        $result = $update_object->process($update_object, $dropbox_utility_object, $sync_diff, $time_i);

      } else {
        $result = "processing";
      }

    } elseif ($sync_webhook !== "done") {

      $result = "initialised";

      $initialise = $update_object->initialise($update_object, $dropbox_utility_object);
      $initialise_json = json_encode($initialise, JSON_PRETTY_PRINT);

      $sync_diff_path = $update_object->stasis()."/"."sync_diff.txt";
      file_put_contents(
        $sync_diff_path,
        $initialise_json
      );

      $sync_webhook_path = $update_object->stasis()."/"."sync_webhook.txt";
      file_put_contents($sync_webhook_path, "done");

    }

    return $result;

    // return $initialise;

    // $sync_diff = $dropbox_utility_object->file_get_utf8("sync_webhook.txt");
    // // $sync_diff = json_decode($sync_diff, true);

  }

  public function initialise($update_object, $dropbox_utility_object){

    // $dropbox_utility_object = new dropbox_utility;
    $completed = $dropbox_utility_object->file_get_utf8("updates_completed_log.txt");
    $completed = json_decode($completed, true);


    // $dropbox_state_level_2 = $update_object->dropbox_state_level_1($update_object, $dropbox_utility_object);
    $dropbox_state_level_2 = $update_object->dropbox_state_level_2($update_object, $dropbox_utility_object);

    $result["remove"] = array_diff_assoc($completed, $dropbox_state_level_2);
    $result["add"] = array_diff_assoc($dropbox_state_level_2, $completed);

    return $result;
  }

  public function dropbox_state_level_1($update_object, $dropbox_utility_object){

    $path = "";

    $result = $update_object->dropbox_state_level_1_helper($path, "", $update_object, $dropbox_utility_object);


    return $result;
  }

  public function dropbox_state_level_1_helper($path, $called, $update_object, $dropbox_utility_object){

    $result = $dropbox_utility_object->dropbox_get_request($path, $update_object, "files/list_folder");


    if (isset($result["entries"])) {
      $result = $result["entries"];

      $called = "";

      if (isset($result)) {
        foreach ($result as $key => $entry) {

          if ($entry['.tag'] == "folder") {
            $sub_result = $update_object->dropbox_state_level_1_helper($entry['path_display'], $called, $update_object, $dropbox_utility_object);
            $result[$key]["child_content"] = $sub_result;
          } else {
            $result[$key]["child_content"] = "";
          }
        }
      }
    }
    return $result;
  }

  public function dropbox_state_level_2($update_object, $dropbox_utility_object){

    $dropbox_state_level_1 = $update_object->dropbox_state_level_1($update_object, $dropbox_utility_object);

    $result = $update_object->dropbox_state_level_2_helper($dropbox_state_level_1, $update_object);

    return $result;
  }

  public function dropbox_state_level_2_helper($dropbox_state_level_1, $update_object){
    $result = array();
    if (is_array($dropbox_state_level_1)) {
      foreach ($dropbox_state_level_1 as $key => $value) {
        if (isset($value[".tag"]) and isset($value['path_display'])) {
          $name = $value["path_display"];
          // $name = str_replace("\\", "", $name);
          if ($value[".tag"] == "folder") {
            $result[$name] = 0;
            $result = array_merge($result, $update_object->dropbox_state_level_2_helper($value["child_content"], $update_object));
          } else {
            $result[$name] = $value["server_modified"];
          }
        }
      }
    }
    return $result;
  }

  public function process($update_object, $dropbox_utility_object, $sync_diff, $time_i){
    $sync_promise_path = $update_object->stasis()."/"."sync_promise.txt";
    file_put_contents(
      $sync_promise_path,
      "closed"
    );

    // file_put_contents(
    //   "updates_completed_log.txt",
    //   $initialise_json
    // );
    // file_put_contents("sync_diff.txt", "");

    $pub_store = storage_path()."/app/public/";
    $files = scandir($pub_store);

    $sync_diff = json_decode($sync_diff, true);
    // dd($sync_diff);

    $result = "completed";

    foreach ($sync_diff["remove"] as $key => $value) {
      // echo $key."<br>";
    }

    foreach ($sync_diff["add"] as $key => $value) {
      $report_object = new report;
      $repo_path = $report_object->repo_path();

      $file_path = $repo_path.$key;
      // echo $file_path."<br>";

      if ($value !== 0) {

        if (!file_exists($file_path)) {

          $link_util = $dropbox_utility_object->dropbox_temp_link($key, $dropbox_utility_object);

          file_put_contents($file_path, fopen($link_util["link"], 'r'));
        }

        // $file_content = file_get_contents($file_content);

      } else {
        if (!file_exists($file_path)) {
          mkdir($file_path);
        }
      }
      unset($sync_diff["add"][$key]);
      $sync_diff_json = json_encode($sync_diff, JSON_PRETTY_PRINT);

      $sync_diff_path = $update_object->stasis()."/"."sync_diff.txt";
      file_put_contents(
        $sync_diff_path,
        $sync_diff_json
      );

      $time_f = strtotime("now");
      $time_dif = $time_f-$time_i;
      if ($time_dif > 80) {
        $result = "stage_complete";
        break;
      }

    }

    $sync_promise_path = $update_object->stasis()."/"."sync_promise.txt";
    file_put_contents(
      $sync_promise_path,
      "open"
    );

    if ($result == "completed") {
      $sync_diff_path = $update_object->stasis()."/"."sync_diff.txt";
      file_put_contents(
        $sync_diff_path,
        ""
      );
    }

    return $result;




  }

}
