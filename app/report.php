<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\dropbox_utility;
use App\sync;

class report extends Model
{
  public function repo_path() {
    $result = storage_path() . "/app/public";
    return $result;
  }

  // public static function show($GET) {
  //   $report_object = new report;
  //
  //   // dd($array);
  //   $html = $report_object->show_html();
  //   return $html;
  // }

  public function show_html($report_object, $data_items, $GET) {

    $report_object = new report;

    // $data_items = $report_object->show_array($_GET);

    // echo "<pre>";
    // $data_items = json_encode($data_items, JSON_PRETTY_PRINT);
    // echo $data_items ;
    // exit;
    // dd($data_items);

    if (!empty($data_items)) {


      $first_elements_key = $report_object->first_elements_key($data_items);

      $get_popped = $GET;
      array_pop($get_popped);

      $link = $report_object->get_var_to_link_utils($get_popped)["current_link"];


      ob_start();
      ?>


      <?php


      $title_html = ob_get_contents();
      ob_end_clean();

      $reportdata_html = "";

      $data_items_0_items = $data_items[$first_elements_key];

      $reportdata_html =  $reportdata_html . $report_object->show_html_helper($data_items_0_items["content"],1,0);


      ob_start();
      ?>
      <!-- <div style="text-align: center;"> -->
      <div style="">

        <?php echo $reportdata_html; ?>
      </div>

      <?php
      $reportdata_html = ob_get_contents();
      ob_end_clean();

      $page_html = $title_html.$reportdata_html;
      return $page_html;

    }
  }

  public function show_html_helper($data_items, $LayerNumber, $restrict_width_count){
    $report_object = new report;

    $LayerNumber = $LayerNumber+1;

    $data_items_field_type = array();
    $data_items_item_type = array();

    $result_part_2 = "";

    // if (!isset($data_items["content"])) {
    //   dd($data_items);
    // }
    foreach ($data_items as $data_item_key => $data_item_value) {
      // echo $data_item_key;
      // echo "<br>";

      if (is_array($data_item_value["content"])) {
        if ($report_object->report_suffix_exists($data_item_key) == 0) {


          // reset($data_item_value["content"]);
          // $data_item_value_0 = key($data_item_value["content"]);
          // $restrict_width_toggle = "InBl_Wi_50Per";
          // if (isset($data_item_value["content"][$data_item_value_0])) {
          //   $data_item_value_0_value = $data_item_value["content"][$data_item_value_0];
          //   // code...
          //   if (is_array($data_item_value_0_value)) {
          //     $restrict_width_toggle = "Wi_100Per";
          //   }
          // }

          $restrict_width_toggle = "Wi_100Per";
          $restrict_width_count_new = $restrict_width_count;
          if ($data_item_value["size"] < 250 AND $restrict_width_count < 1) {
            $restrict_width_toggle = "InBl_Wi_50Per";
            $restrict_width_count_new = $restrict_width_count+1;
          }



          ob_start();
          ?>
          <div class=" <?php echo $restrict_width_toggle ?> d-inline-block ">
            <h<?php echo $LayerNumber ?> class="" style="margin-top: <?php echo (1/$LayerNumber)*5*16 ?>px;">
              <?php echo $data_item_key; ?>
            </h<?php echo $LayerNumber ?>>

            <div class="rounded p-2" style="border: solid 1px Gainsboro;">
              <?php echo $report_object->show_html_helper($data_item_value["content"],$LayerNumber,$restrict_width_count_new) ?>
            </div>


          </div>
          <?php

          $result_part_2 = $result_part_2.ob_get_contents();

          ob_end_clean();
        }

      }
    }


    $result_part_1_loose_files = "";
    foreach ($data_items as $data_item_key => $data_item_value) {
      if (!is_array($data_item_value["content"])){

        ob_start();

        $key_value_width = "Wi_100Per";
        $restrict_width_toggle = "Wi_100Per";
        $is_small_toggle = 0;
        if ($data_item_value["size"] < 50 AND $restrict_width_count < 1) {
          $restrict_width_toggle = "InBl_Wi_50Per";

          $key_value_width = "InBl_Wi_50Per";
          $is_small_toggle = 1;

        }
        ?>
        <div class="<?php echo $restrict_width_toggle ?>  d-inline-block BoSi_BoBo">
          <!-- <table  class="rounded border border-secondary w-100" style="border-collapse: separate;"> -->
          <div class="<?php echo $key_value_width ?>  d-inline-block " >
            <div class="p-2">
              <b>
                <?php echo $data_item_key; ?>
              </b>
            </div>
          </div>
          <div class="<?php echo $key_value_width ?>  d-inline-block ">
            <div class="p-2">
              <?php
              if ($is_small_toggle == 0) {
                echo "<pre>";
                echo $data_item_value["content"];
                echo "</pre>";
              } else {
                echo $data_item_value["content"];
              }
              ?>


            </div>
          </div>
        </div>
        <?php
        // $restrict_width_toggle
        $result_part_1_loose_files = $result_part_1_loose_files.ob_get_contents();

        ob_end_clean();

      }
    }



    ob_start();
    ?>
    <!-- <div class="Di_Fl Fl_Wr"> -->
      <?php echo $result_part_1_loose_files; ?>
    <!-- </div> -->
    <?php
    $result_part_1_loose_files = ob_get_contents();
    ob_end_clean();



    ob_start();
    ?>
    <!-- <div class="Di_Fl Fl_Wr"> -->
      <?php echo $result_part_2; ?>
    <!-- </div> -->
    <?php
    $result_part_2 = ob_get_contents();
    ob_end_clean();



    $result = $result_part_1_loose_files.$result_part_2;
    return $result;
  }

  // public static function show($ShowID) {
  public static function show_array($report_object, $GET) {

    if(!function_exists('App\ShowHelper')){
      function ShowHelper($report_object, $ShowLocation) {
        // $report_object = new report;
        $result = array();
        $shallowList = scandir($ShowLocation);

        $size_sum = 0;
        // $result["content"] = array();

        foreach ($shallowList as $key => $value) {

          if (!in_array($value,array(".","..")))  {
            $DataLocation = $ShowLocation . "/" . $value;

            if (is_dir($DataLocation)){
              // $result["content"][$value] = ShowHelper($report_object, $DataLocation);
              if ($report_object->report_suffix_exists($value) == 0) {
                $result["content"][$value] = ShowHelper($report_object, $DataLocation);

              } else {
                $result["content"][$value]["content"] = array();
                $result["content"][$value]["size"] = 0;
              }
            } else {
              $this_object = new report;
              $result["content"][$value]["content"] = $this_object->read_file($DataLocation);
              $result["content"][$value]["size"] = strlen($result["content"][$value]["content"]);

            }

            $size_sum = $size_sum+$result["content"][$value]["size"];
          }
        }

        $result["size"] = $size_sum;

        return  $result;
      }
    }


    // $ShowLocation = PostM::ShowLocation($ShowID);
    // $ShowLocation = base_path()."/storage/app/public/";
    $URI = "";
    if (!empty($GET)) {
      foreach ($GET as $key => $value) {
        $URI = $URI."/".$value;
      }
    }

    $pub_store = $report_object->repo_path();
    $base_report = scandir($pub_store);
    if (isset($base_report[2])) {
      $base_report = $base_report[2];
    } else {
      $base_report = "fake_dir_name";
    }
    // var_dump($base_report);

    $ShowLocation = $pub_store."/".$base_report.$URI."/";


    if (is_dir($ShowLocation)) {

      $Show =   array(
        basename($ShowLocation) => ShowHelper($report_object, $ShowLocation)
      );

      return $Show;
    }
  }


  public function read_file($DataLocation) {

    // $result = file_get_contents($DataLocation);

    if (file_exists($DataLocation)){
      if (mime_content_type($DataLocation) == "image/jpeg") {
        $result = "";
        // $result = $DataLocation;
        // $type = pathinfo($result, PATHINFO_EXTENSION);
        // $result = file_get_contents($result);
        // $result = 'data:image/' . $type . ';base64,' . base64_encode($result);


      } elseif (mime_content_type($DataLocation) == "text/plain" OR mime_content_type($DataLocation) == "text/html") {

        $result = file_get_contents($DataLocation);
      } else {
        $result = "error dont support this: ".mime_content_type($DataLocation);
      }
      return $result;
    } else {
      $result = "error";

      return $result;
    }

  }

  public function report_suffix_remove($string) {
    $result = str_replace("_report", "",$string);
    return $result;
  }

  public function report_suffix_exists($string) {
    $test = "_report";
    $strlen = strlen($string);
    $testlen = strlen($test);
    $result = 0;
    if ($testlen < $strlen ) {
      if (substr_compare($string, $test, $strlen - $testlen, $testlen) === 0) {
        $result = 1;
      }

    }
    return $result;
  }


  public function title_and_menu($report_object, $data_items, $GET){

    $result = array();
    $first_elements_key = $report_object->first_elements_key($data_items);
    $first_element_value = $data_items[$first_elements_key];

    $title = $report_object->report_suffix_remove($first_elements_key);


    $menu_items = array();

    $get_popped = $GET;
    array_pop($get_popped);
    $back_link = $report_object->get_var_to_link_utils($get_popped)["current_link"];
    $menu_items["Back"] = $back_link;

    foreach ($first_element_value["content"] as $key => $value) {
      if (is_array($value)) {
        if ($report_object->report_suffix_exists($key) !== 0) {


          $link_utils = $report_object->get_var_to_link_utils($GET);
          $current_link = $link_utils["current_link"];
          $slug_sep = $link_utils["slug_sep"];
          $slug_id = $link_utils["slug_id"];

          $link = $current_link.$slug_sep.$slug_id."=".$key;
          $name = $report_object->report_suffix_remove($key);
          $menu_items[$name] = $link;
        }
      }
    }

    $dropbox_utility_object = new dropbox_utility;
    $in_sync = "";

    $sync_object = new sync;
    $process_queue_path = $sync_object->status()."/"."process_queue.txt";
    $process_queue = $dropbox_utility_object->file_get_utf8($process_queue_path);
    $process_queue = preg_replace('/\s+/', '', $process_queue);

    if ($process_queue == "occupied") {
      $in_sync = "No";
    } elseif ($process_queue == "vacant") {
      $in_sync = "Yes";
    }

    $result = array(
      "title" => $title,
      "menu_items" => $menu_items,
      "in_sync" => $in_sync,
    );
    return $result;
  }

  public function get_var_to_link_utils($array){

    $slug_sep = "?";
    $slug_id = 1;
    $link = "";
    foreach ($array as $key => $value) {
      $link = $link.$slug_sep.$slug_id."=".$value;
      if ($key > 0) {
        $slug_sep = "&";
      }
      $slug_id = $slug_id+1;
    }
    $result = array(
      "slug_sep" => $slug_sep,
      "slug_id" => $slug_id,
      "current_link" => $link,
    );
    return $result;
  }

  public function first_elements_key($array){
    reset($array);
    $result = key($array);
    return $result;
  }




}
