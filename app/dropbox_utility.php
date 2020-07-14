<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dropbox_utility extends Model
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

  public function authenticate(){
    $result = 0;
    $dropbox_utility = new dropbox_utility;
    $userpwd = "";
    $userpwd = $dropbox_utility->apikey()["dropbox_userpwd"];
    // $token = "";
    // $token = env('DROPBOX_TOKEN');

    $raw_data = file_get_contents('php://input');
    if ($raw_data) {
      $json = json_decode($raw_data);
      if (is_object($json)) {
        if (isset($json->list_folder)) {
          $headers = $dropbox_utility->getallheaders();
          if (hash_hmac("sha256", $raw_data, $userpwd['password']) == $headers['X-Dropbox-Signature']) {
            $result = 1;
          }
        }
      }
    }
    return $result;
  }

  public function getallheaders(){
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

  // public function curl_get($endpoint,$userpwd){
    //
    // $ch = @curl_init();
    // if (!empty($userpwd)) {
    //   curl_setopt($ch, CURLOPT_USERPWD, $userpwd['username'] . ":" . $userpwd['password']);
    // }
    //
    // @curl_setopt($ch, CURLOPT_URL, $endpoint);
    // @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    // 'Accept: application/json',
    // 'Content-Type: application/json'
    // ));
    // @curl_setopt($ch, CURLOPT_HEADER, 0);
    // @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //
    // $response = @curl_exec($ch);
    // $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // $curl_errors = curl_error($ch);
    //
    // @curl_close($ch);
    //
    //
    // $response = json_encode(json_decode($response, true),JSON_PRETTY_PRINT);
    // return $response;
  // }

  public function dropbox_file_contents($path, $dropbox_utility){
    $file_content = $dropbox_utility->get_from_dropbox($path, $dropbox_utility, "files/get_temporary_link");
    if (isset($file_content["link"])){
      $file_content = $file_content["link"];
      $file_content = file_get_contents($file_content);
    } else {
      $file_content = "";
    }
    $result = $file_content;

    return $result;
  }

  public function get_from_dropbox($path, $dropbox_utility, $url_suffix){
    $dropbox_utility = new dropbox_utility;
    $body = array(
    "path" => $path,
    );
    $body = json_encode($body);

    // $url_suffix = "files/get_metadata";

    $userpwd = "";
    // $userpwd = $dropbox_utility->apikey()["dropbox_userpwd"];

    $token = "";
    $token = env('DROPBOX_TOKEN');

    $endpoint = 'https://api.dropboxapi.com/2/'.$url_suffix;


    $result = $dropbox_utility->curl_post($body,$endpoint,$userpwd,$token);

    $result = json_decode($result, true);
    return $result;
  }

}