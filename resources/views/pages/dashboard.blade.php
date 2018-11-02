<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Reporting/Mailing</title>
    <link href="{!! asset('css/bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('css/sb-admin.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('font-awesome/css/font-awesome.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('css/style.css') !!}" media="all" rel="stylesheet" type="text/css" />
</head>
<body>

     <div id="wrapper">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span> 
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home">Reporting/Mailing</a>
            </div>
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    @foreach(Session::get('apps') as $appl)
                        @if($appl->apps_login=='noApps')
                             <a href="{{ url('home') }}"><i class="fa fa-home">  Home </i></a>
                        @endif
                    @endforeach
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-dashboard"></i> Dashboard <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><label style="margin-left: 20px;font-weight: bold;">  Select view</label></li>
                        <li><a href="{{ url('report/getAllReports') }}"><i class="fa fa-file-pdf-o"></i>  Reporting</a></li>
                        <li><a href="email"><i class="fa fa-fw fa-envelope"></i>  Mailing</a></li>
                        <li><a href="{{ url('home/dashboard') }}"><i class="fa fa-dashboard"></i>  Dashboard</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-table"></i>
                    @foreach(Session::get('apps') as $apps)
                        {{$apps->apps_name}}
                    @endforeach
                    <b class="caret"></b></a>
                   
                    @foreach(Session::get('apps') as $appS)
                        @if($appS->apps_login=='noApps')
                            <ul class="dropdown-menu">
                                <li><label style="margin-left: 8px;font-weight: bold;">Select application</label></li>
                                @foreach($app as $app)
                                    <li><a href="{{ url('/'.$app->apps_name) }}"><i class="fa fa-fw fa-user"></i>{{$app->apps_name}}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> App account
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#updateApp"><i class="fa fa-fw fa-envelope"></i> Edit app</a></li>
                        <li class="divider"></li><li><a href="logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li> <a href="#" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Select View <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li><a  href="{{ url('home/dashboard') }}"><i class="fa fa-fw fa-envelope"></i> Dashboard</a></li>
                            <li><a  href="{{ url('report/getAllReports') }}"><i class="fa fa-fw fa-envelope"></i> Reporting</a></li>
                            <li><a  href="email"><i class="fa fa-fw fa-envelope"></i> Mailing</a></li>
                        </ul>
                    </li>
                    <li><a  href="#updateApp"><i class="fa fa-fw fa-envelope"></i> Edit current app</a></li>
                    
                </ul>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Dashboard<small>/reporting</small></h1>

                    </div>
                </div> 
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">26</div>
                                        <div>New Reports!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-tasks fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">12</div>
                                        <div>New Report Templates</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">124</div>
                                        <div>All reports</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-support fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">13</div>
                                        <div>All Report Templates!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                 <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default" style="margin-top:40px;">
                            <div class="panel-heading">
                               Current app: Webcolf project
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="col-md-7">
                                    <ul>
                                        @foreach($myApps as $myApp) 
                                            <li><b>Name :</b> {{ $myApp->apps_name }}</li>
                                            <li><b>Creation date :</b> {{ $myApp->apps_creation_date }}</li>
                                            <li><b>Description :</b> {{ $myApp->apps_desc }}</li>
                                        @endforeach
                                        
                                    </ul>
                                </div>

                                <div class="col-md-offset-1 col-md-4" style="margin-top: 20px;">
                                     <a class="btn btn-primary btn-success" style="margin-left: 50px;width: 250px!important; font-size: 15pt;" href="{{ url('report/getAllReports') }}">Go to Reporting view </a>
                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                 <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">26</div>
                                        <div>New Reports!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-tasks fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">12</div>
                                        <div>New Report Templates</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">124</div>
                                        <div>All reports</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-support fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">13</div>
                                        <div>All Report Templates!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>



                 <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default" style="margin-top:40px;">
                            <div class="panel-heading">
                               Current app: Webcolf project
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="col-md-7">
                                    <ul>
                                        @foreach($myApps as $myApps) 
                                            <li><b>Name :</b> {{ $myApps->apps_name }}</li>
                                            <li><b>Creation date :</b> {{ $myApps->apps_creation_date }}</li>
                                            <li><b>Description :</b> {{ $myApps->apps_desc }}</li>

                                        @endforeach
                                        
                                    </ul>
                                </div>

                                <div class="col-md-offset-1 col-md-4" style="margin-top: 20px;">
                                     <a class="btn btn-primary btn-danger" style="margin-left: 50px;width: 250px!important; font-size: 15pt;" href="{{ url('mailing/getEmailList') }}">Go to Mailing view </a>
                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->




                <div class="modal fade" id="updateApp">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header header-dialog">
                                <button type="button" class="close" data-dismiss="modal">x</button>
                                <h2 class="modal-title">Update application</h2>
                            </div>
                            <div class="modal-body">         
                                <div class="card card-register mx-auto mt-5">
                                    <div class="card-header" style="margin-bottom: 10px;"><i>Update current Application Account</i></div>
                                    <div class="card-body">
                                      <form method="post" action="{!! url('updateCurrentApp') !!}" accept-charset="UTF-8">
                                        {!! csrf_field() !!}
                                         @foreach(Session::get('apps') as $ap)
                                            <input type="hidden" name="apps_id" value="{{$ap->apps_id}}">
                                         @endforeach 
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label for="apps_name">Name<span class="required">*</span></label>
                                               
                                                @foreach(Session::get('apps') as $ap)
                                                    <input type="text" id="apps_name" name="apps_name"required="required" autofocus=" autofocus" class="form-control" value=" {{$ap->apps_name}}"/>
                                                @endforeach 
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label for="apps_login">Login<span class="required">*</span></label>
                                                @foreach(Session::get('apps') as $ap)
                                                    <input type="text" id="apps_login" name="apps_login" class="form-control" value="{{$ap->apps_login}}" required="required"/>
                                                @endforeach 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label for="apps_Password">Password<span class="required">*</span></label>
                                                @foreach(Session::get('apps') as $ap)
                                                     <input type="password" id="apps_password" name="apps_password" class="form-control" value=" {{$ap->apps_password}}" required="required"/>
                                                @endforeach 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                              <div class="form-label-group">
                                                <label for="confirm_password">Confirm password<span class="required">*</span></label>
                                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm password" required="required"/>
                                              </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="form-label-group">
                                            <label for="apps_desc">Description</label>
                                            @foreach(Session::get('apps') as $ap)
                                                 <textarea type="text" id="apps_desc" name="apps_desc" class="form-control" value=" {{ $ap->apps_desc }}" required="required"></textarea>
                                            @endforeach
                                          </div>
                                        </div>
                                       
                                        <div class="">
                                            <input type="reset" class="btn btn-primary btn-danger" value="Cancel"/>
                                            <input type="submit" class="btn btn-primary btn-success" value="Register"/>
                                        </div>
                                      </form>
                                      <!--div class="text-center">
                                        <a class="d-block small mt-3" style="font-weight: bold;" href="login">Login Page</a><br/>
                                      </div-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            <div class="modal fade" id="reportTemplate">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">Report template</h4>
                        </div>
                        <div class="modal-body">         
                           
                             <div class="card card-login mx-auto mt-5">
                                <div class="card-body">
                                  <form method="post" action="{!! url('saveReportTemplate') !!}" accept-charset="UTF-8">
                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                      <div class="form-label-group">
                                        <label for="report_template_name">Name</label>
                                        <input type="text" id="report_template_name" name="report_template_name" class="form-control" placeholder="Name" required="required" autofocus="autofocus">
                                        
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="form-label-group">
                                        <label for="report_template_desc">Description</label>
                                        <input type="text" id="report_template_desc" name="report_template_desc" class="form-control" placeholder="Description ..." required="required">
                                        
                                      </div>
                                    </div>
                                    <div class="form-label-group">
                                        <label for="report_template_content">Content</label>
                                        <textarea type="text" id="report_template_content" name="report_template_content" class="form-control" placeholder="<h1>Hello World!</h1>"></textarea>
                                    </div>
                                    <div class="form-group" style="margin-top: 20px;">
                                        <input type="submit" class="btn btn-primary btn-block" value="Save"/>    
                                    </div>
                                    
                                  </form>
                                  <div class="text-center">
                                    <a data-toggle="modal" class="d-block small mt-3" href="#register">Cancel</a>
                                  </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>






            <div class="modal fade" id="emailAccount">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">Email Account</h4>
                        </div>
                        <div class="modal-body">         
                           
                             <div class="card card-login mx-auto mt-5">
                                <div class="card-body">
                                  <form>
                                    <div class="form-group">
                                      <div class="form-label-group">
                                        <label for="inputEmail">Name</label>
                                        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
                                        
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="form-label-group">
                                        <label for="inputPassword">Email address</label>
                                        <input type="password" id="inputPassword" class="form-control" placeholder="Your email ..." required="required">
                                        
                                      </div>
                                    </div>
                                    <div class="form-label-group">
                                        <label for="apps_desc">Description</label>
                                        <textarea type="text" id="content" class="form-control" placeholder="..."></textarea>
                                    </div>
                                    <div class="form-group" style="margin-top: 20px;">
                                        <a class="btn btn-primary btn-block" href="home">Save</a>    
                                    </div>
                                    
                                  </form>
                                  <div class="text-center">
                                    <a data-toggle="modal" class="d-block small mt-3" href="#register">Cancel</a>
                                  </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



            <div class="modal fade" id="sendMail">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" datadismiss="modal">x</button>
                                <h4 class="modal-title">Send mail</h4>
                            </div>
                            <div class="modal-body">
                                <div class="card card-register mx-auto mt-5">
                                    <div class="card-header">Send mail</div>
                                    <div class="card-body">
                                      <form>
                                        <div class="form-group">
                                          <div class="form-row">
                                            <div class="col-md-12">
                                              <div class="form-label-group">
                                                <label for="firstName">To</label>
                                                <input type="text" id="apps_name" name="apps_name" class="form-control" placeholder="email..." required="required" autofocus="autofocus">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="form-row">
                                            <div class="col-md-12">
                                              <div class="form-label-group">
                                                <label for="apps_Password">Subject</label>
                                                <input type="subject" id="subject" class="form-control" placeholder="title" required="required">
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <div class="form-label-group" style="padding-left: 12px; padding-right: 12px">
                                            <label for="apps_desc">Content</label>
                                            <textarea type="text" id="content" class="form-control" placeholder="..." ></textarea>
                                          </div>
                                        </div>
                                        <a class="btn btn-primary btn-block" href="login">Send mail</a>
                                      </form>
                                      <div class="text-center">
                                        <a class="d-block small mt-3" href="login">Cancel</a><br/>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-info" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>


              

                


                
    <script type="text/javascript" src="{!! asset('js/bootstrap.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('js/jquery.min.js') !!}"></script>
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!--script type="text/javascript" src="{!! asset('js/bootstrap-new.min.js') !!}"></script-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>

</html>
