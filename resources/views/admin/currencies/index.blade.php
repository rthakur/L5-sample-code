@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ trans('common.Manage').' '. trans('common.Currencies') }}</h1>
      <div class="col-sm-10 col-sm-offset-1 manage">

          <form action="/admin/currencies/change-order" method="post">
              {{csrf_field()}}
              <input type="hidden" name="orderData">
              <button class="btn btn-default save-order-btn manage-user" disabled=""><i class="fa fa-floppy-o"></i> {{ trans('common.SaveOrder') }}</button>
          </form>

          <table class="table table-responsive">
              <thead>
                  <tr>
                      <th>{{trans('common.Currencies')}}</th>
                      <th width="10%">{{ trans('common.ChangeOrder') }}</th>
                  </tr>
              </thead>
              <tbody id="sortable">
                  @if(count($orderedCurrencies))
                      @foreach($orderedCurrencies as $currency)
                      <tr class="sort-rows" data-id="{{$currency->id}}">
                          <td>{{$currency->currency}}</td>
                          <td><i class="fa fa-sort sort-handle"></i></td>
                      </tr>
                      @endforeach
                  @endif

                  @if(count($unorderedCurrencies))
                      @foreach($unorderedCurrencies as $currency)
                      <tr class="sort-rows" data-id="{{$currency->id}}">
                          <td>{{$currency->currency}}</td>
                          <td><i class="fa fa-sort sort-handle cursor-pointer"></i></td>
                      </tr>
                      @endforeach
                  @endif
              </tbody>
          </table>
      </div>
  </div>
</div>
@endsection

@section('extra_scripts')
    <script>
        $(document).ready(function(){

            @if(Session::has('error'))
                swal('Oops...', "{{Session::get('error')}}", 'error');
            @endif

            $("#sortable").sortable({
                containment: "parent",
                axis: "y",
                items: "tr.sort-rows",
                cursor: "move",
                handle: '.sort-handle',
                update: updateOrder
            });
        });

        function updateOrder(e,ui)
        {
            data = new Array();

            $('.sort-rows').each(function(){
                data.push($(this).data('id'));
            });

            $('[name="orderData"]').val(btoa(JSON.stringify(data)));
            $('.save-order-btn').removeAttr('disabled');
        }

    </script>
@endsection
