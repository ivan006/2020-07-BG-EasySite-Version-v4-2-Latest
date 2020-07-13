<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class update extends Model
{

  public function apikey(){
    $result = array(
      "dropbox_token" => env('DROPBOX_TOKEN'),
      "dropbox_userpwd" => array(
        "username" => "z3o9nmtmd0ikqf4",
        "password" => "ntibchtud5z4lmr",
      ),
    );
    return $result;
  }

  public function update_updates_pending_log(){
    $update_object = new update;
    $file_name = "updates_pending_log.txt";

    if (isset($_GET['challenge'])) {
      $result = $_GET['challenge'];

      $timestamp = date('Y-m-d h:i:s a', time());
      file_put_contents(
        $file_name,
        "ready"." ".$timestamp
      );

      return $result;
    } elseif ($update_object->authenticate() == 1) {
      file_put_contents($file_name, "yes");
    } else {
      header('HTTP/1.0 403 Forbidden');
      file_put_contents($file_name, "not_ready");
    }
  }

  public function authenticate(){
    $result = 0;
    $update_object = new update;
    $userpwd = "";
    $userpwd = $update_object->apikey()["dropbox_userpwd"];
    // $token = "";
    // $token = env('DROPBOX_TOKEN');

    $raw_data = file_get_contents('php://input');
    if ($raw_data) {
      $json = json_decode($raw_data);
      if (is_object($json)) {
        if (isset($json->list_folder)) {
          $headers = $update_object->getallheaders();
          if (hash_hmac("sha256", $raw_data, $userpwd['password']) == $headers['X-Dropbox-Signature']) {
            $result = 1;
          }
        }
      }
    }
    return $result;
  }

  function getallheaders(){
    $headers = array();
    foreach ($_SERVER as $name => $value)  {
      if (substr($name, 0, 5) == 'HTTP_') {

        $name = substr($name, 5);
        $name = str_replace('_', ' ', $name);
        $name = strtolower($name);
        $name = ucwords($name);
        $name = str_replace(' ', '-', $name);

        $headers[$name] = $value;

      }
    }
    return $headers;
  }

  public function file_get_utf8($path){
    $result = file_get_contents($path);
    $result = utf8_encode($result);
    return $result;
  }


  public function update_updates_processing_log(){
    $update_object = new update;

    $updates_processing_log = $update_object->file_get_utf8("updates_pending_log.txt");
    // $updates_processing_log = json_decode($updates_processing_log, true);

    $timestamp = date('Y-m-d h:i:s a', time());
    file_put_contents(
      "updates_processing_log.txt",
      $updates_processing_log." ".$timestamp
    );


  }

  public function state_raw(){
    $update_object = new update;
    $path = "";

    $result = $update_object->state_raw_helper($path, "", $update_object);


    return $result;
  }

  public function state_raw_helper($path, $called, $update_object){
    $result = $update_object->get_from_dropbox($path, $update_object, "files/list_folder");


    if (isset($result["entries"])) {
      $result = $result["entries"];

      $called = "";

      if (isset($result)) {
        foreach ($result as $key => $entry) {

          if ($entry['.tag'] == "folder") {
            $sub_result = $update_object->state_raw_helper($entry['path_display'], $called, $update_object);
            $result[$key]["child_content"] = $sub_result;
          } else {
            $result[$key]["child_content"] = "";
          }
        }
      }
    }
    return $result;
  }

  public function file_contents($path, $update_object){
    $file_content = $update_object->get_from_dropbox($path, $update_object, "files/get_temporary_link");
    if (isset($file_content["link"])){
      $file_content = $file_content["link"];
      $file_content = file_get_contents($file_content);
    } else {
      $file_content = "";
    }
    $result = $file_content;

    return $result;
  }

  public function get_from_dropbox($path, $update_object, $url_suffix){
    $update_object = new update;
    $body = array(
    "path" => $path,
    );
    $body = json_encode($body);

    // $url_suffix = "files/get_metadata";

    $userpwd = "";
    // $userpwd = $update_object->apikey()["dropbox_userpwd"];

    $token = "";
    $token = env('DROPBOX_TOKEN');

    $endpoint = 'https://api.dropboxapi.com/2/'.$url_suffix;


    $result = $update_object->curl_post($body,$endpoint,$userpwd,$token);

    $result = json_decode($result, true);
    return $result;
  }

  public function curl_post($body, $endpoint, $userpwd, $token){

    $ch = curl_init();

    // set URL and other appropriate options
    $options = array(
    CURLOPT_URL => $endpoint,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    ),
    );
    if (!empty($userpwd)) {
      $options[CURLOPT_USERPWD] = $userpwd['username'] . ":" . $userpwd['password'];
      // $options[CURLOPT_HTTPHEADER][] = "Authorization: Basic <base64(".$userpwd['username'].":".$userpwd['password'].")>";
    } elseif (!empty($token)) {
      $options[CURLOPT_HTTPHEADER][] = "Authorization: Bearer $token";
    }

    // dd($options);

    curl_setopt_array($ch, $options);



    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    // echo "<pre>";
    $result = json_encode(json_decode($result, true),JSON_PRETTY_PRINT);
    // echo $result;
    return $result;



  }

  public function curl_get($endpoint,$userpwd){


    $ch = @curl_init();
    if (!empty($userpwd)) {
      curl_setopt($ch, CURLOPT_USERPWD, $userpwd['username'] . ":" . $userpwd['password']);
    }

    @curl_setopt($ch, CURLOPT_URL, $endpoint);
    @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: application/json',
    'Content-Type: application/json'
    ));
    @curl_setopt($ch, CURLOPT_HEADER, 0);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = @curl_exec($ch);
    $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_errors = curl_error($ch);

    @curl_close($ch);


    $response = json_encode(json_decode($response, true),JSON_PRETTY_PRINT);
    return $response;


  }



  public function state($update_object){

    $state_raw = $update_object->state_raw();
    $result = $update_object->state_helper($state_raw, $update_object);

    return $result;
  }

  public function state_helper($state_raw, $update_object){
    $result = array();
    if (is_array($state_raw)) {
      foreach ($state_raw as $key => $value) {
        if (isset($value[".tag"]) and isset($value['path_display'])) {
          $name = $value["path_display"];
          // $name = str_replace("\\", "", $name);
          if ($value[".tag"] == "folder") {
            $result[$name] = 0;
            $result = array_merge($result, $update_object->state_helper($value["child_content"], $update_object));
          } else {
            $result[$name] = $value["server_modified"];
          }
        }
      }
    }
    return $result;
  }

  public function diff_level_1($update_object){

    $old_state = $update_object->file_get_utf8("updates_completed_log.txt");
    $old_state = json_decode($old_state, true);


    $state = $update_object->state_raw();
    $state = $update_object->state_helper($state, $update_object);

    $result["remove"] = array_diff_assoc($old_state, $state);
    $result["add"] = array_diff_assoc($state, $old_state);

    return $result;
  }

  public function diff_level_2($update_object){

    $diff_level_1 = $update_object->diff_level_1($update_object);

    $result["remove"] = $update_object->diff_level_2_helper("remove",$diff_level_1);
    $result["add"] = $update_object->diff_level_2_helper("add",$diff_level_1);

    // $result["remove"] = array_diff_assoc($old_state, $state);
    // $result["add"] = array_diff_assoc($state, $old_state);

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
