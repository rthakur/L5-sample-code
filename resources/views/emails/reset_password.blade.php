<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
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

<?php

$style = [
    /* Layout ------------------------------ */

    'body' => 'margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;',
    'email-wrapper' => 'width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;',

    /* Masthead ----------------------- */

    'email-masthead' => 'padding: 25px 0; text-align: center;',
    'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',

    'email-body' => 'width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;',
    'email-body_inner' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0;',
    'email-body_cell' => 'padding: 35px;',

    'email-footer' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
    'email-footer_cell' => 'color: #AEAEAE; padding: 35px; text-align: center;',

    /* Body ------------------------------ */

    'body_action' => 'width: 100%; margin: 30px auto; padding: 0; text-align: center;',
    'body_sub' => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2;',

    /* Type ------------------------------ */

    'anchor' => 'color: #3869D4;',
    // 'header-1' => 'margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;',
    'header-1' => 'font-size:26px; font-weight:700; font-family:Verdana, Geneva, sans-serif;color:#2d2e2f; padding:0 40px 20px;',
    'line-1'   => 'font-size:14px; font-weight:600; line-height:24px; font-family:Verdana, Geneva, sans-serif;;color:#2d2e2f; padding:0 40px 20px;',
    'line-2'   => 'font-size:14px; font-weight:600; line-height:24px; font-family:Verdana, Geneva, sans-serif;;color:#2d2e2f; padding:20px 40px 60px;',
    'line-3'   => 'font-size:14px; border-top:4px solid #f7f7f7; font-weight:400; line-height:24px; font-family:Verdana, Geneva, sans-serif;color:#2d2e2f; padding:30px 40px 10px;',
    'paragraph' => 'margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;',
    'paragraph-sub' => 'margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;',
    'paragraph-center' => 'text-align: center;',

    /* Buttons ------------------------------ */

    'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',

    'button--green' => 'background-color: #22BC66;',
    'button--red' => 'background-color: #dc4d2f;',
    'button--blue' => 'background-color: #3869D4;',
];
?>

<?php $fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;'; ?>

<body style="{{ $style['body'] }}">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $style['email-wrapper'] }}" align="center">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <!-- Logo -->
                    <tr>
                        <td align="center" style="padding:40px 0 40px;">
                            <a  href="{{ url('/') }}" target="_blank">
                                <img src="{{url('/assets/img/logo.png')}}">
                            </a>
                        </td>
                    </tr>

                    <!-- Email Body -->
                    <tr>
                        <td style="{{ $style['email-body'] }}" width="100%">
                            <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}">
                                        <!-- Greeting -->
                                        <h1 style="{{ $style['header-1'] }}">
                                            @if (! empty($greeting))
                                                {{ $greeting }}
                                            @else
                                                @if ($level == 'error')
                                                    {{trans('common.email_whoops')}}
                                                @else
                                                    {{trans('emails.email_hello')}}
                                                @endif
                                            @endif
                                        </h1>

                                        <!-- Intro -->
                                        <!-- @foreach ($introLines as $line) -->
                                            <p style="{{ $style['line-1'] }}">
                                                    {{ trans('emails.emailForgotPwrdLine1') }}
                                            </p>
                                        <!-- @endforeach -->

                                        <!-- Action Button -->
                                        @if (isset($actionText))
                                            <table style="{{ $style['body_action'] }}" align="center" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td align="center" style="padding:10px 0 30px">
                                                        <table cellpadding="0" cellspacing="0" border="0" width="295">
                                                        <?php
                                                            switch ($level) {
                                                                case 'success':
                                                                    $actionColor = 'button--green';
                                                                    break;
                                                                case 'error':
                                                                    $actionColor = 'button--red';
                                                                    break;
                                                                default:
                                                                    $actionColor = 'button--blue';
                                                            }
                                                        ?>
                                                        <tr>
                											<td align="center" height="53" bgcolor="#1392de" style="border-radius: 5px; color: #ffffff; display: block;">
                                                                <a href="{{ $actionUrl }}" target="_blank" style=" text-transform:uppercase;font-size:15px; font-weight: bold; font-family:Arial, sans-serif; text-decoration: none; line-height:52px; width:100%; display:block">
                                                                    <span style="color: #ffffff">{{trans('emails.resetPassword')}}</span>
                                                                </a>
                                                            </td>
                										</tr>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif

                                        <!-- Outro -->
                                        <!-- @foreach ($outroLines as $line) -->
                                        <p style="{{ $style['line-2'] }}">
                                                {{ trans('emails.emailForgotPwrdLine2') }}
                                        </p>
                                        
                                        <!-- @endforeach -->
                                        
                                        <table style="{{ $style['body_action'] }}" align="center" width="100%" cellpadding="0" cellspacing="0">
                							<tr>
                								<td align=:"center" style="font-size:18px; text-align:center; font-weight:100; line-height:28px; font-style:italic; font-family:Georgia, 'Times New Roman', Times, serif;color:#757575; padding:15px 40px 20px;">{{trans('emails.emailWelcome_connectivity_msg')}}</td>
                							</tr>
                                        </table>
                                        
                                        <p style="{{ $style['line-3'] }}">
                                                {{ trans('emails.emailForgotPwrdLine3') }}
                                        </p>

                                        <!-- Sub Copy -->
                                        @if (isset($actionText))
                                            <table style="{{ $style['body_sub'] }}">
                                                <tr>
                                                    <td style="padding:10px 40px 30px;">
                                                        <a style="font-size:14px; line-height:24px; color:#32a0e2; font-weight:600; font-family:Verdana, Geneva, sans-serif; display:block;  text-decoration:none; word-wrap:break-word; width: 500px;" href="{{ $actionUrl }}" target="_blank"> 
                                                            {{ $actionUrl }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="border-top:1px solid #e7e7e8">
                                        <table width="600" cellpadding="0" cellspacing="0" border="0">
                                            @if($user->role_id =='2')
                                            <tr>
                                                <td align="center" style="padding:50px 0 80px; font-size:15px; font-weight:400; font-family:Verdana, Geneva, sans-serif;">
                                                    {{trans('emails.email_unsubscribe_msg')}} 
                                                    <a style="color:#1392de; font-weight:600" href="javascript:void(0);">
                                                        {{trans('emails.email_unsubscribe')}}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
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
                    <!-- Footer -->
                    <tr>
                		<td align="center" style="border-top:1px solid #e7e7e8; padding:80px 0 50px">
                			<table width="600px" cellpadding="0" cellspacing="0" border="0">
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
                    <!-- Footer -->
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
