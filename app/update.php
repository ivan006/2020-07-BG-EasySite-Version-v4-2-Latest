<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dropbox_utility;

class update extends Model
{

  public function status(){
    $result = storage_path()."/app/status";
    return $result;
  }

  public function webhook(){
    $update_object = new update;
    $dropbox_utility_object = new dropbox_utility;

    $webhook = $update_object->status()."/"."webhook.txt";

    $signal_status = "";

    if (isset($_GET['challenge'])) {
      $signal_status = "signal_test_passed";

      $result = $_GET['challenge'];
      // $timestamp = date('Y-m-d h:i:s a', time());
      // file_put_contents(
      //   $webhook,
      //   "ready"." ".$timestamp
      // );
      file_put_contents($webhook, "pending");
      return $result;

    } elseif ($dropbox_utility_object->authenticate() == 1) {
      $signal_status = "signal_security_passed";

      file_put_contents($webhook, "pending");

    } else {
      $signal_status = "signal_security_failed";

      header('HTTP/1.0 403 Forbidden');
      // file_put_contents($webhook, "not_ready");

    }

  }

  public function sync($update_object, $dropbox_utility_object){

    // $result = $dropbox_utility_object->dropbox_get_request("", $update_object, "files/list_folder");
    // dd($result);

    $time_i = strtotime("now");

    $state_diff = $update_object->status()."/"."state_diff.txt";
    $state_diff = $dropbox_utility_object->file_get_utf8($state_diff);

    $webhook_path = $update_object->status()."/"."webhook.txt";
    $webhook = $dropbox_utility_object->file_get_utf8($webhook_path);
    $webhook = preg_replace('/\s+/', '', $webhook);
    file_put_contents($webhook_path, "done");

    $promise_proc = $update_object->status()."/"."promise_proc.txt";
    $promise_proc = $dropbox_utility_object->file_get_utf8($promise_proc);
    $promise_proc = preg_replace('/\s+/', '', $promise_proc);

    $promise_init = $update_object->status()."/"."promise_init.txt";
    $promise_init = $dropbox_utility_object->file_get_utf8($promise_init);

    $state_local = $update_object->status()."/"."state_local.txt";
    $state_local = $dropbox_utility_object->file_get_utf8($state_local);




    $result = "standby";

    if ($state_diff !== "") {

      if ($promise_proc == "open") {

        $result = $update_object->process($update_object, $dropbox_utility_object, $state_diff, $time_i, $state_local);
      } else {
        $result = "processing";
      }

    } elseif ($webhook == "pending") {

      $result = "initialised";

      $initialise = $update_object->initialise($update_object, $dropbox_utility_object);
      $initialise_json = json_encode($initialise, JSON_PRETTY_PRINT);

      $state_diff_path = $update_object->status()."/"."state_diff.txt";
      file_put_contents(
        $state_diff_path,
        $initialise_json
      );

    } elseif ($promise_init == "closed") {

      $result = "initialising";

    }

    if ($result !== "standby") {
      $status_keys = array(
        "complete" => "prcd",
        "clipping" => "rest",
        "processing" => "prc",
        "initialised" => "strtd",
        "initialising" => "strt",
        "standby" => "null",
      );

      $daystamp = date('Y-m-d', time());
      $log_path = $update_object->status()."/../logs/"."log-".$daystamp.".txt";

      $timestamp = date('H:i:s', time());
      $msg = $timestamp." - ".$status_keys[$result];
      file_put_contents(
        $log_path,
        $msg.PHP_EOL,
        FILE_APPEND
      );
    }
    return $result;

    // return $initialise;

    // $state_diff = $dropbox_utility_object->file_get_utf8("webhook.txt");
    // // $state_diff = json_decode($state_diff, true);

  }

  public function schedule(){

      // $t = time();
      // $timestamp = date('Y-m-d', $t)."T".date('H:i:s', $t)."Z";
      // echo $timestamp;
      // exit;

      $update_object = new update;
      $dropbox_utility_object = new dropbox_utility;

      $result = $update_object->dropbox_state_level($update_object, $dropbox_utility_object);
      // $result = $update_object->dropbox_state_level_2($update_object, $dropbox_utility_object);

      dd($result);


  }

  public function initialise($update_object, $dropbox_utility_object){
    $promise_init_path = $update_object->status()."/"."promise_init.txt";
    file_put_contents(
      $promise_init_path,
      "closed"
    );

    // $dropbox_utility_object = new dropbox_utility;
    $state_local_path = $update_object->status()."/"."state_local.txt";
    $state_local = $dropbox_utility_object->file_get_utf8($state_local_path);
    $state_local = json_decode($state_local, true);


    // $dropbox_state_level = $update_object->dropbox_state_level_2($update_object, $dropbox_utility_object);
    $dropbox_state_level = $update_object->dropbox_state_level($update_object, $dropbox_utility_object);

    $result["remove"] = array_diff_assoc($state_local, $dropbox_state_level);
    $result["add"] = array_diff_assoc($dropbox_state_level, $state_local);



    $promise_init_path = $update_object->status()."/"."promise_init.txt";
    file_put_contents(
      $promise_init_path,
      "open"
    );

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

  public function process($update_object, $dropbox_utility_object, $state_diff, $time_i, $state_local){
    $promise_proc_path = $update_object->status()."/"."promise_proc.txt";
    file_put_contents(
      $promise_proc_path,
      "closed"
    );

    // file_put_contents(
    //   "state_local.txt",
    //   $initialise_json
    // );
    // file_put_contents("state_diff.txt", "");

    $pub_store = storage_path()."/app/public/";
    // $files = scandir($pub_store);

    $state_diff = json_decode($state_diff, true);
    $state_local = json_decode($state_local, true);
    // dd($state_diff);

    $result = "complete";

    $report_object = new report;
    $repo_path = $report_object->repo_path();


    foreach ($state_diff["remove"] as $key => $value) {

      $file_path = $repo_path.$key;

      if (file_exists($file_path)) {
        if ($value !== 0) {
          // exec( "rm $file_path");
          unlink($file_path);
        } else {
          // exec( "rm -r -f $file_path");
          $dropbox_utility_object->rrmdir($dropbox_utility_object, $file_path);
        }
      }

      $state_local = $update_object->update_state_local_status(
        $update_object,
        $state_local,
        $state_diff,
        $key,
        "remove"
      );

      $state_diff = $update_object->update_state_diff_status(
        $update_object,
        $state_diff,
        $key,
        "remove"
      );

    }

    foreach ($state_diff["add"] as $key => $value) {

      $file_path = $repo_path.$key;
      // echo $file_path."<br>";

      if ($value !== 0) {

        $link_util = $dropbox_utility_object->dropbox_temp_link($key, $dropbox_utility_object);

        file_put_contents($file_path, fopen($link_util["link"], 'r'));

        // $file_content = file_get_contents($file_content);

      } else {
        mkdir($file_path);
      }

      $state_local = $update_object->update_state_local_status(
        $update_object,
        $state_local,
        $state_diff,
        $key,
        "add"
      );

      $state_diff = $update_object->update_state_diff_status(
        $update_object,
        $state_diff,
        $key,
        "add"
      );

      $time_f = strtotime("now");
      $time_dif = $time_f-$time_i;
      if ($time_dif > 80) {
        $result = "clipping";
        break;
      }

    }

    $promise_proc_path = $update_object->status()."/"."promise_proc.txt";
    file_put_contents(
      $promise_proc_path,
      "open"
    );

    if ($result == "complete") {
      $state_diff_path = $update_object->status()."/"."state_diff.txt";
      file_put_contents(
        $state_diff_path,
        ""
      );
    }

    return $result;


  }

  public function update_state_local_status($update_object, $state_local, $state_diff, $key, $action){

    if ($action == "add") {
      $state_local[$key] = $state_diff[$action][$key];
    } elseif ($action == "remove") {
      unset($state_local[$key]);
    }

    $state_local_json = json_encode($state_local, JSON_PRETTY_PRINT);
    $state_local_path = $update_object->status()."/"."state_local.txt";
    file_put_contents(
      $state_local_path,
      $state_local_json
    );

    return $state_local;

  }

  public function update_state_diff_status($update_object, $state_diff, $key, $action){

    unset($state_diff[$action][$key]);
    $state_diff_json = json_encode($state_diff, JSON_PRETTY_PRINT);

    $state_diff_path = $update_object->status()."/"."state_diff.txt";
    file_put_contents(
      $state_diff_path,
      $state_diff_json
    );
    return $state_diff;

  }

  public function update_state_remote_status($update_object, $state_remote){

    $state_remote_json = json_encode($state_remote, JSON_PRETTY_PRINT);

    $state_remote_path = $update_object->status()."/"."state_remote.txt";
    file_put_contents(
      $state_remote_path,
      $state_remote_json
    );
    return $state_remote;

  }

  public function dropbox_state_level($update_object, $dropbox_utility_object){

    $path = "";

    $state_remote = array();

    $result = $update_object->dropbox_state_level_helper($path, $update_object, $dropbox_utility_object, $state_remote);


    return $result;
  }

  public function dropbox_state_level_helper($path, $update_object, $dropbox_utility_object, $state_remote){

    $list = $dropbox_utility_object->dropbox_get_request($path, $update_object, "files/list_folder");

    if (isset($list["entries"])) {
      $list = $list["entries"];

      foreach ($list as $key => $value) {
        $state_key = $value["path_display"];
        if ($value[".tag"] == "folder") {
          $state_value = "unexplored";
        } else {
          $state_value = $value["server_modified"];
        }
        $state_remote[$state_key] = $state_value;
      }
    }

    if (isset($state_remote[$path])) {
      $state_remote[$path] = 0;
    }

    $update_object->update_state_remote_status($update_object, $state_remote);

    // $time_f = strtotime("now");
    // $time_dif = $time_f-$time_i;
    // if ($time_dif > 80) {
    //
    //   $promise_init_path = $update_object->status()."/"."promise_init.txt";
    //   file_put_contents(
    //     $promise_init_path,
    //     "open"
    //   );
    //
    // }


    foreach ($state_remote as $key => $value) {
      if ($value === "unexplored") {



        $temp = $update_object->status()."/"."temp.txt";

        $state_remote_json = json_encode($state_remote, JSON_PRETTY_PRINT);
        file_put_contents(
          $temp,
          $state_remote_json
        );

        $state_remote = $update_object->dropbox_state_level_helper(
          $key,
          $update_object,
          $dropbox_utility_object,
          $state_remote
        );


      }
    }

    $result = $state_remote;
    return $result;
  }

}
