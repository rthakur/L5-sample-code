@foreach($agencies as $agency)
  <div class="agency">
      <div class="wrapper">
          <header><a href="{{$agency->detailPageURL()}}"><h2>{{$agency->public_name}}</h2></a></header>
          <dl>
              <dt>{{ trans('common.Phone') }}:</dt>
              <dd>{!! $agency->formatPhone()?: '&nbsp' !!}</dd>
              <dt>{{ trans('common.Mobile') }}:</dt>
              <dd>{!! $agency->mobile?: '&nbsp' !!}</dd>
              <dt>{{ trans('common.Email') }}:</dt>
              <dd><a href="mailto:{{$agency->info_email}}">{{$agency->info_email}}</a></dd>
              <dt>Skype:</dt>
              <dd>{!! $agency->skype?: '&nbsp' !!}</dd>
          </dl>
          <address>
              <strong>{{ trans('common.Address') }}</strong>
              <br>
              <strong>{{$agency->public_name}}</strong><br>
              {!! $agency->address_line_1?: '&nbsp' !!}<br>
              {!! $agency->address_line_2?: '&nbsp' !!}
          </address>
      </div>
  </div><!-- /.agency -->

@endforeach