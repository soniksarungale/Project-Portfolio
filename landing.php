<?php
$hashed='';
$_SESSION['token'] = microtime();

if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $salt = '$2y$11$' . substr(md5(uniqid(mt_rand(), true)), 0, 22);
    $hashed = crypt($_SESSION['token'], $salt);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Project portfolio/</title>
  <link rel="stylesheet" href="files/css/master.css">
  <link rel="stylesheet" href="files/css/landing.css">
  <link rel="stylesheet" href="files/css/loader.css">
</head>
<body>
  <section id="landing">
    <div class="landing-text">
      <div class="text-holder">
        <h1 class="logo-color">Project<span>Portfolio</span></h1>
        <p>is a application which allow you to upload your project over internet without worrying about domain and hosting.</p>
      </div>
    </div>
    <div class="landing-forms">
      <div class="form">
        <div class="login-form">
            <div class="heading">Login to your <b>Account</b></div>
            <div class="input-box">
                <form action="" method="post">
                  <div class="error error-warning"></div>
                  <div class="sucess sucess-alert"></div>
                    <input type="text" class="input" id="lemail" placeholder="Your Email address..." required>
                    <input type="password" class="input" id="lpass" placeholder="Your password..." required>
                    <input type="button" onclick="loginAccount()" class="submit" value="login">
                </form>
            </div>
            <br>
            Don't have account? <button type="button" id="showCreateBtn" class="togglebtn" onclick="show_create()">Create Account</button> Now.
        </div>
        <div class="signup-form">
            <div class="heading">Create your <b>Account</b></div>
            <div class="input-box">
                <form action="" method="post">
                  <div class="error error-warning"></div>
                  <div class="sucess sucess-alert"></div>
                    <input type="text" class="input" id="cemail" placeholder="New Email address..." required>
                    <input type="password" class="input" id="cpassword" placeholder="New password..." required>
                    <input type="password" class="input" id="crpassword" placeholder="Repeat New password..." required>
                    <input type="button" onclick="createAccount()" class="submit" value="Create account">
                </form>
            </div>
            <br>
            Already an member ? <button type="button" id="showCreateBtn" class="togglebtn" onclick="show_login()">Login</button> Now.
        </div>
      </div>
    </div>
  </section>
  <div class="loader">
                        <div class="preloader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="files/js/master.js"></script>
  <script type="text/javascript">
    function show_create(){
      $(".login-form").css("display","none");
      $(".signup-form").fadeIn();
    }
    function show_login(){
      $(".signup-form").css("display","none");
      $(".login-form").fadeIn();
    }
    function loginAccount() {
      var pass = $.trim($("#lpass").val());
      var email = $.trim($("#lemail").val());
      if(email == "" || pass == ""){
         $(".login-form .error").css("display","block");
         $(".login-form .error").html("All fields required");
          if(email == ""){
             $("#lemail").css("border-color","red");
          }
          else{
              $("#lemail").css("border-color","rgba(0, 0, 0, 0.075)");
          }
          if(pass == ""){
             $("#lpass").css("border-color","red");
          }
          else{
              $("#lpass").css("border-color","rgba(0, 0, 0, 0.075)");
          }
          return false;
      }
      $(".loader").fadeIn();
      var session = "<?php echo $hashed;?>";
      $.post("files/controller/login.php" , {
          email: email,
          pass: pass,
          usession: session
      }).done(
      function(result){
        var json = $.parseJSON(result);
        if (json.status=="sucess") {
          $(".login-form .error").css("display","none");
          $(".loader").fadeOut();
          window.location.href =json.html;
        }else{
          $(".login-form .sucess").css("display","none");
          $(".login-form .error").css("display","block");
          $(".login-form .error").html(json.html);
          $(".loader").fadeOut();
        }
      });
    }
    function createAccount(){
      var newpass = $.trim($("#cpassword").val());
      var repeatpass = $.trim($("#crpassword").val());
      var email = $.trim($("#cemail").val());
    if(email == "" || newpass == "" || repeatpass == ""){
       $(".signup-form .error").css("display","block");
       $(".signup-form .error").html("All fields required");
        if(email == ""){
           $("#cemail").css("border-color","red");
        }
        else{
            $("#cemail").css("border-color","rgba(0, 0, 0, 0.075)");
        }
            if(newpass == ""){
               $("#cpassword").css("border-color","red");
            }
            else{
                $("#cpassword").css("border-color","rgba(0, 0, 0, 0.075)");
            }
            if(repeatpass == ""){
               $("#crpassword").css("border-color","red");
            }
            else{
                $("#crpassword").css("border-color","rgba(0, 0, 0, 0.075)");
            }
              $(".loader").fadeOut();
    }
    else{
        if(newpass!=repeatpass){
           $(".signup-form .error").html("Both password should match");
            $("#cpassword").css("border-color","red");
            $("#crpassword").css("border-color","red");
              $(".loader").fadeOut();
        }
        else{
          $(".signup-form .input").css("border-color","rgba(0, 0, 0, 0.075)");
            if(isEmail(email)){
              $(".loader").fadeIn();
              $(".signup-form .error").css("display","none");
              $(".signup-form .error").html('');
              $(".signup-form .input").css("border-color","rgba(0, 0, 0, 0.075)");
              var email = $.trim($("#cemail").val());
              var newpass = $.trim($("#cpassword").val());
              var repeatpass = $.trim($("#crpassword").val());
              var session = "<?php echo $hashed;?>";
              if(newpass == repeatpass){
                  $(".loader").fadeIn();

                  $.post("files/controller/createac.php" , {
                      email: email,
                      pass: newpass,
                      rpass: repeatpass,
                      usession: session
                  }).done(
                  function(result){
//                    var json = $.parseJSON(result);
                    var json=result;
                    if (json.status=="sucess") {
                      $(".signup-form .error").css("display","none");
                      $(".signup-form .sucess").css("display","block");
                      $(".signup-form .sucess").html(json.html);
                      $(".loader").fadeOut();
                      window.location.href ="index.php";
                    }else{
                      $(".signup-form .sucess").css("display","none");
                      $(".signup-form .error").css("display","block");
                      $(".signup-form .error").html(json.html);
                      $(".loader").fadeOut();
                    }
                  });
              }
              else{
                  $(".signup-form .error").html("Both Password should be same");
                  $("#cpassword").css("border-color","red");
                  $("#crpassword").css("border-color","red");
                $(".loader").fadeOut();
              }
            }
            else{
                $(".signup-form .error").html("Enter valid email address");
                $("#cemail").css("border-color","red");
                $(".loader").fadeOut();
          }
        }
    }

}
  </script>
</body>
</html>
