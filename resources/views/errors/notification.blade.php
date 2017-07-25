@if(Session::has('notification'))    
<div class="alert alert-danger">
  {{ Session::get('notification') }}
</div>
@endif

@if(isset($error))
    
    <div class="alert alert-danger">
        <strong> {{ $error }} </strong>
    </div>
    
@elseif(isset($success))

    <div class="alert alert-success">
        <strong> {!! $success !!} </strong>
    </div>
    
@endif