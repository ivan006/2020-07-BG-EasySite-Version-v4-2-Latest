<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dropbox_utility;

class sync extends Model
{
  public $state_remote = array();
  public $state_local = array();
  public $state_diff = array();

  public function status(){
    $result = storage_path()."/app/status";
    return $result;
  }

  public function process_queue(){
    $sync_object = new sync;
    $dropbox_utility_object = new dropbox_utility;


    $signal_status = "";

    if (isset($_GET['challenge'])) {
      $signal_status = "signal_test_passed";

      $result = $_GET['challenge'];
      // $timestamp = date('Y-m-d h:i:s a', time());
      // file_put_contents(
      //   $process_queue,
      //   "ready"." ".$timestamp
      // );

      // $process_queue_path = $sync_object->status()."/"."process_queue.txt";
      // file_put_contents($process_queue_path, "occupied");
      return $result;

    } elseif ($dropbox_utility_object->authenticate() == 1) {
      $signal_status = "signal_security_passed";

      $process_queue_path = $sync_object->status()."/"."process_queue.txt";
      file_put_contents($process_queue_path, "occupied");

    } else {
      $signal_status = "signal_security_failed";

      header('HTTP/1.0 403 Forbidden');
      // file_put_contents($process_queue, "not_ready");

    }

  }

  public function sync($sync_object, $dropbox_utility_object){

    $time_i = strtotime("now");

    $sync_object->check_data($sync_object, $dropbox_utility_object);

    $process_queue_path = $sync_object->status()."/"."process_queue.txt";
    $process_queue = $dropbox_utility_object->file_get_utf8($process_queue_path);
    $process_queue = preg_replace('/\s+/', '', $process_queue);

    $process_stager_path = $sync_object->status()."/"."process_stager.txt";
    $process_stager = $dropbox_utility_object->file_get_utf8($process_stager_path);
    $process_stager = preg_replace('/\s+/', '', $process_stager);

    $state_diff_path = $sync_object->status()."/"."state_diff.txt";
    // $state_local = $sync_object->status()."/"."state_local.txt";
    $state_remote_path = $sync_object->status()."/"."state_remote.txt";

    file_put_contents($process_queue_path, "vacant");

    if ($process_queue == "occupied" and $process_stager == "standby") {
      $process_stager = "calculation";
    }

    if ($process_stager == "calculation") {

      $next_process_stager = "calculation_standby";
      file_put_contents($process_stager_path, $next_process_stager);

      $search_results = array_search("unexplored", $sync_object->state_remote);

      while ($search_results !== false ) {
        $time_f = strtotime("now");
        $time_dif = $time_f-$time_i;
        if ($time_dif > 80) {
          $next_process_stager = "calculation";
          break;
        }

        $sync_object->calculation_part_1(
          $sync_object,
          $dropbox_utility_object,
          $search_results
        );

        $search_results = array_search("unexplored", $sync_object->state_remote);
      }

      if ($next_process_stager == "calculation_standby") {

        $result = $sync_object->calculation_part_2(
          $sync_object,
          $state_remote_path
        );

        $next_process_stager = "implementation";

      } elseif ($next_process_stager == "calculation") {

        $state_remote_json = json_encode($sync_object->state_remote, JSON_PRETTY_PRINT);
        file_put_contents($state_remote_path, $state_remote_json);

      }

      file_put_contents($process_stager_path, $next_process_stager);



    } elseif ($process_stager == "implementation") {

      $report_object = new report;
      $repo_path = $report_object->repo_path();

      $next_process_stager = "implementation_standby";
      file_put_contents($process_stager_path, $next_process_stager);

      $result = $sync_object->implementation_part_1(
        $sync_object,
        $dropbox_utility_object,
        $repo_path
      );

      foreach ($sync_object->state_diff["add"] as $key => $value) {

        $sync_object->implementation_part_2(
          $sync_object,
          $dropbox_utility_object,
          $key,
          $value,
          $repo_path
        );

        $time_f = strtotime("now");
        $time_dif = $time_f-$time_i;
        if ($time_dif > 80) {
          $next_process_stager = "implementation";
          break;
        }

      }

      if ($next_process_stager == "implementation_standby") {
        file_put_contents($state_diff_path, "");
        $next_process_stager = "standby";
      }

      file_put_contents($process_stager_path, $next_process_stager);


    }

    // if ($process_stager !== "standby") {
    if ($process_stager == "implementation" or $process_stager == "calculation") {

      $daystamp = date('Y-m-d', time());
      $log_path = $sync_object->status()."/../logs/"."log-".$daystamp.".txt";
      $timestamp = date('H:i:s', time());
      $msg = $timestamp." - ".$process_stager;

      file_put_contents($log_path, $msg.PHP_EOL, FILE_APPEND);
    }
    return $process_stager;


  }

  public function schedule(){

      // // // $t = time();
      // // // $timestamp = date('Y-m-d', $t)."T".date('H:i:s', $t)."Z";
      // // // echo $timestamp;
      // // // exit;
      // //
      // // $sync_object = new sync;
      // // $dropbox_utility_object = new dropbox_utility;
      // //
      // //
      // // $result = $sync_object->calculation_part_1($sync_object, $dropbox_utility_object, $time_i);
      // //
      // //
      // // $state_remote = $sync_object->status()."/"."state_remote.txt";
      // // $state_remote = $dropbox_utility_object->file_get_utf8($state_remote);
      // // $state_remote = json_decode($state_remote, true);
      // //
      // // // $result = $sync_object->dropbox_state_level_2($sync_object, $dropbox_utility_object);
      // //
      // // dd($result);
      //
      //
      // $sync_object = new sync;
      // $dropbox_utility_object = new dropbox_utility;
      //
      // $state_remote = $sync_object->status()."/"."state_remote.txt";
      // $state_remote = $dropbox_utility_object->file_get_utf8($state_remote);
      // $state_remote = json_decode($state_remote, true);
      //
      // if (!empty($state_remote)) {
      //   $unexplored_key = array_search("unexplored", $state_remote);
      //   if ($unexplored_key !== false) {
      //     $path = $unexplored_key;
      //
      //     $temp = $sync_object->status()."/"."temp.txt";
      //     $state_remote_json = json_encode($state_remote, JSON_PRETTY_PRINT);
      //     file_put_contents(
      //       $temp,
      //       $unexplored_key
      //     );
      //   } else {
      //     return "calculationd";
      //   }
      // } else {
      //   $path = "";
      // }
      // var_dump($unexplored_key);

      $var = [
        123=>123,
        234=>234
      ];
      dd($var);


  }

  public function calculation_part_2($sync_object, $state_remote_path){

    $sync_object->state_diff["remove"] = array_diff_assoc(
      $sync_object->state_local,
      $sync_object->state_remote
    );

    $sync_object->state_diff["add"] = array_diff_assoc(
      $sync_object->state_remote,
      $sync_object->state_local
    );

    $state_diff_json = json_encode($sync_object->state_diff, JSON_PRETTY_PRINT);
    $state_diff_path = $sync_object->status()."/"."state_diff.txt";
    file_put_contents($state_diff_path, $state_diff_json);

    file_put_contents($state_remote_path, '{"":"unexplored"}');
  }

  public function implementation_part_2($sync_object, $dropbox_utility_object, $key, $value, $repo_path){

    $file_path = $repo_path.$key;
    // echo $file_path."<br>";

    if ($value !== "is_folder") {

      $link_util = $dropbox_utility_object->dropbox_temp_link($key, $dropbox_utility_object);

      file_put_contents($file_path, fopen($link_util["link"], 'r'));

      // $file_content = file_get_contents($file_content);

    } else {
      if ($file_path !== $repo_path) {
        mkdir($file_path);
      }
    }

    $sync_object->update_state_local_status(
      $sync_object,
      $key,
      "add"
    );

    $sync_object->update_state_diff_status(
      $sync_object,
      $key,
      "add"
    );


  }

  public function implementation_part_1($sync_object, $dropbox_utility_object, $repo_path){


    foreach ($sync_object->state_diff["remove"] as $key => $value) {

      $file_path = $repo_path.$key;

      if (file_exists($file_path)) {
        if ($value !== "is_folder") {
          // exec( "rm $file_path");
          unlink($file_path);
        } else {
          // exec( "rm -r -f $file_path");
          $dropbox_utility_object->rrmdir($dropbox_utility_object, $file_path);
        }
      }

      $sync_object->update_state_local_status(
        $sync_object,
        $key,
        "remove"
      );

      $sync_object->update_state_diff_status(
        $sync_object,
        $key,
        "remove"
      );

    }



  }

  public function update_state_local_status($sync_object, $key, $action){

    if ($action == "add") {
      $sync_object->state_local[$key] = $sync_object->state_diff[$action][$key];
    } elseif ($action == "remove") {
      unset($sync_object->state_local[$key]);
    }

    $state_local_json = json_encode($sync_object->state_local, JSON_PRETTY_PRINT);
    $state_local_path = $sync_object->status()."/"."state_local.txt";
    file_put_contents($state_local_path, $state_local_json);

  }

  public function update_state_diff_status($sync_object, $key, $action){

    unset($sync_object->state_diff[$action][$key]);
    $state_diff_json = json_encode($sync_object->state_diff, JSON_PRETTY_PRINT);

    $state_diff_path = $sync_object->status()."/"."state_diff.txt";
    file_put_contents($state_diff_path, $state_diff_json);

  }

  public function calculation_part_1($sync_object, $dropbox_utility_object, $search_results){

    $path = $search_results;

    $list = $dropbox_utility_object->dropbox_get_request($path, $sync_object, "files/list_folder");

    if (isset($list["entries"])) {
      $list = $list["entries"];

      foreach ($list as $key => $value) {
        $state_key = $value["path_display"];
        if ($value[".tag"] == "folder") {
          $state_value = "unexplored";
        } else {
          $state_value = $value["server_modified"];
        }
        $sync_object->state_remote[$state_key] = $state_value;
      }
    }

    if (isset($sync_object->state_remote[$path])) {
      $sync_object->state_remote[$path] = "is_folder";
    }

  }

  public function check_data($sync_object, $dropbox_utility_object){

    $state_diff_path = $sync_object->status()."/"."state_diff.txt";
    $state_diff = $dropbox_utility_object->file_get_utf8($state_diff_path);
    $state_diff = json_decode($state_diff, true);
    $sync_object->state_diff = $state_diff;

    $state_local = $sync_object->status()."/"."state_local.txt";
    $state_local = $dropbox_utility_object->file_get_utf8($state_local);
    $state_local = json_decode($state_local, true);
    $sync_object->state_local = $state_local;

    $state_remote_path = $sync_object->status()."/"."state_remote.txt";
    $state_remote = $dropbox_utility_object->file_get_utf8($state_remote_path);
    $state_remote = json_decode($state_remote, true);
    $sync_object->state_remote = $state_remote;

  }

}
