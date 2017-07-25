@if($messages->count())
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th width="80%">{{ trans('common.MessagesDetails') }}</th>
        <th width="10%">{{ trans('common.DateAdded') }}</th>
      </tr>
    </thead>
      <tbody class="user">
      <tr>
        @foreach($messages as $message)
        <tr>
          <td>
            <div>
             <b>Sender Name:</b> {{$message->sender_name}}
            </div>
            <div>
            <b>Sender Email:</b> {{$message->sender_email}}
            </div>
            <div>
            <b>Message:</b><br>
            {!! $message->message_text !!}
            </div>
          </td>
          <td>{{$message->addedDate()}}</td>
        </tr>
        @endforeach
      </tr>
      </tbody>
  </table>
  {{ $messages->links() }}
</div>
@else
<hr>
@include('includes.no_result')
@endif
