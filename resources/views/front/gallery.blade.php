@extends('layouts.front')
@section('title', trans('common.Gallery'))
@section('content')
<div class="home-bottom-section gallery-container">
  <section id="grid-view" data-ajax-request="0" data-page-number="1" data-site-lang="{{SITE_LANG}}"></section>
  <a class="scroll-top-btn"><img src="/assets/img/scroll_up.png"></a>
  <div class="ajax-loader" style="top:200px;left:20px;"><center><img src="/assets/img/ajax-loader.svg"></center></div>
</div>
@endsection

@section('extra_scripts')
<script type="text/javascript" src="/assets/js/grid-view.js"></script>
<script>
$(document).ready(function() {

  map = null;
  getGridData(true);
  var column_breakpoint = 700;
  var columns = 3;

  screenWidth = $(window).width();

  if(screenWidth <= 991)
    var  column_breakpoint = 400, columns = 2;

  if(screenWidth <= 800)
    var column_breakpoint = 700,  columns = 1;

  if(screenWidth <= 480)
    var column_breakpoint = 400,  columns = 1;

  if(screenWidth >= 2634)
    var column_breakpoint = 700,  columns = 3;

  responsiveGridData(columns,column_breakpoint)

  function responsiveGridData(columns,column_breakpoint)
  {
      $('#grid-view').grid_view({
          no_columns: columns,
          padding_x: 10,
          padding_y: 10,
          margin_bottom: 50,
          single_column_breakpoint: column_breakpoint
      });
  }

  $(window).on('touchmove', function() {
   if ($(window).scrollTop() + $(window).height() > $(document).height() - 500)
      getGridData(false);

   if ($(this).scrollTop() > 600)
       $('.scroll-top-btn').fadeIn();
   else
       $('.scroll-top-btn').fadeOut();
  });

  $(window).on('scroll', function() {
   if ($(window).scrollTop() + $(window).height() > $(document).height() - 500)
      getGridData(false);

   if ($(this).scrollTop() > 600)
       $('.scroll-top-btn').fadeIn();
   else
       $('.scroll-top-btn').fadeOut();
  });


  $('.scroll-top-btn').click(function () {
    $("html, body").animate({
      scrollTop: 0
    }, 600);
    return false;
  });

});
</script>
@endsection
