<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="bi bi-list"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <!-- User Dropdown -->
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i>
            <?php echo e(__('Guest')); ?>

            <span class="bi bi-chevron-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('Profile')); ?></a>
            </li>
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('Settings')); ?></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item" href="<?php echo e(url('/login')); ?>">
                <i class="bi bi-box-arrow-in-right"></i> <?php echo e(__('Login')); ?>

              </a>
            </li>
          </ul>
        </li>

        <!-- Notifications -->
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell"></i>
            <span class="badge bg-danger">3</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('New notification')); ?></a>
            </li>
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('Another notification')); ?></a>
            </li>
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('View all notifications')); ?></a>
            </li>
          </ul>
        </li>

        <!-- Messages -->
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-envelope"></i>
            <span class="badge bg-warning">5</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('New message')); ?></a>
            </li>
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('Another message')); ?></a>
            </li>
            <li>
              <a class="dropdown-item" href="#"><?php echo e(__('View all messages')); ?></a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
<?php /**PATH /workspaces/hoja/views/partials/navbar.blade.php ENDPATH**/ ?>