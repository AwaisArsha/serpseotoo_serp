<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <!--[if mso]>
    <xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
    <style>
      td,th,div,p,a,h1,h2,h3,h4,h5,h6 {font-family: "Segoe UI", sans-serif; mso-line-height-rule: exactly;}
    </style>
  <![endif]-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700" rel="stylesheet" media="screen">
    <style>
        .hover-underline:hover {
            text-decoration: underline !important;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes ping {

            75%,
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        @keyframes pulse {
            50% {
                opacity: .5;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(-25%);
                animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
            }

            50% {
                transform: none;
                animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
            }
        }

        @media (max-width: 600px) {
            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }

            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }

            .sm-w-full {
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; width: 100%; word-break: break-word; -webkit-font-smoothing: antialiased; background-color:black !important;">
    <div role="article" aria-roledescription="email" aria-label="" lang="en">
        <table style="font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; width: 100%; background-color:black !important;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr style="background-color:black !important;">
                <td align="center" style=" font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                    <table class="sm-w-full" style="font-family: 'Montserrat',Arial,sans-serif; width: 600px;" width="600" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td class="sm-py-32 sm-px-24" style="font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; padding: 48px; text-align: center;" align="center">
                                <a href="{{url('/')}}">
                                    <img src="{{asset('/project_images'.$logo)}}" width="155" style="border: 0; max-width: 100%; line-height: 100%; vertical-align: middle;">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="sm-px-24" style="font-family: 'Montserrat',Arial,sans-serif;">
                                <table style="font-family: 'Montserrat',Arial,sans-serif; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="sm-px-24" style="--bg-opacity: 1; background-color: #ffffff; border-radius: 4px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; line-height: 24px; padding: 48px; text-align: left;" align="left">
                                            <p style="font-weight: 600; font-size: 18px; margin-bottom: 0;">Hello {{$name}},</p>
                                            <p style="margin: 0 0 24px;">
                                            We have received your request to activate the trial pack. Click the button below to activate your sample pack...
                                            </p>
                                            <table align="center" style="font-family: 'Montserrat',Arial,sans-serif; margin-left: auto; margin-right: auto; text-align: center; width: 100%;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td align="right" style="font-family: 'Montserrat',Arial,sans-serif;">
                                                        <table style="font-family: 'Montserrat',Arial,sans-serif; margin-top: 24px; margin-bottom: 24px;" cellpadding="0" cellspacing="0" role="presentation">
                                                            <tr>
                                                                <td align="right" style=" border-radius: 4px;  font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                                                                    <a href="{{url('/trial/activate/'.$rand)}}" style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; font-weight: 600; border-radius: 6px; background-color: #2f55d4; border: 1px solid #2f55d4; color: #ffffff;">Visit Site &rarr;</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p style="font-size: 14px; line-height: 24px; margin-top: 6px; margin-bottom: 20px;">
                                                Regards,
                                                <br>
                                                © <script>
                                                    document.write(new Date().getFullYear())
                                                </script> Rank Checker.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; height: 20px;" height="20"></td>
                        </tr>
                        <tr>
                            <td style="font-family: 'Montserrat',Arial,sans-serif; height: 16px;" height="16"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>