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
      <form action="{{url('/database-setting-configure')}}" method="post" >
        @csrf
      <div class="form-group fg_2"> 
        <input type="text" class="form-control" placeholder="Enter Database Host" required name="db_host">
      </div> 
      <div class="form-group fg_2"> 
        <input type="text" class="form-control" placeholder="Enter Database Name" required name="db_database">
      </div> 
      <div class="form-group fg_2"> 
        <input type="text" class="form-control" placeholder="Enter Database Username" required name="db_username">
      </div>
      <div class="form-group fg_2"> 
        <input type="text" class="form-control" placeholder="Enter Database Password" required name="db_password">
      </div>
<!-- 
      <div class="form-group fg_2"> 
        <input type="text" class="form-control" placeholder="Domain Host to be used for Emails" required name="for_emails_host">
      </div>
      <div class="form-group fg_2"> 
        <input type="text" class="form-control" placeholder="Domain Email to be used for Emails" required name="for_emails_email">
      </div>
      <div class="form-group fg_2"> 
        <input type="text" class="form-control" placeholder="Password to be used for Emails" required name="for_emails_password">
      </div> -->
        
      <input type="submit" class="action-button" value="Next"> 
    </form>
    </fieldset>  
  </div>  
</section> 
      <!-- END Multiform HTML -->
  </article>
 </main>
 
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js'></script>
    <script src="{{asset('front_assets/installation/js/script.js')}}"></script>
  
  </body>
</html>