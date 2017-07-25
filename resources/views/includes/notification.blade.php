@if(Session::has('warning'))
    <div class="alert alert-warning">
        {!! Session::get('warning') !!}
    </div>    
@endif

@if(Session::has('success'))
    
    <div class="alert alert-success">
        {!! Session::get('success') !!}
    </div>
    
@endif

@if(isset($message))
    <div class="alert alert-warning">
        {!! $message !!}
    </div>    
@endif