<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Settings</title>
      <!-- Normalize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
      <!-- Bootstrap 4 CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css'>
      <!-- Telephone Input CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.2/css/intlTelInput.css'>
      <!-- Icons CSS -->
    <link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
      <!-- Nice Select CSS -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css'>
     <!-- Style CSS -->
    <link rel="stylesheet" href="{{asset('front_assets/installation/css/style.css')}}">
	<!-- Demo CSS -->
	<link rel="stylesheet" href="{{asset('front_assets/installation/css/demo.css')}}">
  
  </head>
  <body>
  
 <main>
  <article>

      <!-- Start Multiform HTML -->
  <section class="multi_step_form">  
  <div id="msform" style="padding-top: 20px !important;"> 
    <!-- Tittle -->
    <div class="tittle">
      <h2>INSTALLATION</h2>
    </div>
      
    <fieldset>
      <p>Database was not configured.<br>Make Sure all the credentials are correct.</p>
      <a href="{{url('/database-setting')}}" class="action-button">Back</a>
    </fieldset>  
  </div>  
</section> 
      <!-- END Multiform HTML -->
  </article>
 </main>
 
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
    <script src="{{asset('front_assets/installation/js/script.js')}}"></script>
  
  </body>
</html>