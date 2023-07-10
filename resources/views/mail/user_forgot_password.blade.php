<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Ranking Checker</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <meta name="Version" content="v3.5.0" />
        <!-- favicon -->
        <link rel="shortcut icon" href="images/favicon.ico">
        <!-- Font -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
    </head>

    <body style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400;">
        <!-- Loader -->
        <!-- <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <div class="double-bounce1"></div>
                    <div class="double-bounce2"></div>
                </div>
            </div>
        </div> -->
        <!-- Loader -->

        <!-- Hero Start -->
        <div style="margin-top: 50px;">
            <table cellpadding="0" cellspacing="0" style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400; max-width: 600px; border: none; margin: 0 auto; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
                <thead>
                    <tr style="background-color: black; padding: 3px 0; line-height: 68px; text-align: center; color: #fff; font-size: 24px; font-weight: 700; letter-spacing: 1px;">
                        <th scope="col"><img src="{{asset('/project_images'.$logo)}}"  height="60" alt="" style="padding-top: 15px;"></th>
                    </tr>
                </thead>

                <tbody>
                    
                    <tr>
                        <td style="padding: 15px 24px 15px; color: black !important;">
                        Hello <br>
                       We have received your password change request.
                         <br>
                         Use the button below to reset your password...
                        <br>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 15px 24px;">
                            <a href="{{url('/user/forgot_password_code/'.$random)}}" target="blank" style="padding: 8px 20px; outline: none; text-decoration: none; font-size: 16px; letter-spacing: 0.5px; transition: all 0.3s; font-weight: 600; border-radius: 6px; background-color: #2f55d4; border: 1px solid #2f55d4; color: #ffffff;">Reset Password</a>
                        </td>
                    </tr>


                    <tr>
                        <td style="padding: 15px 24px 15px; color: #8492a6;">
                            Regards <br>                             Rank Checker
                     </td>
                    </tr>

                    <tr>
                        <td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
                            © <script>document.write(new Date().getFullYear())</script> Rank Checker.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Hero End -->
    </body>
</html>