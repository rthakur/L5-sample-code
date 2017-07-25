@extends('layouts.admin')
@section('content')

<div class="row">
  <div class="col-lg-12">
      <h1>Manage Bookmarklink</h1>
      @include('admin.bookmarklinks.list')
  </div>
</div>
@endsection

@section('extra_scripts')
  <script>
    $(window).load(function(){
      $('.copy-btn').on('click', function(){
        var aux = document.createElement("input");
        elementId = $(this).data('clipboard-target');
        aux.setAttribute("value", document.getElementById(elementId).innerHTML);
        document.body.appendChild(aux);
        aux.select();
        document.execCommand("copy");
        document.body.removeChild(aux);
        $(this).css('color', '#7FCC5B');
        setTimeout(function(){
          _this.css('color', '#0095E5');
        }, 1000);
      })
    });
  </script>
@endsection