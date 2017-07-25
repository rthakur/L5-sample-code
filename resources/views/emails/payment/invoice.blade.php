<!DOCTYPE html>
<html>
<head>
    <title>{{trans('emails.Newsletter')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- //Custom Theme files -->

    <!-- Responsive Styles and Valid Styles -->
    <style type="text/css">
    body {
    	width: 100%;
    	margin:0;
    	padding:0;
    	-webkit-font-smoothing: antialiased;
    }
    html {
    	width: 100%;
    }
    table {
    	font-size: 14px;
    	border: 0;
    }
    </style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table width="100%" cellpadding="0" cellspacing="0" border="0"  bgcolor="#f7f7f7">
        <tr>
            <td align="center" style="border-bottom:1px solid #e7e7e8">
                <table width="600px" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="center" style="padding:40px 0 40px;">
                            <a href="javascript:void(0);">
                                <img src="{{ url('/assets/img/logo.png') }}">
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td align="center" style="padding-bottom:100px; padding-top:30px">
                <table width="600" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="center" style="background-color:#efefef; padding:25px 0 20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding:0 0 10px;font-family:verdana, 'Times New Roman', Times, serif; font-size:22px; color:#1392de;font-weight:bold;">
                                        <span style="font-size:18px; color:#757575; font-weight:regular; padding:0 5px 0 0;">
                                            {{trans('invoice.Invoice')}}:
                                        </span> 
                                        {{$payload['data']['object']['id']}} 
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td align="center" style="font-family:Verdana,'Times New Roman', Times, serif; font-size:15px; color:#3f3f3f;font-weight:bold;">
                                        <span style="font-size:15px; color:#757575; font-weight:regular; padding:0;">
                                            {{trans('invoice.Date')}}: 
                                        </span> 
                                        {{ date('Y-m-d', $payload['data']['object']['date']) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                
                    <tr>
                        <td align="center" style="padding:20px 0 0">
                            <img src="{{url('/assets/img/border-bg.png')}}" alt="..." style="display:block; border:0;">
                        </td>
                    </tr>
                
                    <tr>
                        <td bgcolor="#ffffff" style="padding:0 40px 50px; border-bottom:2px dashed #d1d1d1;)">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="left" style="padding:40px 0 20px;font-family: Georgia, 'Times New Roman', Times, serif; font-size:30px; color:#1392de;font-style:italic;"> {{trans('emails.Customer')}}: </td>
                                </tr>
                                
                                <tr>
                                    <td style="color:#757575; font-family: Verdana, Geneva, sans-serif ;">
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">{{ trans('invoice.Companyname') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">{{$user->name}}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">
                                                {{ trans('invoice.BusinessVATNumber') }}:
                                            </td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">{{$agency->vat_number}}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">{{ trans('invoice.Address') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">
                                                    {{$agency->address_line_1 . ($agency->address_line_1 ? ', ' : '') . $agency->address_line_2}}
                                                </td>                                            
                                            </tr>
                                            
                                            <tr>	
                                                <td style="font-weight:bold; border-bottom:1px solid #f4f4f4;padding:5px 0;">
    												{{ trans('invoice.Zipcode') }}:
    											</td>
    											<td align="right" style="border-bottom:1px solid #f4f4f4;">
    												{{($agency->invoice_zip_code)? $agency->invoice_zip_code : $agency->zip_code}}
    											</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold;border-bottom:1px solid #f4f4f4;padding:5px 0;">
                                                    {{ trans('invoice.Country') }}:
                                                </td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;"> 
                                                    {{ trans('countries.'. $agency->country->search_key ) }}
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold;padding:5px 0;">
                                                    {{ trans('invoice.Email') }}:
                                                </td>
                                                <td align="right">
                                                    {{$agency->info_email}}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                
                    <tr>
                        <td bgcolor="#ffffff" style="padding:0 40px 60px;)">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="left" style="padding:50px 0 20px;font-family: Georgia, 'Times New Roman', Times, serif; font-size:30px; color:#1392de;font-style:italic;">{{ trans('invoice.Invoicedetails') }}: </td>
                                </tr>
                                
                                <tr>
                                    <td style="color:#757575; font-family: Verdana, Geneva, sans-serif ;">
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">{{ trans('invoice.Paymentperiod') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">
                                                    {{$invoice->getPaymentPeriod()}}
                                                </td>
                                                
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">{{ trans('invoice.Service') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">{{$payload['data']['object']['lines']['data'][0]['plan']['id']}}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">{{ trans('invoice.Price') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">{{$payload['data']['object']['amount_due']}} {{ strtoupper($payload['data']['object']['currency']) }} </td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">{{ trans('invoice.VAT') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">{{$payload['data']['object']['tax'] ?: 0.00}} {{ strtoupper($payload['data']['object']['currency']) }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0; border-bottom:1px solid #f4f4f4;">{{ trans('invoice.Totalpricetopay') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;"> {{$payload['data']['object']['total']}} {{ strtoupper($payload['data']['object']['currency']) }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0;border-bottom:1px solid #f4f4f4;">{{ trans('invoice.Paymentstatus') }}:</td>
                                                <td align="right" style="border-bottom:1px solid #f4f4f4;">{{ trans('invoice.PayedViaCreditCard') }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-weight:bold; padding:16px 0;"> {{ trans('invoice.VATinformation') }}:  </td>
                                                <td align="right" style="line-height:22px;">
                                                    {{Auth::user()->vatText()}} 
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    
        <tr>
            <td align="center"  bgcolor="#ebebeb" style="border-top:1px solid #e7e7e8; padding:55px 0">
                <table width="600px" cellpadding="0" cellspacing="0" border="0">
                    
                    <tr>
                        <td align="center" style="padding:10px 0 25px;  color:#757575; line-height:24px; font-family:Verdana, Geneva, sans-serif;">
                            {{ trans('invoice.CancelService') }} :
                            <a href="#" style="color:#1392de; font-weight:bold;"> {{$agency->info_email}} </a> 
                            {{ trans('invoice.DowngradeService') }}
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="border-top:1px solid #d7d7d7;border-bottom:1px solid #f9f9f9;"></td>
                    </tr>
                    
                    <tr>
                        <td style="padding:30px 0 0">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding:0 0 25px;color:#1392de; font-family:Georgia, 'Times New Roman', Times, serif; font-style:italic;font-size:24px;">{{ trans('invoice.Paymentreciever') }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="font-size:15px; line-height:24px; color:#757575;font-family:Verdana, Geneva, sans-serif;">
                                                    MiParo AB <br>
                                                    Rorgatan 4 <br>
                                                    75221 Uppsal <br>
                                                    Sweden
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    
                                    <td align="right" valign="top">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="right" style="padding:0 0 25px;color:#1392de; font-family:Georgia, 'Times New Roman', Times, serif; font-style:italic;font-size:24px;">
                                                    {{ trans('invoice.Contactdetails') }}
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="right" style="font-size:15px; line-height:24px; color:#757575;font-family:Verdana, Geneva, sans-serif;"> 
                                                    {{trans('invoice.Telephone')}}: +46 735 11 04 24
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td align="right" style="font-size:15px; line-height:24px; color:#757575;font-family:Verdana, Geneva, sans-serif;"> 
                                                    {{trans('invoice.Email')}}: <a href="#" style="color:#1392de;"> invoice@miparo.com
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
    </table>
</body>
</html>