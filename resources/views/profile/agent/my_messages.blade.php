@extends('layouts.front')
@section('title',trans('common.MyMessages'))
@section('content')
    <!-- Page Content -->
    <div id="page-content">
        <div class="container">
            <div class="row">
            <!-- sidebar -->
            @include('profile.sidebar')
            <!-- end Sidebar -->
                <!-- My Properties -->
                <div class="col-md-9 col-sm-8">
                    <section id="my-properties">
                        <div class="col-sm-12 col-md-8 messages-header">
                            <header><h1><i class="glyph-icon flaticon-multimedia"></i> {{ trans('common.MyMessages') }}</h1></header>
                        </div>
                        <div class="my-properties">
                            <div class="table-responsive my-messages">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('common.MessagesDetails') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($messages as $message)
                                      <tr>
                                        <td>
                                            <button class="btn btn-danger pull-right delete-btn" href="{{SITE_LANG}}/account/message/delete/{{$message->id}}" data-item="{{trans('common.message')}}">
                                                <span class="glyphicon glyphicon-trash"></span> {{ trans('common.Delete') }}
                                            </button>

                                            <p><label class="messages-labels">{{ trans('common.SenderName') }}&nbsp:&nbsp</label> {{$message->sender_name}} </p>
                                            <p><label class="messages-labels">{{ trans('common.SenderEmail') }}&nbsp:&nbsp</label> {{$message->sender_email}}</p>
                                            <p><label class="messages-labels">{{ trans('common.SenderMessage') }}&nbsp:&nbsp</label>{!! $message->message_text !!}</p>
                                            <p><label class="messages-labels">{{ trans('common.DateAdded') }}&nbsp:&nbsp</label> {{$message->addedDate()}}</p>
                                        </td>
                                      </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if(!count($messages))
                                    <h3 class="text-center"> {{trans('common.NoDataFound')}} </h3>
                                @endif
                            </div><!-- /.table-responsive -->
                            {{ $messages->links() }}
                        </div><!-- /.my-properties -->
                    </section><!-- /#my-properties -->
                </div><!-- /.col-md-9 -->
                <!-- end My Properties -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>

@endsection
