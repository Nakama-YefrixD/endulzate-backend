<!DOCTYPE html>
<html lang="es">


<!-- Mirrored from www.ampleadmin.wrappixel.com/ampleadmin-html/ampleadmin-material/index.html by HTTrack assetsAdminTemplatesite Copier/3.x [XR&CO'2014], Mon, 18 Mar 2019 16:16:50 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assetsAdminTemplate/assets/images/favicon.png')}}">
    <!-- <title>Ample admin Template - The Ultimate Multipurpose admin template</title> -->
    <!-- chartist CSS -->
    <link href="{{ asset('assetsAdminTemplate/assets/libs/chartist/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assetsAdminTemplate/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <!--c3 CSS -->
    <link href="{{ asset('assetsAdminTemplate/assets/libs/morris.js/morris.css')}}" rel="stylesheet">
    <link href="{{ asset('assetsAdminTemplate/assets/extra-libs/c3/c3.min.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assetsAdminTemplate/assets/libs/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('assetsAdminTemplate/assets/extra-libs/calendar/calendar.css')}}" rel="stylesheet" />
    <!-- needed css -->
    <link href="{{ asset('assetsAdminTemplate/dist/css/style.min.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    
    <script> 
        let csrf_token = '{{ csrf_token() }}';
        console.log("TOKEN:"+csrf_token)
    </script>
    <div id="app"></div>

    <script src="{{ asset('js/app.js') }}"></script>
    
    
    <div class="chat-windows"></div>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assetsAdminTemplate/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assetsAdminTemplate/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- apps -->
    <script src="{{ asset('assetsAdminTemplate/dist/js/app.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/dist/js/app.init.material.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/dist/js/app-style-switcher.js')}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assetsAdminTemplate/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/assets/extra-libs/sparkline/sparkline.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('assetsAdminTemplate/dist/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('assetsAdminTemplate/dist/js/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('assetsAdminTemplate/dist/js/custom.min.js')}}"></script>
    <!-- This Page JS -->
    <script src="{{ asset('assetsAdminTemplate/assets/libs/chartist/dist/chartist.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/dist/js/pages/chartist/chartist-plugin-tooltip.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/assets/extra-libs/c3/d3.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/assets/extra-libs/c3/c3.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/assets/libs/raphael/raphael.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/assets/libs/morris.js/morris.min.js')}}"></script>
    <!-- <script src="{{ asset('assetsAdminTemplate/dist/js/pages/dashboards/dashboard1.js')}}"></script> -->
    <script src="{{ asset('assetsAdminTemplate/assets/libs/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/assets/libs/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{ asset('assetsAdminTemplate/dist/js/pages/calendar/cal-init.js')}}"></script>
    <script>
        $('#calendar').fullCalendar('option', 'height', 650);

    </script>
    @yield('script')

</body>
</html>