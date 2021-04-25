@include('partials.head')
@include('partials.topnav')
@include('partials.sidebar')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      @yield('content')
    </div>
  </div>
</div>
</div>
</div>

@include('partials.footer')

</div>
  </div>
  <script src="{{ asset('assetsAdminTemplate/vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{ asset('assetsAdminTemplate/vendors/js/vendor.bundle.addons.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('assetsAdminTemplate/js/off-canvas.js')}}"></script>
  <script src="{{ asset('assetsAdminTemplate/js/misc.js')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('assetsAdminTemplate/js/dashboard.js')}}"></script>
  <!-- End custom js for this page-->
  
  <!-- DATATABLES -->
  <script src="{{ asset('lib/Datatables/jquery.dataTables.min.js')}}"></script>
  <!-- <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> -->
  <!-- Datepicker -->
  <script src="{{ asset('assetsAdminTemplate/js/bootstrap-datepicker.js')}}"></script>
  <!-- Select2 -->
  <script src="{{ asset('lib/Select2/select2.min.js')}}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> -->
  <!-- Toastr -->
  <script src="{{ asset('lib/Toastr/toastr.min.js')}}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> -->
  <!-- Jquery Confirm -->
  <script src="{{ asset('lib/JqueryConfirm/jquery-confirm.min.js')}}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script> -->
  <!-- JQUERY DATE RANGE -->
  <script type="text/javascript" src="{{ asset('lib/JqueryDateRange/moment.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('lib/JqueryDateRange/daterangepicker.min.js')}}"></script>
  <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> -->
  
  @yield('script')
</body>

</html>