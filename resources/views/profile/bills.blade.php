@extends('layouts.front')
@section('title', trans('common.MyInvoices'))
@section('content')
    <!-- Page Content -->
    <div id="page-content">

        <div class="container">
            <div class="row">
            <!-- sidebar -->
            @include('profile.sidebar')
            <!-- end Sidebar -->
                <!-- My Properties -->
                <div class="col-md-9 col-sm-8 myInvoices">
                  <section>
                      <header>
                        <h1>{{ trans('common.MyInvoices') }}</h1>
                      </header>
                      <div class="my-properties">
                        @if(count($invoices))
                          <div class="table-responsive lgResponsive">
                            <table>
                              <tr>
                                <th>No.</th>
                                <th>{{ trans('invoice.Paymentperiod') }}</th>
                                <th>{{ trans('invoice.Price') }}</th>
                                <th>{{ trans('invoice.VAT') }}</th>
                                <th>{{ trans('invoice.Total') }}</th>
                                <th>{{ trans('invoice.Paymentstatus') }}</th>
                                <th></th>
                              </tr>
                              @foreach ($invoices as $key => $invoice)
                                  <tr>
                                      <td>{{++$key.'.'}}</td>
                                      <td>{{ $invoice->getPaymentPeriod() }}</td>
                                      <td>${{ $invoice->subtotal }}</td>
                                      <td>${{ $invoice->tax }}</td>
                                      <td>${{ $invoice->total }}</td>
                                      <td>{{ trans('invoice.PayedViaCreditCard') }}</td>
                                      <td><a href="{{SITE_LANG}}/account/invoice/{{ $invoice->stripe_invoice_id }}">Download</a></td>
                                  </tr>
                              @endforeach
                            </table>
                          </div><!-- /.table-responsive -->
                          @else
                            @include('includes.no_result')
                          @endif
                      </div><!-- /.my-properties -->
                  </section><!-- /#my-properties -->

                </div><!-- /.col-md-9 -->
                <!-- end My Properties -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>


@endsection
