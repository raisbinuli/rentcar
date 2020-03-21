<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf_token" content="{{ csrf_token() }}">
  <title>RENTCAR</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.min.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .card-header{
      background:#007bff;
    }
  </style>


  @yield("other-css")

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  
  
  <!-- /.navbar -->
   
  <!-- Main Sidebar Container -->
  

  
    <div class="container">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link active">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                </li>
            </ul>           
            
        </nav>
    <!-- Main content -->
        <section class="content">
            @yield("content")
        </section>

        <footer class="main-footer">
    
            <div class="d-none d-sm-inline-block">
            
            </div>
        </footer>
    </div>
    <!-- /.content -->


  <!-- /.content-wrapper -->
  

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>

<script>

function ajaxRentCar(url,data,method,callback,errorId){
    
    data['_token'] = "{{ csrf_token() }}";
    
    if(errorId)
      $("#"+errorId).html("");

    $.ajax({
        url: url,
        method : method,
        data :data,
        dataType:'JSON',
        beforeSend : function(){
            $("#loading").css('visibility', 'visible');
        },
        success: function(result){
            if(typeof callback == 'function'){
                
                callback(result);
            }
        },
        error:function(requestObject, error, errorThrown){            
            
            console.log('error : ', error, ' errorThrown : ',errorThrown,requestObject);            
            if (requestObject.status == 422) {
              var response = JSON.parse(requestObject.responseText);
              var listErrors = "";
              $.each(response.errors,function(key,value){
                listErrors = listErrors + '<li class="error invalid-feedback">'+key+' : ' +value[0]+'</li>'
              });
              
              $("#"+errorId).html(listErrors);
            }
            
        }
    });
}
</script>
@yield("other-js")


</body>
</html>
