<!DOCTYPE html>
<html lang="zxx">
<head>
	@include('frontend.layouts.head')	
</head>
<body class="js">
	
	<!-- Preloader -->
	<div class="preloader">
		<div class="preloader-inner">
			<div class="preloader-icon">
				<span></span>
				<span></span>
			</div>
		</div>
	</div>
	<!-- End Preloader -->
	
	@include('frontend.layouts.notification')
	<!-- Header -->
	@include('frontend.layouts.header2')
	<!--/ End Header -->
	@yield('main-content')
	
	@include('frontend.layouts.footer2')

</body>
</html>