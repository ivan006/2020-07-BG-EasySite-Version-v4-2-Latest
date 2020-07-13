<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

  <!-- Styles -->

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">

  <style media="screen">


  @media (min-width: 768px) {
    .container-small {
      width: 300px;
    }
    .container-large {
      width: 970px;
    }
  }
  @media (min-width: 992px) {
    .container-small {
      width: 500px;
    }
    .container-large {
      width: 1170px;
    }
  }
  @media (min-width: 1200px) {
    .container-small {
      width: 700px;
    }
    .container-large {
      width: 1500px;
    }
  }

  .container-small, .container-large {
    max-width: 100%;
  }
  </style>


</head>
<body class="bg-light">


  <div class="container container-small bg-white p-2 rounded my-2">
    <?php echo $result ?>


  </div>

</body>
</html>
