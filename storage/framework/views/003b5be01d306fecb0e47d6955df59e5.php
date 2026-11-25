<?php $__env->startSection('title', 'Espace membre — Ensaj Clubs'); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('home')], ['label' => 'Espace membre']])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
    <div class="page-head"><h1>Espace membre — <?php echo e($club->name); ?></h1></div>

    <div class="grid grid-4">
        <div class="stat-card"><div class="stat-card__number"><?php echo e($posts['draft']); ?></div><div class="stat-card__label">Brouillons</div></div>
        <div class="stat-card"><div class="stat-card__number"><?php echo e($posts['pending']); ?></div><div class="stat-card__label">Posts en attente</div></div>
        <div class="stat-card"><div class="stat-card__number"><?php echo e($posts['approved']); ?></div><div class="stat-card__label">Posts publiés</div></div>
        <div class="stat-card"><div class="stat-card__number"><?php echo e($posts['rejected']); ?></div><div class="stat-card__label">Posts refusés</div></div>
        <div class="stat-card"><div class="stat-card__number"><?php echo e($mediaPending); ?></div><div class="stat-card__label">Médias en attente</div></div>
        <div class="stat-card"><div class="stat-card__number"><?php echo e($mediaRejected); ?></div><div class="stat-card__label">Médias refusés</div></div>
        <div class="stat-card"><div class="stat-card__number"><?php echo e($teamPending); ?></div><div class="stat-card__label">Fiches en attente</div></div>
        <div class="stat-card"><div class="stat-card__number"><?php echo e($unread); ?></div><div class="stat-card__label">Messages non lus</div></div>
    </div>
    <div class="section row">
        <a class="btn" href="<?php echo e(route('manage.posts.create')); ?>">Nouveau post</a>
        <a class="btn" href="<?php echo e(route('manage.team.create')); ?>">Proposer une fiche</a>
        <a class="btn" href="<?php echo e(route('manage.media.create')); ?>">Ajouter un média</a>
        <a class="btn btn--secondary" href="<?php echo e(route('conversations.index')); ?>">Boîte du club</a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/manage/dashboard.blade.php ENDPATH**/ ?>