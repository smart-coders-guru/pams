<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Reporting/Mailing</title>
    <link href="{!! asset('css/bootstrap.min.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('css/jquery-ui.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="{!! asset('css/sb-admin.css') !!}" media="all" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" media="all" rel="stylesheet" type="text/css" />
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
                <a class="navbar-brand" href="index.html">Reporting/Mailing</a>
            </div>
            <ul class="nav navbar-right top-nav">
               <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-table"></i> Global action <b class="caret"></b></a>
                    <ul class="dropdown-menu" style="min-width:190px;">
                        <li><label style="margin-left: 20px;font-weight: bold;">Select action</label></li>
                        <li><a data-toggle="modal" href="#register"><i class="fa fa-fw fa-edit"></i> Add app</a></li>
                        <li><a data-toggle="modal" href="#reportTemplate"><i class="fa fa-file-pdf-o"></i> Add report template</a></li>
                        <li><a data-toggle="modal" href="#emailTemplate"><i class="fa fa-envelope"></i> Add email template</a></li>
                        <li><a href="home"> <i class="fa fa-fw fa-table"></i> List apps</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-table"></i>
                    @foreach(Session::get('apps') as $ap)
                        {{$ap->apps_name}}
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
                    <!--@foreach(Session::get('apps') as $app)
                        {{$app->apps_name}}
                    @endforeach-->
                     <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="fa fa-fw fa-envelope"></i> Edit app</a></li>
                        <li class="divider"></li><li><a href="logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li><a  href="{{ url('report/getAllReports') }}"><i class="fa fa-fw fa-envelope"></i> Report list</a></li>
                    <li><a  href="{{ url('report/getAllReportTemplates') }}"><i class="fa fa-fw fa-envelope"></i> Report templates</a></li>
                    <li><a data-toggle="modal" href="#reportTemplate"><i class="fa fa-fw fa-envelope"></i> Add template</a></li>
                    <!--li><a data-toggle="modal" href="#report"><i class="fa fa-fw fa-envelope"></i> Add report</a></li-->
                </ul>
            </div>
        </nav>






        <div id="page-wrapper">
            <div class="container-fluid">
                 <section class="cont-header">
                    <div class="row">
                        <div class="col-md-4"> <h2>Report Templates</h2></div>
                        <div class="col-md-8"> 
                          <ul class="breadcrumb">
                            <li><a href="#" style="color:green; font-weight: bold;"><i class="fa fa-eye"></i> View</a></li>
                            <li><a href="#" style="color:green;font-weight: bold;"><i class="fa fa-edit"></i> Edit</a></li>
                            <li class="active"><a href="#"style="color:red;font-weight: bold;"><i class="fa fa-remove"></i> Delete</a></li>
                          </ul>
                        </div>
                    </div>
                </section>
                <section class="content">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="box">
                        <div class="box-body table-responsive">
                            <table id="example" class="table display" style="width:100%">
                               <thead> 
                                <tr> 
                                    <th style="width:90px;padding-left:10px;"><input type="checkbox" value=""> Check all</button></th>
                                    <th>Id</th> 
                                    <th>Title</th> 
                                    <th>Description</th>  
                                    <th>Action</th> 
                                </tr> 
                               </thead> 
                               <tbody> 
                                    @foreach($report_template as $report_template)  
                                       @if($report_template->report_template_state==0)
                                            <tr class="odd gradeX">
                                                <td><input type="checkbox" value=""/></td>
                                                <td style="width:30px;"> {{ $report_template->report_template_id }}</td>
                                                <td> {{ $report_template->report_template_name }}</td>
                                                <td> {{ $report_template->report_template_desc }}</td> 
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-success dropdown-toggle" data-toggle="dropdown">
                                                        View <span class="caret"></span></button>
                                                        <ul class="dropdown-menu" role="menu">
                                                          <li><a href="#" style="font-weight: bold; color: green;"><i class="fa fa-eye"></i> View</a></li>
                                                          <li><a href="#" style="font-weight: bold; color: green;"><i class="fa fa-edit"></i> Edit</a></li>
                                                          <li><a href="{{ url('report/deleteReport/'.$report_template->report_template_id) }}" style="font-weight: bold; color: red;"><i class="fa fa-remove"></i> Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody> 
                                <tfoot> 
                                    <tr> 
                                        <th>Check all</th> 
                                        <th>Id</th> 
                                        <th>Name</th> 
                                        <th>Description</th> 
                                        <th>Action</th> 
                                    </tr> </tfoot> 
                            </table>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </section><!-- /.content -->
            </div>
        </div>
        <footer style="position:relative; padding-left: 10px; padding-bottom: 10px; clear:both;background-color: white;">
                Copyright smartCoders platform 2018
        </footer>
                                 




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
                              <form method="post" action="{!! url('saveReport') !!}" accept-charset="UTF-8">
                                {!! csrf_field() !!}
                                <div class="form-group">
                                  <div class="form-label-group">
                                    <label for="report_title">Title</label>
                                    <input type="text" id="report_title" name="report_title" class="form-control" placeholder="Title" required="required" autofocus="autofocus">
                                    
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="form-label-group">
                                    <label for="report_link">link</label>
                                    <input type="text" id="report_link"  name="report_link" class="form-control" placeholder="http://..." required="required">
                                    
                                  </div>
                                  <div class="form-group">
                                  <div class="form-label-group">
                                    <label for="report_desc">Description</label>
                                    <input type="text" id="report_desc" name="report_desc" class="form-control" placeholder="Descriptio...">
                                    
                                  </div>
                                </div>
                                <div class="form-label-group">
                                    <label for="report_content">Content</label>
                                    <textarea type="text" id="report_content" name="report_content" class="form-control" placeholder="data of report"></textarea>
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




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>
   <script type="text/javascript" src="{!! asset('js/jquery.min.js') !!}"></script>
    <!--script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script-->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <!--script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script-->
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> 
    <script type="text/javascript" src="{!! asset('js/js.js') !!}"></script>
</body>

</html>
