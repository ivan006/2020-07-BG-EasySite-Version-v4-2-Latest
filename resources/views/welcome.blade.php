<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>
    <?php
    if (isset($title_and_menu["title"])) {
      echo $title_and_menu["title"];
    }
    ?>
  </title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">

  <!-- Styles -->
  <style>
    html, body {
      /* background-color: #fff;
      color: #636b6f;
      font-family: 'Nunito', sans-serif;
      font-weight: 200;
      height: 100vh;
      margin: 0; */
    }

    .full-height {
      height: 100vh;
    }

    .flex-center {
      align-items: center;
      display: flex;
      justify-content: center;
    }

    .position-ref {
      position: relative;
    }

    .top-right {
      position: absolute;
      right: 10px;
      top: 18px;
    }

    .content {
      /* text-align: center; */
    }

    .title {
      font-size: 84px;
    }

    .links > a {
      color: #636b6f;
      padding: 0 25px;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: .1rem;
      text-decoration: none;
      text-transform: uppercase;
    }

    .m-b-md {
      margin-bottom: 30px;
    }
  </style>

  <style media="screen">


    @media (min-width: 768px) {
      .container-large {
        width: 970px;
      }
      .container-small {
        width: 360px;
      }
    }
    @media (min-width: 992px) {
      .container-large {
        width: 1170px;
      }
      .container-small {
        width: 560px;
      }
    }
    @media (min-width: 1200px) {
      .container-large {
        width: 1500px;
      }
      .container-small {
        width: 760px;
      }
    }

    .container-small, .container-large {
      max-width: 100%;
    }

    .Pa_50px {padding: 50px;}
    .Wi_800px {width: 800px;}
    .Wi_400px {width: 400px;}
    .InBl_Wi_400px {width: 397px;}

    .Wi_100Per {width: 100%;}
    .InBl_Wi_50Per {width: calc(50% - 3px);}

    .BoSi_BoBo {box-sizing: border-box;}
  </style>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body class="bg-light">

  <!-- <div class="flex-center position-ref full-height"> -->
    <!-- <div class="flex-center position-ref "> -->
      <div class=" ">

        @if (Route::has('login'))
        <div class="top-right links">
          @auth
          <a href="{{ url('/home') }}">Home</a>
          @else
          <a href="{{ route('login') }}">Login</a>

          @if (Route::has('register'))
          <a href="{{ route('register') }}">Register</a>
          @endif
          @endauth
        </div>
        @endif
        <div class="content">



          <div class="container container-small bg-white Pa_50px rounded my-2">
            <h1 class="my-3">
              <?php
              echo $title_and_menu["title"];
              ?>
            </h1>
            <div class="">
              Synced: <?php echo $title_and_menu["updated"];?> (syncing threads: <span id="feedback_box">0</span>)
            </div>

            <script type="text/javascript">
            function fetchdata(){
              $.ajax({
                url: '/processing',
                type: 'get',
                success: function(response){
                  var thread_count = $('#feedback_box').text();
                  $('#feedback_box').text(parseFloat(thread_count)+1);

                }
              });
            }

            $(document).ready(function(){
              setInterval(fetchdata,5000);
            });
            </script>
            <hr>

            <div class="row">

              <?php
              foreach ($title_and_menu["menu_items"] as $key => $value) {
                ?>

                <div class="col-md-3">
                  <!-- <table  class="rounded border border-secondary w-100" style="border-collapse: separate;"> -->
                    <table  class="p-2 rounded w-100" style="border-collapse: separate;">
                      <tr>
                        <td class="p-2 ">
                          <b>
                            <a href="/<?php echo $value ?>">
                              <?php echo $key ?>
                            </a>
                          </b>
                        </td>

                      </tr>
                    </table>

                  </div>
                  <?php
                }
                ?>
              </div>
              <hr>
              <?php echo $body ?>
            </div>
          </div>
        </div>

      </body>
      </html>
