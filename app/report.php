<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class report extends Model
{
  public static function show($GET) {
    $report_object = new report;

    // dd($array);
    $html = $report_object->show_html();
    return $html;
  }

  public function show_html() {

    $report_object = new report;

    $data_items = $report_object->show_array($_GET);

    // echo "<pre>";
    // $data_items = json_encode($data_items, JSON_PRETTY_PRINT);
    // echo $data_items ;
    // exit;
    // dd($data_items);

    if (!empty($data_items)) {
      reset($data_items);
      $data_items_0 = key($data_items);

      $GET = $_GET;
      array_pop($GET);

      $link = "";
      $separator = "?";
      $i = 1;
      foreach ($GET as $key => $value) {
        // echo $key."zzz";
        $link = $link.$separator.$i."=".$value;
        if ($key > 0) {
          $separator = "&";
        }
        $i = $i+1;
      }

      ob_start();
      ?>
      <h1 class="my-3">
        <?php echo $report_object->ends_with($data_items_0, "_report") ?>
      </h1>

      <hr>

      <div class="row">
        <div class="col-md-3">
          <!-- <table  class="rounded border border-secondary w-100" style="border-collapse: separate;"> -->
          <table  class="p-2 rounded w-100" style="border-collapse: separate;">
            <tr>
              <td class="p-2">
                <b>

                  <a href="/<?php echo $link ?>">Back</a>
                </b>
              </td>

            </tr>
          </table>

        </div>
      <?php
      foreach ($data_items[$data_items_0]["content"] as $data_item_key => $data_item_value) {
        // echo $data_item_key;
        // echo "<br>";
        if (is_array($data_item_value)) {
          if ($report_object->ends_with($data_item_key, "_report") == null) {
          } else {


            $GET = $_GET;

            $link = "";
            $separator = "?";
            $i = 1;
            foreach ($GET as $key => $value) {
              // echo $key."zzz";
              $link = $link.$separator.$i."=".$value;
              if ($key > 0) {
                $separator = "&";
              }
              $i = $i+1;
            }

            $link = $link.$separator.$i."=".$data_item_key;
            ?>

              <div class="col-md-3">
                <!-- <table  class="rounded border border-secondary w-100" style="border-collapse: separate;"> -->
                <table  class="p-2 rounded w-100" style="border-collapse: separate;">
                  <tr>
                    <td class="p-2 ">
                      <b>
                        <a href="/<?php echo $link ?>">
                          <?php echo $report_object->ends_with($data_item_key, "_report") ?>
                        </a>
                      </b>
                    </td>

                  </tr>
                </table>

              </div>
            <?php

          }

        }
      }
      ?>
      </div>
      <hr>

      <?php


      $title_html = ob_get_contents();
      ob_end_clean();

      $reportdata_html = "";

      $data_items_0_items = $data_items[$data_items_0];

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
        if ($report_object->ends_with($data_item_key, "_report") == null) {


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
  public static function show_array($GET) {

    if(!function_exists('App\ShowHelper')){
      function ShowHelper($ShowLocation) {
        $report_object = new report;
        $result = array();
        $shallowList = scandir($ShowLocation);

        $size_sum = 0;
        // $result["content"] = array();

        foreach ($shallowList as $key => $value) {

          if (!in_array($value,array(".","..")))  {
            $DataLocation = $ShowLocation . "/" . $value;

            if (is_dir($DataLocation)){
              // $result["content"][$value] = ShowHelper($DataLocation);
              if ($report_object->ends_with($value, "_report") == null) {
                $result["content"][$value] = ShowHelper($DataLocation);

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

    $pub_store = storage_path()."/app/public/";
    $base_report = scandir($pub_store);
    if (isset($base_report[2])) {
      $base_report = $base_report[2];
    } else {
      $base_report = "fake_dir_name";
    }
    // var_dump($base_report);

    $ShowLocation = $pub_store.$base_report.$URI."/";


    if (is_dir($ShowLocation)) {

      $Show =   array(
        basename($ShowLocation) => ShowHelper($ShowLocation)
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

  public function ends_with($string, $test) {
      $strlen = strlen($string);
      $testlen = strlen($test);
      if ($testlen > $strlen ) {
        return null;
      } elseif (substr_compare($string, $test, $strlen - $testlen, $testlen) === 0) {
        $result = str_replace($test, "",$string);
        return $result;
      }
  }



}
