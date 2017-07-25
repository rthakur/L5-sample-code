@extends('layouts.front')
@section('title', trans('common.MyProperties'))
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
                    <section id="my-properties">
                        <header><h1>{{ trans('common.MyProperties') }}</h1></header>
                        <div class="my-properties">
                           @if(count($properties))
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('common.Property') }}</th>
                                        <th></th>
                                        <th>{{ trans('common.DateAdded') }}</th>
                                        <th>{{ trans('common.Views') }}</th>
                                        <th>{{ trans('common.Actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($properties as $property)
                                      <tr>
                                          <td class="image">
                                              <a href="{{$property->detailPageURL()}}"><img alt="" src="{{ $property->main_image_url }}"></a>
                                          </td>
                                          <td><div class="inner">
                                              <a href="{{$property->detailPageURL()}}"><h2>{{$property->texts->langSubject()}}</h2></a>
                                              <figure>{{$property->address}}</figure>
                                              <div class="tag price">{{$property->originalPrice()}}</div>
                                          </div>
                                          </td>
                                          <td>{{$property->createdDate()}}</td>
                                          <td>236</td>
                                          <td class="actions">
                                              <a href="{{SITE_LANG}}/property/submit/{{$property->id}}" class="edit"><i class="fa fa-pencil"></i>{{ trans('common.Edit') }}</a>
                                              <a href="{{SITE_LANG}}/property/delete/{{$property->id}}" onclick="return confirm('{{ trans('common.PropertyDeleteConfirm')}}');"><i class="delete fa fa-trash-o"></i></a>
                                          </td>
                                      </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->
                            <!-- Pagination -->
                            <div class="center">
                                {{ $properties->links() }}
                            </div><!-- /.center-->
                           @else
                             @include('includes.no_result')
                           @endif
                        </div><!-- /.my-properties -->
                    </section><!-- /#my-properties -->
                </div><!-- /.col-md-9 -->
                <!-- end My Properties -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>


@endsection
