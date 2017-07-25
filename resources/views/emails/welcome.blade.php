<!DOCTYPE html>
<html>
<head>
<title>Newsletter</title>
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
					<td align="center" style="padding:40px 0 40px;"><a ><img src="{{ url('/assets/img/logo.png') }}"></a></td>
				</tr>
			</table>
        </td>
	</tr>
	<tr>
		<td align="center" style="padding-bottom:100px; padding-top:70px">
            <table width="600" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="center" style="padding:30px 0 50px;font-family:Georgia, 'Times New Roman', Times, serif; font-size:75px; color:#1392de;font-style:italic;"> {{trans('emails.emailWelcome_welcome')}} </td>
				</tr>
                <tr>
					<td align="center"><img src="{{url('/assets/img/bg.png')}}" alt="..." style="display:block; border:0;"></td>
				</tr>
				<tr>
					<td>
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FFF">
							<tr>
								<td align=:"center" style="padding:20px 0; text-align:center;font-family:Georgia, 'Times New Roman', Times, serif; font-size:30px; color:#1392de;font-style:italic;"> {{trans('emails.emailWelcome_glad')}} </td>
							</tr>
							
							
							<tr>
								<td align=:"center" style="font-size:22px; text-align:center; font-weight:800; line-height:24px; font-family:Verdana, Geneva, sans-serif;color:#757575; padding:20px 40px 15px;">{{trans('emails.emailWelcome_thanks')}}</td>
							</tr>
							<tr>
									<td align="center" style="padding:30px 0 20px">
									<table cellpadding="0" cellspacing="0" border="0" width="295">
										<tr>
											<td align="center" height="53" bgcolor="#1392de" style="border-radius: 5px; color: #ffffff; display: block;"><a href="{{url(SITE_LANG.'/login')}}" style=" text-transform:uppercase;font-size:15px; font-weight: bold; font-family:Arial, sans-serif; text-decoration: none; line-height:52px; width:100%; display:block"><span style="color: #ffffff">{{trans('emails.visitMiparo')}}</span></a></td>
										</tr>
									</table>
									</td>
							</tr>
							<tr>
								<td align=:"center" style="font-size:18px; text-align:center; font-weight:100; line-height:28px; font-style:italic; font-family:Georgia, 'Times New Roman', Times, serif;color:#757575; padding:15px 40px 20px;">{{trans('emails.emailWelcome_connectivity_msg')}}</td>
							</tr>
							<tr>
								<td align="center" style="color:#525252; font-size:16px; font-family:Verdana, Geneva, sans-serif; padding:20px 0 80px">{{trans('emails.emailWelcome_email_label')}} :<a style="color:#1392de; text-decoration:none" > {{$user->email}}</a></td>
							</tr>
							<tr>
								<td align="center" style="color:#525252; font-size:16px; font-family:Verdana, Geneva, sans-serif;">
									<p style="margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;">
										{{trans('emails.email_regards')}}
										<br>{{ config('app.name') }}
									</p>
									<!-- Salutation -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="border-top:1px solid #e7e7e8; padding:80px 0 50px"><table width="600px" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="center" style="padding:10px 0; font-size:18px; color:#2d2e2f; font-weight:700; font-family:Verdana, Geneva, sans-serif; text-transform:uppercase;"> {{trans('emails.emailWelcome_follow_msg')}} </td>
				</tr>
				<tr>
					<td style="padding:30px 0 0;" align="center">
						<table width="160" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td style="padding:0 8px 0;" align="center"><a target="_blank" href="https://www.facebook.com/MiParo-International-250609725400081/" ><img src="{{url('/assets/img/social-icon-2.png')}}"></a></td>
								<td style="padding:0 8px 0;" align="center"><a target="_blank" href="https://twitter.com/MiParo_official"><img src="{{url('/assets/img/social-icon-3.png')}}"></a></td>
								<!-- <td style="padding:0 8px 0;" align="center"><a ><img src="{{url('/assets/img/social-icon-1.png')}}"></a></td> -->
								<td style="padding:0 8px 0;" align="center"><a target="_blank" href="https://www.linkedin.com/company/miparo?trk=company_logo"><img src="{{url('/assets/img/link.png')}}"></a></td>
							</tr>
						</table>
					</td>
				</tr>
				@if($user->receive_newsletter != null)
				<tr>
					<td align="center" style="padding:30px 0; font-size:15px; font-weight:400; font-family:Verdana, Geneva, sans-serif;">
                        {{trans('emails.email_unsubscribe_msg')}} 
                        <a style="color:#1392de; font-weight:600" href="{{url(SITE_LANG.'/account/profile/unsubscribe/'.base64_encode($user->id).'?token='.$user->access_token)}}" target="_blank">{{trans('emails.email_unsubscribe')}}</a>
                    </td>
				</tr>
				@endif
				<!-- Footer -->
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0"  bgcolor="#f7f7f7">
							<tr>
								<td style="color: #AEAEAE; padding: 35px; text-align: center;">
									<p style="margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;">
										&copy; {{ date('Y') }}
										<a style="color: #3869D4;" href="{{ url('/') }}" target="_blank">{{ config('app.name') }}</a>.
										{{trans('emails.email_copyright')}}
									</p>
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