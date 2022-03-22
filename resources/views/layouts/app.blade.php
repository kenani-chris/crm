<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>TOYOTA KENYA | TOYOTA KENYA Outbound Call Center CRM</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="DT-DOBIE" name="description" />
        <meta content="Bytecity Inclusive Solutions - www.bytecityinc.com" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
        <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
		<!-- App css -->
		<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />

		<link href="{{asset('assets/css/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.min.css')}}"/>

		<link href="{{asset('assets/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />


		<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{asset('assets/css/jquery.multiselect.css')}}" rel="stylesheet" type="text/css" />


        
    <link href="{{asset('assets/css/gijgo.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/custom.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">
    <script src="{{asset('assets/js/jquery.js')}}"></script> 

    @livewireStyles

    @stack('scripts_2')
        
    </head>
    <body>
        <div id="wrapper">
        @include('layouts.topbar')
            @include('layouts.sidebar')

            <div class="content-page">
                <div class="content">
                <div class="container-fluid">
                <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">TOYOTA KENYA</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">@yield('pageParent')</a></li>
                                            <li class="breadcrumb-item active">@yield('pageTitle')</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">@yield('pageTitle')</h4>
                                </div>
                            </div>
                        </div>  


                 

                              @yield('content')
                             

            
                       
                      
                        <div id="vp_modal" class="vp_modal">

                    <!-- Modal content -->
                    <div class="vp_modal-content">
                    <div class="vp_loader"></div>
                    <p>Please wait...</p>
                    <div class="loader"></div>
                    </div>

                    </div>
                </div>

                </div>
                @include('layouts.footer')
            </div>
    </div>

    <script src="{{asset('assets/js/vendor.min.js')}}"></script>
   
    @livewireScripts


<script type="text/javascript" src="{{asset('assets/js/datatables.min.js')}}"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
 <script src="{{asset('assets/js/gijgo.min.js')}}" type="text/javascript"></script>


   <!-- <script src="{{asset('assets/js/additional-methods.min.js')}}"></script>-->
 <script src="{{asset('assets/js/avatar.js')}}"></script>

    <script src="{{asset('assets/js/jquery.multiselect.js')}}"></script>

    <link rel="stylesheet" href="{{asset('assets/css/sweetalert2.min.css')}}">
    <script src="{{asset('assets/js/sweetalert2.all.min.js')}}"></script>

    <script text="text/javascript">

function loadModal(display) {
        var modal = document.getElementById("vp_modal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("vp_close")[0];
        modal.style.display = display;
    }
   
    var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()+1);

    $('#date_from').datepicker({
        format: 'yyyy-mm-dd',
        iconsLibrary: 'fontawesome',
            uiLibrary: 'bootstrap',
            maxDate:today,


        });

        $('#date_to').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#date_from').val();
            },
            maxDate:today
        });

        $('#completion_from').datepicker({
        format: 'yyyy-mm-dd',
        iconsLibrary: 'fontawesome',
            uiLibrary: 'bootstrap',
            maxDate:today,


        });

        $('#completion_to').datepicker({
            format: 'yyyy-mm-dd',
            uiLibrary: 'bootstrap',
            iconsLibrary: 'fontawesome',
            minDate: function () {
                return $('#date_from').val();
            },
            maxDate:today
        });

    </script>
<script src="{{asset('assets/js/jquery.validate.js')}}"></script>  
    <script src="{{asset('assets/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/js/app.min.js')}}"></script>
    @stack('scripts')
</body>
</html>
