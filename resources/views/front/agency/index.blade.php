@extends('layouts.front')
@section('title','Agencies')
@section('content')
    <!-- Page Content -->
    <div id="page-content">

        <div class="container">
            <div class="row">
                <!-- Agent Detail -->
                <div class="col-md-9 col-sm-9">
                    <section id="agencies-listing">
                        <header><h1>{{ trans('common.Agencies') }}</h1></header>
                        
                        @include('front.agency.list')
                        
                        <!-- Pagination -->
                        <div class="center">
                            {{$agencies->links()}}
                        </div><!-- /.center-->
                        <!-- /.pagination-->
                    </section><!-- /#agencies-listing -->
                </div><!-- /.col-md-9 -->
                <!-- end Agent Detail -->

                <!-- sidebar -->
                @include('includes.sidebar.right_sidebar')
                <!-- end Sidebar -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
    

@endsection