<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Reporting</title>
    <link href="{!! asset('css/bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('css/sb-admin.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('font-awesome/css/font-awesome.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('font-awesome/css/all.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('datatables/datatables.bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    
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
                <a class="navbar-brand" href="index.html">Reporting</a>
            </div>
            <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-table"></i> Dashboard <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><label style="margin-left: 20px;font-weight: bold;">Select view</label></li>
                        <li><a href="reports"><i class="fa fa-fw fa-user"></i>Reporting</a></li>
                        <li><a href="email"><i class="fa fa-fw fa-user"></i>Mailing</a></li>
                        <li><a href="myapps"><i class="fa fa-fw fa-user"></i>Dashboard</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-table"></i> @foreach(Session::get('apps') as $apps)
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
                        <li><a href="#"><i class="fa fa-fw fa-envelope"></i> Edit app</a></li>
                        <li class="divider"></li><li><a href="logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li><a href="email"><i class="fa fa-fw fa-envelope"></i> View email</a></li>
                    <li> <a href="#" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Programmed email <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li><a href="programmed-email"><i class="fa fa-fw fa-envelope"></i>Programmed email list</a></li>
                            <li><a href="#prog_email"><i class="fa fa-fw fa-envelope"></i>Programmed an email</a></li>
                        </ul>
                    </li>
                    <li> <a href="#" data-toggle="collapse" data-target="#demo1"><i class="fa fa-fw fa-arrows-v"></i> Mailing list <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo1" class="collapse">
                            <li><a href="mailing-list"><i class="fa fa-fw fa-envelope"></i>View mailing list</a></li>
                            <li><a href="#"><i class="fa fa-fw fa-envelope"></i>Add mailing list</a></li>
                        </ul>
                    </li>
                     <li> <a href="#" data-toggle="collapse" data-target="#demo2"><i class="fa fa-fw fa-arrows-v"></i> Mail account <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo2" class="collapse">
                            <li><a href="email-account"><i class="fa fa-fw fa-envelope"></i>Acount list</a></li>
                            <li><a data-toggle="modal" href="#emailAccount"><i class="fa fa-fw fa-envelope"></i>Add account</a></li>
                        </ul>
                    </li>
                     <li><a data-toggle="modal" href="#sendMail"><i class="fa fa-fw fa-envelope"></i>Send mail</a></li>
                </ul>
            </div>
        </nav>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Email list</h1>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-lg-12">
                         <div class="panel panel-default">
                            <div class="panel-heading">
                                Email list
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Content</th>
                                                <th>Link</th>
                                                <th>Creation date</th>
                                                <th>description</th>
                                                <th>State</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @foreach($prog_email as $prog_email)  
                                                <tr class="odd gradeX">
                                                    <td>{{ $prog_email->programmed_email_id }}</td>
                                                    <td>{{ $prog_email->programmed_email_title }}</td>
                                                    <td>{{ $prog_email->programmed_email_subject }}</td>
                                                    <td>{{ $prog_email->programmed_email_content }}</td>
                                                    <td>{{ $prog_email->programmed_email_attachments }}</td>
                                                    <td>{{ $prog_email->programmed_email_send_time }}</td>
                                                    <td>{{ $prog_email->programmed_email_state }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                                <div class="col-md-offset-8 col-md-4">
                                    <ul class="pagination">
                                        <li><a href="#">Previous</a></li>
                                        <li class="active"><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">4</a></li>
                                        <li><a href="#">Next</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->


        <div class="modal fade" id="report">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Report</h4>
                    </div>
                    <div class="modal-body">         
                       
                         <div class="card card-login mx-auto mt-5">
                            <div class="card-body">
                              <form>
                                <div class="form-group">
                                  <div class="form-label-group">
                                    <label for="inputEmail">Title</label>
                                    <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
                                    
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="form-label-group">
                                    <label for="inputPassword">link</label>
                                    <input type="password" id="inputPassword" class="form-control" placeholder="http://..." required="required">
                                    
                                  </div>
                                  <div class="form-group">
                                  <div class="form-label-group">
                                    <label for="inputPassword">Description</label>
                                    <input type="password" id="inputPassword" class="form-control" placeholder="...">
                                    
                                  </div>
                                </div>
                                <div class="form-label-group">
                                    <label for="apps_desc">Content</label>
                                    <textarea type="text" id="content" class="form-control" placeholder="data of report"></textarea>
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

    </div>
</div>  



<button data-toggle="modal" href="#emailAccount" class="btn btn-primary">Email Account</button>
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
                          <form>
                            <div class="form-group">
                              <div class="form-label-group">
                                <label for="inputEmail">Name</label>
                                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
                                
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="form-label-group">
                                <label for="inputPassword">Description</label>
                                <input type="password" id="inputPassword" class="form-control" placeholder="Your email ..." required="required">
                                
                              </div>
                            </div>
                            <div class="form-label-group">
                                <label for="apps_desc">Content</label>
                                <textarea type="text" id="content" class="form-control" placeholder="<h1>Hello World!</h1>"></textarea>
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
                                <h4 class="modal-title">Send Mail</h4>
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
