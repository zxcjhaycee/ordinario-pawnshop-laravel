<style>
@media(max-width: 992px){
  .sidebar .nav li .dropdown-menu a, .sidebar .nav li a {
  color: #ffffff;
  }
  .sidebar .nav li:hover>a {
    color: #ffffff;
  }
}

</style>
   <!-- Start of Navigation -->
   <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="#pablo">
                
            </a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="#pablo">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Stats
                  </p>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">notifications</i>
                  <span class="notification">0</span>
                  <p class="d-lg-none d-md-block">Notifications</p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Notification</a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <span> {{  Auth::user()->first_name }}  </span>  
                  <i class="material-icons">person</i>

                  <p class="d-lg-none d-md-block">
                    
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="#">Profile</a>
                  <a class="dropdown-item" href="#">Settings</a>
                  <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="post" id="logout">
                        @csrf
                        <!-- <button class="dropdown-item" type="submit">Logout</button> -->
                        <a class="dropdown-item" href="javascript:{}" onclick="document.getElementById('logout').submit();">Logout</a>

                    </form>
                    <!-- <a class="dropdown-item" href="{{ route('logout')}}">Log out</a> -->
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>