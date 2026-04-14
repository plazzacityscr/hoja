<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>

                <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <!-- /page content -->
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Gentelella JS -->
    <script src="<?php echo e(asset('assets/js/gentelella.js')); ?>"></script>

    <!-- Page-specific scripts -->
    <?php echo $__env->yieldContent('scripts'); ?>

    <!-- Additional scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /workspaces/hoja/views/layouts/app.blade.php ENDPATH**/ ?>