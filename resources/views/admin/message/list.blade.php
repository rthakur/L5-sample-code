@if($messages->count())
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th width="30%">{{ trans('common.MessageDetails')}}</th>
                <th width="10%">{{ trans('common.ContactedTo')}}</th>
                <th width="10%">{{ trans('common.AgentAgencyName')}}</th>
                <th width="10%">{{ trans('common.CreatedAt')}}</th>
            </tr>
        </thead>
            <tbody class="user">
                @foreach($messages as $message)
                    <tr>
                        <td>
                            <div>
                                <b>{{ trans('common.SenderName')}}:</b> {{$message->sender_name}}
                            </div>
                            <div>
                                <b>{{ trans('common.SenderEmail')}}:</b> {{$message->sender_email}}
                            </div>
                            <div>
                                <b>{{ trans('common.Message')}}:</b><br>
                                {!! $message->message_text !!}
                            </div>
                        </td>
                        <td>{{isset($contactedTo[$message->type])? $contactedTo[$message->type] : $message->type }}</td>
                        <td>
                            {!! $message->typeName() !!}
                        </td>
                        <td>{{$message->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>
    {{ $messages->links() }}
</div>
@else
<hr>
@include('includes.no_result')
@endif
