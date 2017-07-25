@extends('layouts.front')
@section('title','500 '.trans('common.Error'))
@section('content')


    <!-- Page Content -->
    <div id="page-content">
        <!-- Breadcrumb -->
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="{{SITE_LANG}}">{{trans('common.Home')}}</a></li>
                <li class="active">500</li>
            </ol>
        </div>
        <!-- end Breadcrumb -->

        <div class="container">
            <section id="500">
                <div class="error-page">
                    
                    @if(env('APP_ENV') ==  'test' || env('APP_ENV') ==  'dev')
                        <div class="title">
                            <img alt="" src="/assets/img/error-page-background.png" class="top">
                            <header>500</header>
                            <img alt="" src="/assets/img/error-page-background.png" class="bottom">
                        </div>
                        <h2 class="no-border">{{trans('common.InternalServerError')}}</h2>
                        <a href="" class="link-arrow back" onclick="history.back(-1)">{{trans('common.GoBack')}}</a>
                    @else
                        <div class="content">
                            <div class="title">Something went wrong.</div>
                            @unless(empty($sentryID))
                                <!-- Sentry JS SDK 2.1.+ required -->
                                <script src="//cdn.ravenjs.com/3.3.0/raven.min.js"></script>

                                <script>
                                Raven.showReportDialog({
                                    eventId: '{{ $sentryID }}',

                                    // use the public DSN (dont include your secret!)
                                    dsn: 'https://117f4c9e44434fcab4398509c8db97d1@sentry.io/165182'
                                });
                                </script>
                            @endunless
                        </div>
                    @endif
                </div>
            </section>
        </div><!-- /.container -->
    </div>
    

@endsection