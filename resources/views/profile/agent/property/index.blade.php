@extends('layouts.front')
@section('title', trans('common.MyProperties'))
@section('content')
    <!-- Page Content -->
<div id="page-content">
    <div class="container">
        @include('includes.notification')
        <div class="row">

            <!-- sidebar -->
            @include('profile.sidebar')
            <!-- end Sidebar -->

            <!-- My Properties -->
            <div class="col-md-9 col-sm-10">
                <section id="my-properties">

                    <header>
                        @if(Auth::user()->checkAllowToAddProperty())
                            <a class="btn btn-default pull-right green-btn" href="{{SITE_LANG}}/property/submit">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                {{ trans('common.AddNewProperty') }}
                            </a>
                        @endif

                        <h1>{{ (Request::segment('5') == 'new') ? trans('common.NewPropertiestoVerify') : trans('common.MyProperties') }}</h1>
                    </header>

                    <div class="my-properties">
                        @include(count($properties) ? 'profile.agent.property.list' : 'includes.no_result')
                    </div><!-- /.my-properties -->

                </section><!-- /#my-properties -->
            </div><!-- /.col-md-9 -->
            <!-- end My Properties -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</div>

@endsection

@section('script_without_select2')
    @if(Session::has('error'))
        <script>
            swal('Oops...', "{{Session::get('error')}}", 'error');
        </script>
    @endif
@endsection
