<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Signin Template for Bootstrap</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="http://yandex.st/bootstrap/3.1.1/css/bootstrap.min.css" />
        <script src="http://yandex.st/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>

        <style type="text/css">
            body {
              padding-top: 40px;
              padding-bottom: 40px;
              background-color: #eee;
            }

            .form-signin {
              max-width: 330px;
              padding: 15px;
              margin: 0 auto;
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
              margin-bottom: 10px;
            }
            .form-signin .checkbox {
              font-weight: normal;
            }
            .form-signin .form-control {
              position: relative;
              height: auto;
              -webkit-box-sizing: border-box;
                 -moz-box-sizing: border-box;
                      box-sizing: border-box;
              padding: 10px;
              font-size: 16px;
            }
            .form-signin .form-control:focus {
              z-index: 2;
            }
            .form-signin input[type="email"] {
              margin-bottom: -1px;
              border-bottom-right-radius: 0;
              border-bottom-left-radius: 0;
            }
            .form-signin input[type="password"] {
              margin-bottom: 10px;
              border-top-left-radius: 0;
              border-top-right-radius: 0;
            }
        </style>
    </head>

    <body>

        <div class="container">

            <form class="form-signin" role="form" method="post" enctype="application/x-www-form-urlencoded" action="login" >
                <h2 class="form-signin-heading">Вход в систему</h2>
                <input type="text" name="login" class="form-control" placeholder="Email address" required autofocus>
                <input type="password" name="pass" class="form-control" placeholder="Password" required>
                <label class="checkbox">
                  <!-- <input type="checkbox" value="remember-me"> Remember me -->
                </label>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Вход</button>
            </form>

        </div> <!-- /container -->
    </body>
</html>
