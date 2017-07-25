<form method="post" action="/payment/checkout" id="payment-form">
  <input type="hidden" name="package_id" value="{{$package->id}}">
  {{csrf_field()}}
    <div id="paymentError"></div>
    <div class="col-sm-8 col-sm-offset-2">
          <div class="row">
              <div class="col-sm-12">
                  <p>{{ trans('common.CardNumber') }}  <img src="/assets/img/cc_methods.png" style="width: 40%;"></p>
                  <input type="text" data-mask="9999-9999-9999-9999" maxlength="20" autocomplete="off" class="card-number card-form stripe-sensitive  form-control required" />
              </div>
              <div class="col-sm-12">
                 <div class="row">
                     <div class="col-sm-12">
                       <p>{{ trans('common.Expiration(MM/YYYY)') }}</p>
                     </div>
                     <div class="col-sm-6 col-xs-6">
                         <select class="card-expiry-month stripe-sensitive form-control col-md-3 required">
                           @for($i=1;$i<=12;$i++)
                            <option value="{{ $i }}">{{ sprintf("%'.02d\n", $i) }}</option>
                           @endfor
                         </select>
                     </div>
                     <div class="col-sm-6 col-xs-6">
                         <select class="card-expiry-year stripe-sensitive form-control card-form required">
                           @for($i=0; $i<=12; $i++)
                            <option value="{{date('Y') + $i}}">{{date('Y') + $i}}</option>
                           @endfor
                         </select>
                     </div>
                 </div>
                 <div class="row">
                   <div class="col-sm-12">
                       <p>{{ trans('common.CVCNumber') }}</p>
                       <input type="text" placeholder="" maxlength="4" autocomplete="off" class="card-cvc card-form stripe-sensitive form-control required" />
                   </div>
                 </div>
              </div>
              <div class="col-sm-12">
                 <center>
                     <button type="submit" id="makepayment" name="paymen_mode" value="cc" class="btn btn-success btn-lg btn-block" style="margin-left:0 !important"><i class="fa fa-credit-card"></i> {{ trans('common.PayNow') }} </button>
                     <span class="payment-errors"></span>
                 </center>
               </div>
         </div>
    </div>
</form>
