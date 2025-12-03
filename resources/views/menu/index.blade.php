<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>PILIH MENU</title>
	<link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
	<script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css') }}/styles.css" rel="stylesheet" />
	<style>
		.bg-gradient
		{
			background: #26C784;
			background: -webkit-linear-gradient(to right, #11998e, #26C784);
			background: linear-gradient(to right, #11998e, #26C784);
		}

		.card
		{
			-webkit-box-shadow: 0 8px 6px -6px #CCCCCC;
			-moz-box-shadow: 0 8px 6px -6px #CCCCCC;
			box-shadow: 0 8px 6px -6px #CCCCCC;
		}
		.col-md-4
		{
			margin-bottom: 30px;
		}
	</style>
</head>
<body id="page-top">

	<nav class="navbar navbar-expand-lg  text-uppercase fixed-top" style="background:#ED1A3B;" id="mainNav">
		<div class="container">
            <a class="navbar-brand js-scroll-trigger float-left" href="/">PT CHAROEN POKHPAND INDONESIA PLANT BANDUNG.</a>
            <a href="{{ route('logout') }}" class="float-right"><img height="50px;" src="{{ asset('img') }}/check-out.png" alt=""></a>
		</div>
	</nav>

	<header class="text-white text-center" style="margin-top: 150px;"></header>

	<div class="container" style="margin-bottom:50px; ">
		<h5 style="color: #ED1A3B;" class="text-center">"PILIH WAREHOUSE"</h5>
		<div class="row justify-content-center" style="margin-top: 50px;">
			
			@foreach ($gudang as $g)
            <div class="col-md-4">
				<a href="{{ route('addSessionGudang',$g->id) }}">
					<div class="card">
						<div class="card-body">
							<center>
								<img width="100%" height="250px;" src="{{ asset('img') }}/warehouse.png" alt="">
							</center>
							<hr>
							<h5 class="text-center" style="color: #ED1A3B;">{{ $g->nm_gudang }}</h5>
						</div>
					</div>
				</a>
			</div>
            @endforeach
			
			

		</div>
	</div>

	<div class="py-4 text-center text-white" style="background:#ED1A3B;">
		<div class="container">Copyright &copy; PT CHAROEN POKHPAND INDONESIA PLANT BANDUNG.</strong>
			All rights reserved.</div>
	</div>

	<div class="scroll-to-top d-lg-none position-fixed">
		<a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

</body>
</html>
