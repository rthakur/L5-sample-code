<div class="table-responsive">
    @if(count($requests))
        <table class="table">
            <thead>
                <tr>
                    <th width="20%">{{ trans('common.Name') }}</th>
                    <th width="25%">{{ trans('common.Email') }}</th>
                    <th width="40%">{{ trans('common.HomepageUrl') }}</th>
                    <th width="15%">{{ trans('common.Action') }}</th>
                </tr>
            </thead>
            
            <tbody> 
                @foreach($requests as $request)
                    <tr>
                        <td>{{$request->name}} </td>
                        <td>{{$request->email}}</td> 
                        <td>{{$request->homepage_url}}</td>     
                        <td>            
                            @if ($request->syncronize_plan_request == 2)
                                {{ trans('common.Approved') }}
                            @elseif ($request->syncronize_plan_request == 3)
                                {{ trans('common.Dispproved') }}
                            @else
                                <div class="row">
                                    <div class="col-sm-3 text-center">
                                        <a class="btn btn-success btn-sm" href="{{SITE_LANG}}/admin/sync-request/{{$request->user_id}}/2" title="{{ trans('common.Approve') }}">
                                            <i class="fa fa-thumbs-o-up"></i> 
                                        </a>
                                    </div>
                                    <div class="col-sm-3 text-center">
                                        <a class="btn btn-warning btn-sm" href="{{SITE_LANG}}/admin/sync-request/{{$request->user_id}}/3" title="{{ trans('common.Disapprove') }}">
                                            <i class="fa fa-thumbs-o-down"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>
    @else
        <hr>
        @include('includes.no_result')
    @endif
</div>