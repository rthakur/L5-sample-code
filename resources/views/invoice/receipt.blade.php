<!DOCTYPE html>
<html>
<head>
<title>{{ trans('invoice.Invoice') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- //Custom Theme files -->

<!-- Responsive Styles and Valid Styles -->
<style type="text/css">

table {
	font-size: 14px;
	border: 0;
}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  cellpadding="0" cellspacing="0" border="0"  bgcolor="#f7f7f7" width="500">
	<tr>
		<td align="center" style="border-bottom:1px solid #e7e7e8">
			<table cellpadding="0" cellspacing="0" border="0" width="500">
				<tr>
					<td align="center" style="font-size:32px;">
							<strong>MIPARO</strong>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="">
			<table cellpadding="0" cellspacing="0" border="0" width="500">
				<tr>
					<td align="center" style="background-color:#efefef; padding:25px 0 20px;">
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td align="center" style="padding:0 0 10px;font-family:verdana, 'Times New Roman', Times, serif; font-size:22px; color:#1392de;font-weight:bold;">
									<span style="font-size:18px; color:#757575; font-weight:regular; padding:0 5px 0 0;">
										{{ trans('invoice.Invoice') }} :
									</span> 
									{{$invoice->id}} 
								</td>
							</tr>
							<tr>
								<td align="center" style="font-family:Verdana,'Times New Roman', Times, serif; font-size:15px; color:#3f3f3f;font-weight:bold;">
									<span style="font-size:15px; color:#757575; font-weight:regular; padding:0;">
										{{ trans('invoice.Date') }}: 
									</span> 
									{{ $invoice->getFormattedDate() }} 
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#ffffff" style="padding:25px 0 20px;" >
						<table cellpadding="0" cellspacing="0" border="0" width="500">
							<tr>
								<td align="left" style="font-family: Georgia, 'Times New Roman', Times, serif; font-size:30px; color:#1392de;font-style:italic;"> 
									{{ trans('invoice.Customer') }}: </td>
							</tr>
							<tr>
								<td style="color:#757575; font-family: Verdana, Geneva, sans-serif ;">
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td style="font-weight:bold;border-bottom:1px solid #f4f4f4;padding:5px 0;">
												{{ trans('invoice.Companyname') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;">
												{{$agency->public_name}}
											</td>
										</tr>
										<tr>
											<td style="font-weight:bold; border-bottom:1px solid #f4f4f4;padding:5px 0;">
												{{ trans('invoice.BusinessVATNumber') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;">
												{{$agency->vat_number}}
											</td>
										</tr>
										<tr>
											<td style="font-weight:bold; border-bottom:1px solid #f4f4f4;padding:5px 0;">
												{{ trans('invoice.Address') }}:
											</td>
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
											@php
												$getCountry = $agency->getCountry($agency->invoice_country);
											@endphp
											<td align="right" style="border-bottom:1px solid #f4f4f4;"> 
												{{($getCountry)? $getCountry->name_en : $agency->country->name_en}}
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
					<td bgcolor="#ffffff" style="padding:25px 0 20px;">
						<table cellpadding="0" cellspacing="0" border="0" width="500">
							<tr>
								<td align="left" style="font-family: Georgia, 'Times New Roman', Times, serif; font-size:30px; color:#1392de;font-style:italic;">
									{{ trans('invoice.Invoicedetails') }}: 
								</td>
							</tr>
							<tr>
								<td style="color:#757575; font-family: Verdana, Geneva, sans-serif ;">
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td style="font-weight:bold; padding:5px 0; border-bottom:1px solid #f4f4f4;">
												{{ trans('invoice.Paymentperiod') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;"> 
												{{$invoice->getPaymentPeriod()}}
											</td>
										</tr>
										<tr>
											<td style="font-weight:bold; padding:5px 0; border-bottom:1px solid #f4f4f4;">
												{{ trans('invoice.Service') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;">{{trans('invoice.MiParoRealEstateService')}}</td>
										</tr>
										<tr>
											<td style="font-weight:bold; padding:5px 0; border-bottom:1px solid #f4f4f4;">
												{{ trans('invoice.Price') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;">
												{{$invoice->subtotal}} USD
											</td>
										</tr>
										<tr>
											<td style="font-weight:bold; padding:5px 0; border-bottom:1px solid #f4f4f4;">
												{{ trans('invoice.VAT') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;">
												{{$invoice->tax}} USD
											</td>
										</tr>
										<tr>
											<td style="font-weight:bold; padding:5px 0; border-bottom:1px solid #f4f4f4;">
												{{ trans('invoice.Totalpricetopay') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;"> 
												{{$invoice->total}} USD
											</td>
										</tr>
										<tr>
											<td style="font-weight:bold; padding:5px 0;border-bottom:1px solid #f4f4f4;">
												{{ trans('invoice.Paymentstatus') }}:
											</td>
											<td align="right" style="border-bottom:1px solid #f4f4f4;">
												{{ trans('invoice.PayedViaCreditCard') }}
											</td>
										</tr>
										<tr>
											<td style="font-weight:bold; padding:5px 0;"> 
												{{ trans('invoice.VATinformation') }}: 
											</td>
											<td align="right" style="line-height:22px;">
												{{Auth::user()->vatText()}}
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center"  bgcolor="#ebebeb" style="border-top:1px solid #e7e7e8; padding:10px 0">
			<table  cellpadding="0" cellspacing="0" border="0" width="500">
				<tr>
					<td align="center" style="padding:5px 0;  color:#757575; line-height:24px; font-family:Verdana, Geneva, sans-serif;">
						{{ trans('invoice.CancelService') }} 
						<a href="{{url(SITE_LANG.'/login')}}" style="color:#1392de; font-weight:bold;" > 
							{{$agency->info_email}} 
						</a> 
							{{ trans('invoice.DowngradeService') }}
					 </td>
				</tr>
				<tr>
					<td style="border-top:1px solid #d7d7d7;border-bottom:1px solid #f9f9f9;"></td>
				</tr>
				<tr>
					<td style="padding:5px 0;">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0" border="0" width="500">
										<tr>
											<td style="color:#757575; font-family: Verdana, Geneva, sans-serif ;">
												<table cellpadding="0" cellspacing="0" border="0" width="100%">
													<tr>
														<td style="padding:5px 0 10px 10px;color:#1392de; font-family:Georgia, 'Times New Roman', Times, serif; font-style:italic;font-size:24px;">
															{{ trans('invoice.Paymentreciever') }}
														</td>
														<td align="right" style="padding:5px 10px;color:#1392de; font-family:Georgia, 'Times New Roman', Times, serif; font-style:italic;font-size:24px;"> 
															{{ trans('invoice.Contactdetails') }}
														</td>
													</tr>
													<tr>
														<td style="padding:5px 0 10px 10px;">
															MiParo AB <br>
															Rorgatan 4 <br>
															75221 Uppsal <br>
															Sweden 														
														</td>
														<td align="right" style="padding:5px 10px 10px 10px;"> 
															Telephone: +46 735 11 04 24 <br>
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
		</td>
	</tr>
</table>
</body>
</html>