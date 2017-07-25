@if($zones->count())
<div class="table-responsive lgResponsive">
  <table class="table">
    <thead>
      <tr>
        <th>{{trans('common.NorthEastBound')}}</th>
        <th>{{trans('common.SouthWestBound')}}</th>
        <th>{{trans('common.PropertyCount')}}</th>
        <th>{{trans('common.Zoomlevel')}}</th>
        <th>{{trans('common.SummarizeShowUniqueProperties')}}</th>
        <th>{{trans('common.CreatedAt')}}</th>
        <th>{{trans('common.ModifiedAt')}}</th>
        <th>{{ trans('common.Action') }}</th>
      </tr>
    </thead>
      <tbody class="user">
        @foreach($zones as $zone)
        <tr>
          <td><p>{{trans('common.Lat')}}: {{$zone->ne_lat}}<br>{{trans('common.Lng')}}: {{$zone->ne_lng}}</p></td>
          <td><p>{{trans('common.Lat')}}: {{$zone->sw_lat}}<br>{{trans('common.Lng')}}: {{$zone->sw_lng}}</p></td>
          <td><p>{{$zone->property_count}}</p></td>
          <td><p>{{$zone->zoomlevel}}</p></td>
          <td><p>{{$zone->summarize_or_show_unique_properties}}</p></td>
          <td><p>{{$zone->created_at}}</p></td>
          <td><p>{{$zone->updated_at}}</p></td>
          <td>
            <a href="{{SITE_LANG}}/admin/zones/{{$zone->id}}/edit?zoom={{$zone->zoomlevel}}"><i class="fa fa-edit"></i> {{ trans('common.Edit') }}</a> |
            <a class="delete-btn" href="javascript:void(0)" data-item="{{trans('common.zone')}}"><i class="fa fa-trash-o"></i> {{ trans('common.Delete') }}</a>
            <form action="/admin/zones/{{$zone->id}}" method="post">
              {{csrf_field()}}
              {{ method_field('DELETE') }}
            </form>
          </td>
        </tr>
        @endforeach
        <tr><td colspan="6" class="paginate-link">{{ $zones->links() }}</td></tr>
      </tbody>
  </table>
</div>
@else
<hr>
@include('includes.no_result')
@endif
