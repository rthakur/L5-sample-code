@extends('layouts.front')
@section('title','403 '.trans('common.Error'))
@section('content')


    <!-- Page Content -->
    <div id="page-content">
        <!-- Breadcrumb -->
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="{{SITE_LANG}}">{{trans('common.Home')}}</a></li>
                <li class="active">403</li>
            </ol>
        </div>
        <!-- end Breadcrumb -->

        <div class="container">
            <section id="403">
                <div class="error-page">
                    <div class="title">
                        <img alt="" src="/assets/img/error-page-background.png" class="top">
                        <header>403</header>
                        <img alt="" src="/assets/img/error-page-background.png" class="bottom">
                    </div>
                    <h2 class="no-border">Forbidden</h2>
                    <a href="" class="link-arrow back" onclick="history.back(-1)">Go Back</a>
                </div>
            </section>
        </div><!-- /.container -->
    </div>
    

@endsection