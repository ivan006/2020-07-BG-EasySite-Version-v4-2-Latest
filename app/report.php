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



    // $report_object = new report;
    $dropbox_utility_object = new dropbox_utility;

    // $data_items = $report_object->show_array($_GET);

    // echo "<pre>";
    // $data_items = json_encode($data_items, JSON_PRETTY_PRINT);
    // echo $data_items ;
    // exit;
    // dd($data_items);
    // up till here

    if (!empty($data_items)) {


      $first_elements_key = $report_object->first_elements_key($data_items);

      $get_popped = $GET;
      array_pop($get_popped);

      $link = $dropbox_utility_object->get_var_to_link_utils($get_popped)["current_link"];


      ob_start();
      ?>


      <?php


      $title_html = ob_get_contents();
      ob_end_clean();

      $reportdata_html = "";

      $data_items_0_items = $data_items[$first_elements_key];
      // dd($data_items_0_items);

      if (isset($data_items_0_items["content"])) {
        $reportdata_html =  $reportdata_html . $report_object->show_html_helper($data_items_0_items["content"],1,0);
      }


      ob_start();
      ?>
      <!-- <div style="text-align: center;"> -->
      <div style="" class="row">

        <?php echo $reportdata_html; ?>
      </div>

      <?php
      $reportdata_html = ob_get_contents();
      ob_end_clean();

      $page_html = $title_html.$reportdata_html;
      return $page_html;

    }
  }

  public function show_html_helper($data_items, $LayerNumber, $nestlevel_count){
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
        if ($data_item_value['type'] !== "report" AND $data_item_value['type'] !== "section") {


          // reset($data_item_value["content"]);
          // $data_item_value_0 = key($data_item_value["content"]);
          // $supersection_sharespace = "InBl_Wi_50Per";
          // if (isset($data_item_value["content"][$data_item_value_0])) {
          //   $data_item_value_0_value = $data_item_value["content"][$data_item_value_0];
          //   // code...
          //   if (is_array($data_item_value_0_value)) {
          //     $supersection_sharespace = "Wi_100Per";
          //   }
          // }

          $supersection_sharespace = "Wi_100Per";
          $nestlevel_count_new = $nestlevel_count;
          if ($data_item_value["size"] < 250 AND $nestlevel_count < 1) {
            $supersection_sharespace = "col-md-6";
            $nestlevel_count_new = $nestlevel_count+1;
          }



          ob_start();
          ?>
          <div style="" class=" <?php echo $supersection_sharespace ?>  ">
            <h<?php echo $LayerNumber ?> class="" style="margin-top: <?php echo (1/$LayerNumber)*5*16 ?>px;">
              <?php echo $data_item_key; ?>
            </h<?php echo $LayerNumber ?>>

            <!-- <div class="rounded p-2" style="border: solid 1px Gainsboro;"> -->
            <div class="p-2 row" style="border-top: solid 1px Gainsboro; border-bottom: solid 1px Gainsboro;">
              <?php echo $report_object->show_html_helper($data_item_value["content"], $LayerNumber, $nestlevel_count_new) ?>
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

        $key_value_orientation = "Wi_100Per";
        $itemsection_sharespace = "Wi_100Per";
        $is_small_toggle = 0;
        if ($data_item_value["size"] < 150) {

          if ($nestlevel_count < 1) {
            $itemsection_sharespace = "col-md-6";
          }

          $key_value_orientation = "InBl_Wi_50Per";
          $is_small_toggle = 1;

        }

        ?>
        <div style="" class="<?php echo $itemsection_sharespace ?>   BoSi_BoBo">
          <!-- <table  class="rounded border border-secondary w-100" style="border-collapse: separate;"> -->
          <div style="vertical-align:top;" class="<?php echo $key_value_orientation ?>    d-inline-block" >
            <div class="p-2">
              <b>
                <?php echo  preg_replace('/\\.[^.\\s]{3,4}$/', '', $data_item_key); ?>
              </b>
            </div>
          </div>
          <div style="vertical-align:top;" class="<?php echo $key_value_orientation ?>    d-inline-block">
            <div class="p-2">
              <?php
              if ($data_item_value["type"] == "image") {
                $modal_id = preg_replace('/[^a-z0-9]/i', '_', $data_item_key);
                ?>
                <!-- Button to Open the Modal -->
                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo $modal_id ?>">
                  Button
                </button> -->
                <a href="#" data-toggle="modal" data-target="#<?php echo $modal_id ?>">
                  <img style="max-width:150px;" src="/images?1=<?php echo $data_item_value["content"] ?>" alt="">
                </a>

                <!-- The Modal -->
                <div class="modal" id="<?php echo $modal_id ?>">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                      <!-- Modal Header -->
                      <div class="modal-header">
                        <!-- <h4 class="modal-title">Modal Heading</h4> -->
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>

                      <!-- Modal body -->
                      <div class="modal-body" >
                        <!-- Modal body.. -->
                        <div class="" style="text-align: center;">

                          <img style="max-width:100%;" src="/images?1=<?php echo $data_item_value["content"] ?>" alt="">
                        </div>
                      </div>

                      <!-- Modal footer -->
                      <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div> -->

                    </div>
                  </div>
                </div>

                <?php
              } else {
                if ($is_small_toggle == 0) {
                  echo "<pre style='white-space: pre-wrap;'>";
                  echo $data_item_value["content"];
                  echo "</pre>";
                } else {
                  echo $data_item_value["content"];
                }
              }


              ?>


            </div>
          </div>
        </div>
        <?php
        // $itemsection_sharespace
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

  public static function ShowHelper($report_object, $ShowLocation) {
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

          $datatype_report_status = $report_object->datatype_status('_report', $value);

          $datatype_section_status = $report_object->datatype_status('_section_global', $value);

          if ($datatype_report_status == 0 AND $datatype_section_status == 0) {
            $result["content"][$value] = $report_object->ShowHelper($report_object, $DataLocation);
            $result["content"][$value]["type"] = "dir";

          } else {

            if ($datatype_report_status == 1) {
              $result["content"][$value]["content"] = array();
              $result["content"][$value]["size"] = 0;
              $result["content"][$value]["type"] = "report";
            } elseif ($datatype_section_status == 1) {
              $result["content"][$value]["content"] = array();
              $result["content"][$value]["size"] = 0;
              $result["content"][$value]["type"] = "section";
            }

          }
        } else {
          $this_object = new report;

          $result["content"][$value] = $this_object->read_file_attr($DataLocation);

        }

        $size_sum = $size_sum+$result["content"][$value]["size"];
      }
    }
    // $result["type"] = "dir";

    $result["size"] = $size_sum;

    return  $result;
  }



  // public static function show($ShowID) {

  public static function show_array($report_object, $GET) {

    $result_inner = array();
    // if(!function_exists('App\ShowHelper')){
    //
    // }


    // $ShowLocation = PostM::ShowLocation($ShowID);
    // $ShowLocation = base_path()."/storage/app/public/";

    $breadcrumb_array = $GET;
    $page_folder_path = $report_object->page_folder_path($report_object, $breadcrumb_array);


    if (is_dir($page_folder_path)) {
      $result_inner = $report_object->ShowHelper($report_object, $page_folder_path);
    }

    $result = array(
      basename($page_folder_path) => $result_inner
    );
    // dd($result);
    return $result;
  }

  public static function section_global_items($report_object) {

    $result_inner = array();

    $breadcrumb_array = array("_section_global");
    $page_folder_path = $report_object->page_folder_path($report_object, $breadcrumb_array);


    if (is_dir($page_folder_path)) {
      $result_inner = $report_object->ShowHelper($report_object, $page_folder_path);
    }

    $result = array(
      basename($page_folder_path) => $result_inner
    );
    // dd($result);
    return $result;
  }

  public function page_folder_path($report_object, $breadcrumb_array) {
    $URI = "";
    if (!empty($breadcrumb_array)) {
      foreach ($breadcrumb_array as $key => $value) {
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

    $result = $pub_store."/".$base_report.$URI."/";
    return $result;
  }



  public function read_file_attr($DataLocation) {

    // $result = file_get_contents($DataLocation);

    if (file_exists($DataLocation)){
      if (mime_content_type($DataLocation) == "image/jpeg") {
        $result["content"] = str_replace(storage_path(), "", $DataLocation);
        // $result = $DataLocation;
        // $type = pathinfo($result, PATHINFO_EXTENSION);
        // $result = file_get_contents($result);
        // $result = 'data:image/' . $type . ';base64,' . base64_encode($result);
        $result["type"] = "image";
        $result["size"] = 149;


      } elseif (mime_content_type($DataLocation) == "text/plain" OR mime_content_type($DataLocation) == "text/html") {

        $result["content"] = file_get_contents($DataLocation);
        $result["type"] = "txt";

        $content_no_js = preg_replace(
          '/<script\b[^>]*>(.*?)<\/script>/is',
          "",
          $result["content"]
        );
        $result["size"] = strlen($content_no_js);
      } else {

        $result["content"] = "error dont support this: ".mime_content_type($DataLocation);
        $result["type"] = "other";
        $result["size"] = strlen($result["content"]);
      }

    } else {
      $result["content"] = "error";
      $result["type"] = "none";
      $result["size"] = strlen($result["content"]);

    }

    return $result;
  }

  public function report_suffix_remove($string) {
    $result = str_replace("_report", "",$string);
    return $result;
  }

  public function datatype_status($test, $string) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    $result = 0;
    if ($testlen <= $strlen ) {
      if (substr_compare($string, $test, $strlen - $testlen, $testlen) === 0) {
        $result = 1;
      }

    }
    return $result;
  }


  public function title_and_menu($report_object, $GET, $dropbox_utility_object){

    $data_items = $report_object->show_array($report_object, $GET);

    $result = array();
    $first_elements_key = $report_object->first_elements_key($data_items);
    $first_element_value = $data_items[$first_elements_key];

    $title = $report_object->report_suffix_remove($first_elements_key);


    $menu_items = array();

    $get_popped = $GET;
    array_pop($get_popped);
    $back_link = $dropbox_utility_object->get_var_to_link_utils($get_popped)["current_link"];
    $menu_items["Back"] = $back_link;

    foreach ($first_element_value["content"] as $key => $value) {
      if (is_array($value)) {
        if ($value['type'] == "report") {


          $link_utils = $dropbox_utility_object->get_var_to_link_utils($GET);
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

  public function first_elements_key($array){
    reset($array);
    $result = key($array);
    return $result;
  }

  public function last_update(){
    $cmd = 'git log --pretty="%ci" -n1 HEAD';
    $cmd_dir = storage_path()."/../";
    exec(
      "cd $cmd_dir;
      $cmd 2>&1"
      , $result
    );
    return $result;
  }




}
