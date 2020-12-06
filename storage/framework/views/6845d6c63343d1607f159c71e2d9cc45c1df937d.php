<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title'); ?>-WeAdmin Frame型后台管理系统-WeAdmin 1.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset(_ADMIN_ . '/static/css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset(_ADMIN_ . '/static/css/weadmin.css')); ?>">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<?php $__env->startSection('content'); ?>
    <div class="form-group">
        <?php if(count($errors) > 0): ?>
            <div class="alert alert-danger">
                <ul style="color:red;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
<?php echo $__env->yieldSection(); ?>

<?php $__env->startSection('script'); ?>

<?php echo $__env->yieldSection(); ?>

<?php $__env->startSection('js'); ?>

<?php echo $__env->yieldSection(); ?>

</body>

</html>