<aside class="col-md-3 left_col" aria-label="Sidebar navigation">
  <div class="left_col scroll-view">
    <!-- Logo -->
    <div class="navbar nav_title border-0">
      <a href="<?php echo e(url('/')); ?>" class="site_title">
        <img src="<?php echo e(asset('assets/logo.svg')); ?>" alt="Hoja" class="logo-full logo-main" loading="lazy">
        <img src="<?php echo e(asset('assets/logo-icon.svg')); ?>" alt="Hoja" class="logo-icon" loading="lazy">
        <span>Hoja</span>
      </a>
    </div>

    <div class="clearfix"></div>

    <!-- Profile Section -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="<?php echo e(asset('assets/img.jpg')); ?>" alt="<?php echo e(__('User profile photo')); ?>" class="img-circle profile_img" loading="lazy">
      </div>
      <div class="profile_info">
        <span><?php echo e(__('Welcome')); ?>,</span>
        <h4><?php echo e(__('Guest')); ?></h4>
      </div>
    </div>

    <br />

    <!-- Sidebar Menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3><?php echo e(__('General')); ?></h3>
        <ul class="nav side-menu">
          <li>
            <a href="<?php echo e(url('/')); ?>">
              <i class="bi bi-house"></i>
              <span><?php echo e(__('Dashboard')); ?></span>
            </a>
          </li>
          <li>
            <a>
              <i class="bi bi-pencil-square"></i>
              <span><?php echo e(__('Forms')); ?></span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#"><?php echo e(__('General Form')); ?></a></li>
              <li><a href="#"><?php echo e(__('Advanced Components')); ?></a></li>
              <li><a href="#"><?php echo e(__('Form Validation')); ?></a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="bi bi-display"></i>
              <span><?php echo e(__('UI Elements')); ?></span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#"><?php echo e(__('General Elements')); ?></a></li>
              <li><a href="#"><?php echo e(__('Media Gallery')); ?></a></li>
              <li><a href="#"><?php echo e(__('Typography')); ?></a></li>
              <li><a href="#"><?php echo e(__('Icons')); ?></a></li>
              <li><a href="#"><?php echo e(__('Widgets')); ?></a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="bi bi-table"></i>
              <span><?php echo e(__('Tables')); ?></span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#"><?php echo e(__('Tables')); ?></a></li>
              <li><a href="#"><?php echo e(__('Table Dynamic')); ?></a></li>
            </ul>
          </li>
          <li>
            <a>
              <i class="bi bi-bar-chart"></i>
              <span><?php echo e(__('Data Presentation')); ?></span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#"><?php echo e(__('Chart JS')); ?></a></li>
              <li><a href="#"><?php echo e(__('ECharts')); ?></a></li>
            </ul>
          </li>
        </ul>
      </div>

      <div class="menu_section">
        <h3><?php echo e(__('Settings')); ?></h3>
        <ul class="nav side-menu">
          <li>
            <a>
              <i class="bi bi-gear"></i>
              <span><?php echo e(__('Settings')); ?></span>
              <span class="bi bi-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
              <li><a href="#"><?php echo e(__('Profile')); ?></a></li>
              <li><a href="#"><?php echo e(__('Preferences')); ?></a></li>
            </ul>
          </li>
          <li>
            <a href="<?php echo e(url('/login')); ?>">
              <i class="bi bi-box-arrow-in-right"></i>
              <span><?php echo e(__('Login')); ?></span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</aside>
<?php /**PATH /workspaces/hoja/views/partials/sidebar.blade.php ENDPATH**/ ?>