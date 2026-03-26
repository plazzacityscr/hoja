<?php $__env->startSection('title', 'Hoja - Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="dashboard_graph">
      <div class="row x_title">
        <div class="col-md-6">
          <h3><?php echo e(__('Welcome to Hoja')); ?></h3>
        </div>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo e(__('Dashboard')); ?></h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p><?php echo e(__('This is the main dashboard page.')); ?></p>

            <!-- Stats Tiles -->
            <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_390">
                  <div class="x_title">
                    <h2><?php echo e(__('Quick Stats')); ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="dashboard-widget-content">
                      <ul class="list-unstyled timeline widget">
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span><?php echo e(__('Total Users')); ?></span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#">0</a>
                              </h2>
                              <div class="byline">
                                <span><?php echo e(__('Active users')); ?></span>
                              </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span><?php echo e(__('Total Projects')); ?></span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#">0</a>
                              </h2>
                              <div class="byline">
                                <span><?php echo e(__('Active projects')); ?></span>
                              </div>
                            </div>
                          </div>
                        </li>
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span><?php echo e(__('Total Analyses')); ?></span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#">0</a>
                              </h2>
                              <div class="byline">
                                <span><?php echo e(__('Completed analyses')); ?></span>
                              </div>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-9 col-sm-9 col-xs-12">
                <div class="x_panel tile fixed_height_390">
                  <div class="x_title">
                    <h2><?php echo e(__('Recent Activity')); ?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="dashboard-widget-content">
                      <ul class="list-unstyled timeline widget">
                        <li>
                          <div class="block">
                            <div class="tags">
                              <a href="#" class="tag">
                                <span><?php echo e(__('System')); ?></span>
                              </a>
                            </div>
                            <div class="block_content">
                              <h2 class="title">
                                <a href="#"><?php echo e(__('Welcome to Hoja')); ?></a>
                              </h2>
                              <div class="byline">
                                <span><?php echo e(date('Y-m-d H:i:s')); ?></span>
                              </div>
                              <p class="excerpt">
                                <?php echo e(__('Hoja is a modern PHP application built with Leaf framework and Gentelella dashboard template.')); ?>

                              </p>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Page-specific scripts -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /workspaces/hoja/views/index.blade.php ENDPATH**/ ?>