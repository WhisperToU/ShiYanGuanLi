<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href=".\login.css">
  <title>shiwai.com</title>
</head>
<body>

<div class="login-wrap">
  <h2>Login</h2>
  
  <div class="form">
    <input type="text" placeholder="Username" name="un" />
    <input type="password" placeholder="Password" name="pw" />
    <button onclick="window.location.href='index.php'"> Sign in </button>
    <a href="#"> <p> Don't have an account? Register </p></a>
  </div>
</div>
<script type="text/javascript">
  // Login/Register form
// Author: Ian Pirro 
//------------------------------------
// Form will change from login to register and visa-versa based
// on if the user is already "registered"
// "Usernames" min-len is 5 chars
//
// Could be annoying... but fun anyways


// These users "already exist"
var users = [
{ name: 'ianpirro' },
{ name: 'joeschmoe' },
{ name: 'superdev' }
]

var loginform = {
  
  init: function() {
    this.bindUserBox();
  },
  
  bindUserBox: function() {
    var result = {};
    $(".form").delegate("input[name='un']", 'blur',  function(){
      var $self = $(this);
      
      // this grep would be replaced by $.post tp check db for user
      result = $.grep(users, function(elem, i){  
        return (elem.name == $self.val());
      });
      
      // This would be callback
      if (result.length === 1) {
        if( $("div.login-wrap").hasClass('register')) {
          loginform.revertForm();
          return;
        }
        else{
          return;
        }
      }
      
      if( !$("div.login-wrap").hasClass('register') ) {
        if ( $("input[name='un']").val().length > 4 )
          loginform.switchForm();
      }

    });
  },
  switchForm: function() {
    var $html = $("div.login-wrap").addClass('register');
    $html.children('h2').html('Register');
    $html.find(".form input[name='pw']").after("<input type='password' placeholder='Re-type password' name='rpw' />");
    $html.find('button').html('Sign up');
    $html.find('a p').html('Have an account? Sign in');
  },
  revertForm: function() {
    var $html = $("div.login-wrap").removeClass('register');
    $html.children('h2').html('Login');
    $html.find(".form input[name='rpw']").remove();
    $html.find('button').html('Sign in');
    $html.find('a p').html("Don't have an account? Register");
  },
  submitForm: function(){
    // ajax to handle register or login

  }
  
} // loginform {}


// Init login form
loginform.init();


// vertical align box   
(function(elem){ 
    elem.css("margin-top", Math.floor( ( $(window).height() / 2 ) - ( elem.height() / 2 ) ) );
}($(".login-wrap")));

$(window).resize(function(){
    $(".login-wrap").css("margin-top", Math.floor( ( $(window).height() / 2 ) - ( $(".login-wrap").height() / 2 ) ) );
  
});
</script>
</body>
</html>