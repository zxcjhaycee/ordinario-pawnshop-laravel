<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Ordinario Pawnshop</title>

  <!-- Font Awesome Icons -->
  <link href="/css/login/all.min.css" rel="stylesheet" type="text/css">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet"> -->
  <link href="/css/login/merriweather.css" rel="stylesheet" type="text/css">
  <!-- <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'> -->
  <link href="/css/login/merriweather_italic.css" rel="stylesheet" type="text/css">

 
  <!-- Plugin CSS -->
  <link href="/css/login/magnific-popup.css" rel="stylesheet">

  <!-- Theme CSS - Includes Bootstrap -->
  <!-- <link href="/css/login/creative.min.css" rel="stylesheet"> -->
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
  <link href="/css/login/bootstrap.min.css" rel="stylesheet">

</head>

<body>


<section class="h-100">
	<div class="align-items-center h-100">
		<div class="mx-auto col-md-8 col-sm-10 col-lg-7 col-xl-6 col-12">
			<img src="/img/ordinario_colored.png" alt="ordinario_pawnshop" class="img-fluid mb-5 d-block mx-auto rounded"  style="margin-top:3rem!important">
			<div class="card border">
				<div class="card-header  text-center">
						Ordinario Pawnshop
				</div>
				<div class="card-body">
				<form action="{{ route('login') }}" method="POST">
                @csrf

				<!-- <form action="dashboard.php"> -->
					<div class="row col-xl-12 spinner-display spinner-table" style="justify-content:center;height:20vh;align-items: center;display:none">
							<div class="spinner-border text-success" style="width: 3rem; height: 3rem;" role="status">
								<span class="sr-only">Loading...</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="staticEmail" class="col-sm-4 col-12 col-lg-3 col-xl-3 col-form-label"> <i class="fas fa-user-circle"></i>Username</label>
							<div class="col-sm-8 col-12 col-lg-9 col-xl-8">
							<input type="text" class="form-control  @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}">
                              @error('username')
                                <span class="text-danger">
                                        {{ $message }}
                                </span>
                                @enderror
							</div>

						</div>
						<div class="form-group row">
							<label for="inputPassword" class="col-sm-4 col-12 col-lg-3 col-xl-3 col-form-label"> <i class="fas fa-key"></i>Password</label>
							<div class="input-group col-sm-8 col-12 col-lg-9 col-xl-8">
								<input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
								<div class="input-group-append" style="border :1px solid #ced4da;border-left:0px;border-radius:.25rem;margin-left:-5px;margin-top:0px;height:38px">
									<button type="button" class="btn btn-outline" id="showPassword" data-id="off"><i class="fas fa-eye"></i></button>
								</div>

							</div>
                            @error('password')
                                <span class="col-sm-4 col-md-4 col-lg-3"></span>
                                <span class="text-danger col-sm-8">
                                        {{ $message }}
                                </span>
                                @enderror
						</div>
							<div class="form-group row">
								<label class="offset-lg-4 offset-5 offset-sm-5 form-check-label">
									<input class="form-check-input" type="checkbox"> Remember me
								</label>
							</div>
							<div class="col-xl-12">
								<button class="btn btn-primary mt-2 mb-1" style="width:100%">Sign In</button>
							</div>
					</form>
					<div>
						<hr>
						<h6 class="text-center"> <a href="#" class="text-danger"> <i class="fas fa-exclamation-circle"></i>  Forgot Password? </a></h6>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

  <!-- Footer -->
  <footer class="bg-light py-5">
    <div class="container">
      <div class="small text-center text-muted">Copyright &copy; <?= date('Y') ?> - Ordinario Pawnshop</div>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="js/login/jquery.min.js"></script>
  <script src="js/login/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="js/login/jquery.easing.min.js"></script>
  <script src="js/login/jquery.magnific-popup.min.js"></script>

  <!-- Custom scripts for this template -->
  <!-- <script src="js/login/creative.min.js"></script> -->

  <script src="js/login/popper.min.js"></script>
  <script src="js/login/bootstrap.min.js"></script>
  <script>
        $(document).ready(function(){
      $(document).on('click', '#showPassword', function(){
        let state = $(this).attr('data-id');
        if(state == 'off'){
          // $(this).data('id', 'on');
          $(this).attr('data-id', 'on');
          $('#password').attr('type', 'text');
          $(this).html('<i class="fas fa-eye-slash"></i>');
        }else{
          // $(this).data('id', 'off');
          $(this).attr('data-id', 'off');
          $('#password').attr('type', 'password');
          $(this).html('<i class="fas fa-eye"></i>');
        }
      });
    });

  </script>
</body>

</html>
