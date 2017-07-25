<div class="table-responsive">
    @if(count($syncPropertyRequests))
        <table class="table">
            <thead>
                <tr>
                    <th width="20%">{{ trans('common.Name') }}</th>
                    <th width="25%">{{ trans('common.Email') }}</th>
                    <th width="25%">{{ trans('common.CreatedAt') }}</th>
                    <th width="25%">{{ trans('common.SyncStatus') }}</th>
                </tr>
            </thead>
            
            <tbody> 
                @foreach($syncPropertyRequests as $request)
                    <tr>
                        <td>{{$request->user->name}}</td>
                        <td>{{$request->user->email}}</td>
                        <td>{{$request->created_at}}</td>
                        <td>{{$request->status ? 'Synced' : 'Not Synced'}}</td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>
        
        {!! $syncPropertyRequests->links() !!}
        
    @else
        <hr>
        @include('includes.no_result')
    @endif
</div>