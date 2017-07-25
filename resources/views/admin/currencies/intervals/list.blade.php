@if($intervals->count())
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
          <th>{{ trans('common.Name') }}</th>
          <th>{{ trans('common.Intervals') }}</th>
          <th width="150">{{ trans('common.Action') }}</th>
      </tr>
    </thead>
      <tbody class="user">
        @foreach($intervals as $priceInterval)
            @php $intervalStr = explode(',', $priceInterval->intervals); @endphp
            <tr>
              <td>{{$priceInterval->currency}}</td>
              <td>{{$intervalStr[0] .' - '. $intervalStr[(count($intervalStr) - 1)]}}</td>   
              <td>            
                <a href="{{SITE_LANG}}/admin/intervals/edit/{{$priceInterval->id}}/{{$type}}"><i class="fa fa-pencil"></i> {{ trans('common.Edit') }}</a>
              </td>
            </tr>
        @endforeach
      </tbody>
  </table>
  
  {!! $priceIntervals->render() !!}
</div>
@else
@include('includes.no_result')
@endif