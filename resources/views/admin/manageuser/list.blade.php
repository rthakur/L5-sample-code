
@if(count($users))
<div class="table-responsive lgResponsive">
        <table class="table">

            <thead>
                <tr>
                    <th width="25%">{{ trans('common.Name') }} <i class="fa fa-sort-alpha-{{isset($sort) && isset($sort_type) && $sort_type =='name' ? $sort : 'desc'}} sort" title="sort {{isset($sort) && isset($sort_type) && $sort_type =='name' ? $sort : 'desc'}}" data-field="name"></i></th>
                    <th width="25%">{{ trans('common.Email') }} <i class="fa fa-sort-alpha-{{isset($sort) && isset($sort_type) && $sort_type =='email' ? $sort : 'desc'}} sort" title="sort {{isset($sort) && isset($sort_type) && $sort_type =='email' ? $sort : 'desc'}}" data-field="email"></i></th>
                    <th width="15%">{{ trans('common.Role') }}</th>
                    <th width="15%">{{ trans('common.CreatedAt') }}</th>
                    <th width="150">{{ trans('common.Action') }}</th>
                </tr>
            </thead>

            <tbody class="user">
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->name}} </td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->rolename}}</td>
                        <td>{{date('m-d-Y',strtotime($user->created_at))}}</td>
                        <td class="text-center">
                            <a href="{{SITE_LANG}}/admin/manageuser/{{$user->id}}/edit"><i class="fa fa-pencil"></i> {{ trans('common.Edit') }}</a>
                            <br>
                            <a class="user-delete-btn" href="javascript:void(0)" data-role="{{$user->rolename}}" data-item="{{trans('common.user')}}"><i class="fa fa-trash-o"></i> Delete</a>
                            <br>
                            <a href="{{SITE_LANG}}/auth/accessaccount/{{$user->id}}"><i class="fa fa-key"></i> Access Account</a>
                            <form action="/admin/manageuser/{{$user->id}}" method="post">
                                {{csrf_field()}}
                                {{ method_field('DELETE') }}
                            </form>
                            <br>
                        </td>
                    </tr>
                @endforeach

                <tr><td colspan="5" class="paginate-link">{!! $users->appends(Request::except('page'))->render() !!}</td></tr>
            </tbody>
        </table>
</div>
@else
    <hr>
    @include('includes.no_result')
@endif
