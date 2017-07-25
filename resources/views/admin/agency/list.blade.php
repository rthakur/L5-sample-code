@if(count($agencies))
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th width="20%">{{ trans('common.Logo') }} <i class="fa fa-sort-alpha-{{isset($sort) ? $sort : 'desc'}} sort" title="sort {{isset($sort) ? $sort : 'desc'}}"></th>
        <th width="40%">{{ trans('common.Details') }}</th>
        <th>{{ trans('common.CreatedAt') }} </th>
        <th>{{ trans('common.Action') }}</th>
      </tr>
    </thead>
      <tbody class="user">
        @foreach($agencies as $agency)
        <tr>
          <td>
            <img src="{{$agency->logo}}" alt="{{$agency->public_name}}" height="50px"/>
          </td>
          <td>
            <p><b>{{ trans('common.PublicName') }} :</b> {{$agency->public_name}}</p>
            <p><b>{{ trans('common.RealEstateCompanyName') }} :</b> {{$agency->legal_companyname}}</p>
            <p><b>{{ trans('common.Email') }} :</b> {{$agency->info_email}}</p>
          </td>
          <td>{{date('m-d-Y',strtotime($agency->created_at))}}</td>
          <td>
            <a href="{{SITE_LANG}}/admin/agency/{{$agency->id}}/edit"><i class="fa fa-edit"></i> {{ trans('common.Edit') }}</a> |
            <a class="delete-btn" href="javascript:void(0)" data-item="{{trans('common.Agency')}}"><i class="fa fa-trash-o"></i> {{ trans('common.Delete') }}</a>
            <form action="/admin/agency/{{$agency->id}}" method="post">
              {{csrf_field()}}
              {{ method_field('DELETE') }}
            </form>
          </td>
        </tr>
        @endforeach
        <tr><td colspan="4" class="paginate-link">{{$agencies->appends(Request::except('page'))->links()}}</td></tr>
      </tbody>
  </table>
</div>
@else
<hr>
    @include('includes.no_result')
@endif
