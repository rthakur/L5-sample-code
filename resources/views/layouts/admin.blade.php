<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="MiParo">
    <link href='//fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
    <link href="/assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/bootstrap-select.min.css" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/jquery.slider.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/admin-theme.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/admin-style.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/common.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/sweetalert2.min.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/jquery-ui.min.css" type="text/css">
    @yield('extra-style')
    <title>{{Config::get('app.name')}} |  @yield('title', Config::get('app.name').' Admin')</title>
</head>
<body>
<div id="wrapper" class="main-container">
  @include('includes.admin.sidebar')
  @include('includes.admin.topnavbar')
  <!-- Page Content -->
  <div id="page-content-wrapper">
      <div class="container-fluid">
          @yield('content')
      </div>
  </div>
  <!-- /#page-content-wrapper -->
</div>
<script>
geoLocationErrorMessage = "{{ trans('common.GeoLocationError')}}";
deleteConfirmMessage = "{{ trans('common.DeleteConfirm')}}"; ///Are you sure?
deleteConfirmPermission = "{{ trans('common.DeleteConfirmPermission')}}";//You won't be able to revert this!
deleteCancelMessage = "{{ trans('common.DeletionCancelled')}}";
SendEmailConfirmPermission = "{{ trans('common.SendEmailConfirmPermission')}}";//You want to send email
ConfirmationSubscriptionMsg = "{{ trans('common.ConfirmationSubscriptionMsg')}}";//You have already an email to this agnecy
confirmButton = "{{ trans('common.confirmButton')}}";//Yes
Cancel = "{{ trans('common.Cancel')}}";
questionMark = "{{trans('common.QuestionMark')}}";
AlreadySent = "{{ trans('common.AlreadySent')}}";//"You have already sent an email to this agency"
Cancelled = "{{trans('common.Cancelled')}}";
Success = "{{trans('common.Success')}}";
MailSent = "{{trans('common.MailSent')}}";
Success = "{{trans('common.Success')}}";
deleteConfirmText = "{{trans('common.DeleteThis')}}";
ChangeLogo = "{{trans('common.ChangeLogo')}}";
AddLogo = "{{trans('common.AddLogo')}}";
</script>
<script type="text/javascript" src="/assets/js/jquery-2.1.0.min.js"></script>
<script
  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
  integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
  crossorigin="anonymous"></script>
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=AIzaSyDqwBpO43kJG912mPVbUl0xcHoWesmliBw&libraries=places"></script>
<script type="text/javascript" src="/assets/js/custom-map.js"></script>
<script type="text/javascript" src="/assets/js/markerwithlabel_packed.js"></script>
<script type="text/javascript" src="/assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/sweetalert2.min.js"></script>
<script type="text/javascript" src="/assets/js/admin.common.js"></script>
<script type="text/javascript" src="/assets/js/jquery.form.min.js"></script>
<script type="text/javascript" src="/assets/js/common.js"></script>
<script type="text/javascript" src="/assets/js/jquery.placeholder.js"></script>
<!--Extra scripts not included yet-->
<!--
<script type="text/javascript" src="/assets/js/smoothscroll.js"></script>
<script type="text/javascript" src="/assets/js/infobox.js"></script>
<script type="text/javascript" src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/js/icheck.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.vanillabox-0.1.5.min.js"></script>
-->
@yield('extra_scripts')
<script type="text/javascript">
  $('select').select2();
</script>
</body>
</html>
