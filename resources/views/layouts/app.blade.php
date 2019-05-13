<!doctype html>
<html lang="en">
  <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donlisa Recharge Pro</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    
    <!-- jquery toastr -->
    <link href="{{ asset('toastr/toastr.css') }}" rel="stylesheet"/>
    <!-- Material Kit CSS -->
    <link href="{{ asset('material-kit/css/material-kit.css?v=2.0.5') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet"/>
  </head>
  <body class="sidebar-collapse">
    <!-- transacton loader -->
    <div class="white-bg transaction_loader">
        <div class="load-wrapp">
            <div class="load">
                <span> Donlisa DRP.. </span>
                <div class="square-holder">
                    <div class="square"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="app">
        <nav-bar></nav-bar>
        @yield('content')
    </div>

    <footer class="footer">
      <div class="container">
        <nav class="float-left">
          <ul>
            <li>
              <a href="/faq">
                Faq
              </a>
            </li>
            <li>
              <a href="/about">
                About Us
              </a>
            </li>
            <li>
              <a href="/member">
                Pay With EDPoints
              </a>
            </li>
          </ul>
        </nav>
        <div class="copyright float-right">
          &copy;
          <script>
            document.write(new Date().getFullYear())
          </script>,
           made by
          <a href="http://amadurs.com" target="_blank">The Amadurs Team</a>
        </div>
      </div>
    </footer>


<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

<script src="https://js.paystack.co/v1/inline.js"></script>
<!-- toastr js library -->
<script src="{{ asset('toastr/toastr.min.js') }}"></script>
<!--   Core JS Files   -->
<!-- <script src="{{ asset('material-kit/js/core/jquery.min.js') }}" type="text/javascript"></script> -->
<script src="{{ asset('material-kit/js/core/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('material-kit/js/core/bootstrap-material-design.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('material-kit/js/plugins/moment.min.js') }}"></script>
<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<!-- <script src="{{ asset('material-kit/js/plugins/bootstrap-datetimepicker.js') }}" type="text/javascript"></script> -->
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="{{ asset('material-kit/js/plugins/nouislider.min.js') }}" type="text/javascript"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('material-kit/js/material-kit.js?v=2.0.5') }}" type="text/javascript"></script>

<script>
  $(window).on('load', function() {
    $('.transaction_loader').addClass('hide')
  })
</script>
</body>
</html>