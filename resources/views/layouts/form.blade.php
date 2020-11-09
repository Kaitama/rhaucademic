<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>{{env('APP_NAME')}} - @yield('pagetitle')</title>
	
	<link rel="stylesheet" href="{{asset('assets/semantic/semantic.min.css')}}" />
	
	<link
	rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
	integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ="
	crossorigin="anonymous"
	/>
	<link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />

	<style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>
</head>

<body>
	
	@yield('form')
	
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('assets/semantic/semantic.min.js')}}"></script>
	<script src="{{asset('assets/js/script.js')}}"></script>
	<script>
		$('.ui.checkbox').checkbox();
	</script>
</body>
</html>
