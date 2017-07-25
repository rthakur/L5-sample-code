@extends('layouts.front')
@section('title', trans('common.BookmarkedProperties'))
@section('content')
    <!-- Page Content -->
    <div id="page-content">

        <div class="container">
            <div class="row">
            <!-- sidebar -->
            @include('profile.sidebar')
            <!-- end Sidebar -->
                <!-- My Properties -->
                <div class="col-md-9 col-sm-10">
                    <section id="bookmarked-properties">
                        <header><h1>{{ trans('common.BookmarkedProperties') }}</h1></header>

                          <div class="row form-group">
                            @php $count = 1; @endphp
                            @if($bookmarkedProperties->count())
                            @foreach($bookmarkedProperties as $property)
                            <div class="col-md-4 property-list-cols">
                                <div class="thumbnail property-list-wrapper">
                                  <a href="{{$property->detailPageURL()}}" class="property-list-thumb-img">
                                      <img alt="" src="{{ $property->getMainImageUrl() }}">
                                  </a>
                                  <div class="caption">
                                      <header class="property-list-name"><p><a href="{{$property->detailPageURL()}}">
                                         {{$property->texts->langSubject()}}
                                      </a></p>
                                    </header>
                                      <hr>
                                      
                                      <div class="row">
                                          <div class="col-xs-6 total-views">
                                              <figure>{{$property->langAddress()}}</figure>
                                          </div>
                                          <div class="col-xs-6">
                                              <span class="property-price pull-right">{{$property->originalPrice()}}</span>
                                          </div>
                                      </div><!--row-->
                                    </div><!--caption-->
                                  </div><!--list-wrapper-->
                                </div>

                              @if($count != count($bookmarkedProperties) && ($count++ % 4) == 0)
                                </div>
                                <div class="row form-group">
                              @endif

                            @endforeach
                            @else
                                <center><p>{{trans('common.NoBookmarkedProperty')}}</p></center>
                            @endif
                          </div>
                      <!-- Pagination -->
                      <div class="center">
                          {{ $bookmarkedProperties->links() }}
                      </div><!-- /.center-->
                    </section><!-- /#my-properties -->
                </div><!-- /.col-md-9 -->
                <!-- end My Properties -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>


@endsection
