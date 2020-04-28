<div class="sidebar" data-color="danger" data-background-color="white">
      <!-- data-color="purple | azure | green | orange | danger" data-image="../assets/img/ordinario.jpg" -->
      <div class="logo">
        <a class="simple-text logo-normal">
          <img style="width:90%;" src="/img/Ordinario_Pawnshop_Logo.png">
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }} ">
            <a class="nav-link" href="{{ route('dashboard') }}">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ class_basename(Route::current()->controller) == 'PawnController' ? '' : 'collapsed'  }}" data-toggle="collapse" href="#pagesExamples" 
              aria-expanded="{{ class_basename(Route::current()->controller) == 'PawnController' ? 'true' : 'false' }}">
              <i class="material-icons">star</i>
              <p> Pawn & Auction
                <b class="caret"></b>
              </p>   

            </a>
            <div class="collapse {{ class_basename(Route::current()->controller) == 'PawnController' ? 'show' : ''  }} " id="pagesExamples">
              <ul class="nav">
                <li class="nav-item {{ Route::currentRouteName() == 'pawn' ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('pawn') }}">
                    <span class="sidebar-mini"> <i class="material-icons">star</i> </span>
                    <span class="sidebar-normal"> Sangla </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="">
                    <span class="sidebar-mini"> <i class="material-icons">star</i> </span>
                    <span class="sidebar-normal"> Renew & Redeem </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="">
                    <span class="sidebar-mini"> <i class="material-icons">star</i> </span>
                    <span class="sidebar-normal"> Auction </span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="nav-item ">
            <a class="nav-link {{ Route::current()->getPrefix() == '/reports' ? '' : 'collapsed'  }}" data-toggle="collapse" href="#reports" 
              aria-expanded="{{ Route::current()->getPrefix() == '/reports' ? 'true' : 'false'  }}">
              <i class="material-icons">assessment</i>
              <p> Reports
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse {{  Route::current()->getPrefix() == '/reports' ? 'show' : ''  }}" id="reports" style="">
              <ul class="nav">
                <li class="nav-item {{ Route::currentRouteName() == 'notice_listing' ? 'active' : '' }}">
                  <a class="nav-link" href="/reports/notice_listing">
                    <span class="sidebar-mini"> <i class="material-icons">book</i> </span>
                    <span class="sidebar-normal"> Notice Listing </span>
                  </a>
                </li>


              </ul>
            </div>
          </li>

          <li class="nav-item ">
            <a class="nav-link {{ Route::current()->getPrefix() == '/settings' ? '' : 'collapsed'  }}" data-toggle="collapse" href="#settings" 
              aria-expanded="{{ Route::current()->getPrefix() == '/settings' ? 'true' : 'false'  }}">
              <i class="material-icons">build</i>
              <p> Settings
                <b class="caret"></b>
              </p>
            </a>
            <div class="collapse {{  Route::current()->getPrefix() == '/settings' ? 'show' : ''  }}" id="settings" style="">
              <ul class="nav">
                <li class="nav-item {{ Route::currentRouteName() == 'rates' ? 'active' : '' }}">
                  <a class="nav-link" href="/settings/rates">
                    <span class="sidebar-mini"> <i class="material-icons">book</i> </span>
                    <span class="sidebar-normal"> Rates </span>
                  </a>
                </li>
                <li class="nav-item {{ class_basename(Route::current()->controller) == 'UserController' ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('user.index') }}">
                    <span class="sidebar-mini"> <i class="material-icons">people</i> </span>
                    <span class="sidebar-normal"> User </span>
                  </a>
                </li>
                <li class="nav-item {{ class_basename(Route::current()->controller) == 'BranchController' ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('branch.index') }}">
                    <span class="sidebar-mini"> <i class="material-icons">list</i> </span>
                    <span class="sidebar-normal"> Branch </span>
                  </a>
                </li>


              </ul>
            </div>
          </li>

        </ul>
      </div>
    </div>