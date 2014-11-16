<? header("Content-Type: text/html; charset=UTF-8");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>University Sync</title>
        <!-- <link href="bootstrap.css" rel="stylesheet" type="text/css" media="screen" />     -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/color/jquery.color-2.1.2.js"></script>
        <script src="./tinymce/tinymce.min.js"></script>
        <script src="js/bjqs-1.3.js" type="text/javascript"></script>
        <script>

            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-56492222-1', 'auto');
            ga('send', 'pageview');

            tinymce.init({
                selector:'textarea.tinymce',
                fullpage_default_encoding: "UTF-8",
                entity_encoding: "UTF-8",
                body_id: "tinymce",
                height: "200px",
                content_css : "./style.css",
                plugins: 'link image code',
            });    
 

            // This is called with the results from from FB.getLoginStatus().
              function statusChangeCallback(response) {
                console.log('statusChangeCallback');
                console.log(response);
                // The response object is returned with a status field that  lets the
                // app know the current login status of the person.
                // Full docs on the response object can be found in the documentation
                // for FB.getLoginStatus().
                if (response.status === 'connected') {
                  // Logged into your app and Facebook.
                  testAPI();
                } else if (response.status === 'not_authorized') {
                  // The person is logged into Facebook, but not your app.
                  document.getElementById('status').innerHTML = 'Please log ' +
                    'into this app.';
                } else {
                  // The person is not logged into Facebook, so we're not sure if
                  // they are logged into this app or not.
                  document.getElementById('status').innerHTML = 'Please log ' +
                    'into Facebook.';
                }
              }

              // This function is called when someone finishes with the Login
              // Button.  See the onlogin handler attached to it in the sample
              // code below.
              function checkLoginState() {
                FB.getLoginStatus(function(response) {
                  statusChangeCallback(response);
                });
              }

              window.fbAsyncInit = function() {
              FB.init({
                appId      : '404350186381889',
                cookie     : true,  // enable cookies to allow the server to access 
                                    // the session
                xfbml      : true,  // parse social plugins on this page
                version    : 'v2.1' // use version 2.1
              });

              // Now that we've initialized the JavaScript SDK, we call 
              // FB.getLoginStatus().  This function gets the state of the
              // person visiting this page and can return one of three states to
              // the callback you provide.  They can be:
              //
              // 1. Logged into your app ('connected')
              // 2. Logged into Facebook, but not your app ('not_authorized')
              // 3. Not logged into Facebook and can't tell if they are logged into
              //    your app or not.
              //
              // These three cases are handled in the callback function.

              FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
              });

              };

              // Load the SDK asynchronously
              (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));

              // Here we run a very simple test of the Graph API after login is
              // successful.  See statusChangeCallback() for when this call is made.
              function testAPI() {
                console.log('Welcome!  Fetching your information.... ');
                FB.api('/me', function(response) {
                  console.log('Successful lllogin for: ' + response.name);
                  console.log('tvoj e-mail: ' +response.email); 
                  //alert('Vitaj! ' + response.name);
                  //window.location.replace("http://web1.eqo.sk/?page=action&type=FBlogin&email="+response.email);
                  document.getElementById('status').innerHTML =
                    'Thanks for logging in, ' + response.name + '!';
                });
              }
        </script>
        <script type="text/javascript" src="./script.js"></script>  
        <script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
        <script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        <script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.js"></script>
        <link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />
        <link href="style.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="shortcut icon" href="images/favicon_16px.ico"> 
        
    </head>
    <body>
        