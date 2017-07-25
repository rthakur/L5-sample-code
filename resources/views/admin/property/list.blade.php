<div class="table-responsive lgResponsive">
  @if(count($properties))
  <table class="table">
    <thead>
      <tr>
        <th>{{ trans('common.Image') }}</th>
        <th>{{ trans('common.Title') }} <i class="fa fa-sort-alpha-{{isset($sort) && isset($sort_type) && $sort_type =='title' ? $sort : 'desc'}} sort" title="sort {{isset($sort) && isset($sort_type) && $sort_type =='title' ? $sort : 'desc'}}" data-field="title"></i></th>
        <th>{{ trans('common.Price') }} <i class="fa fa-sort-amount-{{isset($sort) && isset($sort_type) && $sort_type =='price'  ? $sort : 'desc'}} sort" title="sort {{isset($sort) && isset($sort_type) && $sort_type =='price' ? $sort : 'desc'}}" data-field="price"></i></th>
        <th>{{ trans('common.Agency') }}</th>
        <th>{{ trans('common.Agent') }}</th>
        <th width="100">{{ trans('common.Action') }}</th>
      </tr>
    </thead>
      <tbody class="user">
        @foreach($properties as $property)
        <tr>
          <td>
            <a href="{{$property->detailPageURL()}}">
              <img src="{{ $property->getMainImageUrl() }}" height="100px" alt=" "/>
            </a>
          </td>
          <td>{{$property->texts ? $property->texts->langSubject() : ''}}</td>
          <td>{{$property->originalPrice()}}</td>
          <td>{{ (isset($property->agency)) ? $property->agency->public_name : ' - '}}</td>
          <td>{{(isset($property->agent) && $property->agent->is_agent('id')) ? $property->agent->is_agent('name') : ' - '}}</td>
          <td>
            <a href="{{SITE_LANG}}/property/{{$property->id}}/edit"><i class="fa fa-edit"></i> Edit</a> <br>
            <a class="delete-btn" href="javascript:void(0)" data-item="{{trans('common.property')}}"><i class="fa fa-trash-o"></i> Delete</a>
            <form action="/admin/property/{{$property->id}}" method="post">
              {{csrf_field()}}
              {{ method_field('DELETE') }}
            </form>
            <a href="{{$property->detailPageURL()}}" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i> View </a>
          </td>
        </tr>
        @endforeach
        <tr><td colspan="6" class="paginate-link">{{ $properties->appends(Request::except('page'))->links() }}</td></tr>
      </tbody>

  </table>
  @else
  <hr>
  @include('includes.no_result')
  @endif

</div>
