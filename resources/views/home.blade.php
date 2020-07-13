@extends('layouts.app')

@section('content')


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12" id="fm-main-block">
      <div id="fm"></div>
    </div>
  </div>
</div>

<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // set fm height
  document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');

  // Add callback to file manager
  fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
    window.opener.fmSetLink(fileUrl);
    window.close();
  });
});
</script>

@endsection
