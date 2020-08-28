<style>
 .nav-link .form-group select.form-control {
        position: static;
        margin-top:-30px
  }
@media(max-width: 992px){
  .sidebar .nav li .dropdown-menu a, .sidebar .nav li a {
  color: #ffffff;
  }
  .sidebar .nav li:hover>a {
    color: #ffffff;
  }
  .sidebar .nav li.active>[data-toggle=collapse], .sidebar .nav li .dropdown-menu a:focus, .sidebar .nav li .dropdown-menu a:hover, .sidebar .nav li:hover>a{
    color : #ffffff;
  }
  #branch_select {
    color: #ffffff;

  }
  #branch_select option {
    color: black;

  }
  .nav-link .form-group select.form-control {
        margin-top:-20px;
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
            <ul class="navbar-nav" style="margin-top:-15px">
              <li class="nav-item">
                <a class="nav-link">
                  <select name="branch_id" id="branch_select" class="form-control" style="width:190px;" onChange="changeBranch()">
                      @foreach(\App\Branch::all() as $key => $value)
                        <option value="{{ $value->id }}" {{ Auth::user()->branch_id == $value->id ? 'selected' : '' }}>{{ $value->branch }}</option>
                      @endforeach
                  </select>
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
      @push('scripts')
    <script>
        let branch_orig = document.getElementById('branch_select').value;
      function changeBranch(){
        let branch_id = document.getElementById('branch_select').value;
        const r = confirm("Do you want to continue?");
        if(r){
          $.ajax({
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          type: "POST",
          url : '{{ route('branch.updateUserBranch') }}',
          data: {
            "_method": 'PUT',
            'id' : branch_id
          },
          success: (data) => {
            // console.log(data);
            if(data['status'] == 'success'){
              location.reload();
            }
            
          }
        })
        }else{
          $('#branch_select').val(branch_orig);
          // console.log(branch_id);
          // console.log(branch_orig);
        }

      }
    </script>
@endpush