<aside class="col-md-3 left_col" aria-label="Sidebar navigation">
  <div class="left_col scroll-view">
    <!-- Logo -->
    <div class="navbar nav_title border-0">
      <a href="{{ url('/') }}" class="site_title">
        <img src="{{ asset('assets/logo.svg') }}" alt="Hoja" class="logo-full logo-main" loading="lazy">
        <img src="{{ asset('assets/logo-icon.svg') }}" alt="Hoja" class="logo-icon" loading="lazy">
        <span>Hoja</span>
      </a>
    </div>

    <div class="clearfix"></div>

    <!-- Profile Section -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="{{ asset('assets/img.jpg') }}" alt="{{ __('User profile photo') }}" class="img-circle profile_img" loading="lazy">
      </div>
      <div class="profile_info">
        <span>{{ __('Welcome') }},</span>
        <h4>{{ __('Guest') }}</h4>
      </div>
    </div>

    <br />

    <!-- Sidebar Menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>{{ __('General') }}</h3>
        <ul class="nav side-menu">
          <li>
            <a href="{{ url('/') }}">
              <i class="bi bi-house"></i>
              <span>{{ __('Dashboard') }}</span>
            </a>
          </li>
          <li>
            <a>
              <i class="bi bi-pencil-square"></i>
              <span>{{ __('Forms') }}</span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#">{{ __('General Form') }}</a></li>
              <li><a href="#">{{ __('Advanced Components') }}</a></li>
              <li><a href="#">{{ __('Form Validation') }}</a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="bi bi-display"></i>
              <span>{{ __('UI Elements') }}</span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#">{{ __('General Elements') }}</a></li>
              <li><a href="#">{{ __('Media Gallery') }}</a></li>
              <li><a href="#">{{ __('Typography') }}</a></li>
              <li><a href="#">{{ __('Icons') }}</a></li>
              <li><a href="#">{{ __('Widgets') }}</a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="bi bi-table"></i>
              <span>{{ __('Tables') }}</span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#">{{ __('Tables') }}</a></li>
              <li><a href="#">{{ __('Table Dynamic') }}</a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="bi bi-bar-chart"></i>
              <span>{{ __('Data Presentation') }}</span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#">{{ __('Chart JS') }}</a></li>
              <li><a href="#">{{ __('ECharts') }}</a></li>
            </ul>
          </li>
        </ul>
      </div>

      <div class="menu_section">
        <h3>{{ __('Settings') }}</h3>
        <ul class="nav side-menu">
          <li>
            <a>
              <i class="bi bi-gear"></i>
              <span>{{ __('Settings') }}</span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#">{{ __('Profile') }}</a></li>
              <li><a href="#">{{ __('Preferences') }}</a></li>
            </ul>
          </li>
          <li>
            <a href="{{ url('/login') }}">
              <i class="bi bi-box-arrow-in-right"></i>
              <span>{{ __('Login') }}</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</aside>
