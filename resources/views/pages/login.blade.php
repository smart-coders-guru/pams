<!DOCTYPE html>
<html lang="en" style="display:table;height:100%; width:100%;">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Reporting/Mailing</title>
    <link href="{!! asset('css/bootstrap-new.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('css/sb-admin.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('css/style.css') !!}" media="all" rel="stylesheet" type="text/css" />
  </head>
  <body class="bg-dark" style="height:100%;display:table!important; display: flex!important;">
    <div class="container" style="display:table-cell!important; margin: auto!important; vertical-align: middle!important;">
      <div  class="card card-login mx-auto mt-5 col-sm-10 col-md-3" >
        <div class="card-header" id="card-head">Sign in</div>
        <div class="card-body" id="card-padding-none">
          <form method="post" action="{!! url('authenticate') !!}" accept-charset="UTF-8">
            {!! csrf_field() !!}
            <div class="form-group">
              <div class="form-label-group">
                <label for="apps_login"> Login</label>
                <input type="text" id="apps_login" name="apps_login" class="form-control" placeholder="login" required="required" autofocus="autofocus">
              </div>
            </div>
            <div class="form-group">
              <div class="form-label-group">
                <label for="apps_password">Password</label>
                <input type="password" id="apps_password" name="apps_password" class="form-control" placeholder="Password" required="required">
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="remember-me">
                  Remember Password
                </label>
              </div>
            </div>
            <input type="submit" class="btn btn-primary btn-block"  value="Sign in"/>
          </form>
          <div class="text-center"style=" font-weight: bold;">
            <a class="d-block small" href="forgot-password">Forgot Password?</a>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="{!! asset('js/bootstrap.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/jquery.min.js') !!}"></script>
  </body>

</html>
