<?php $__env->startSection('title', 'Meet the Team — ' . $club->name); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('home')], ['label' => $club->name, 'url' => route('clubs.show', $club)], ['label' => 'Meet the Team']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('home')], ['label' => $club->name, 'url' => route('clubs.show', $club)], ['label' => 'Meet the Team']])]); ?>
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
    <div class="page-head">
        <h1>Meet the Team</h1>
        <form method="GET" action="<?php echo e(route('clubs.team', $club)); ?>" class="row" id="team-year-form">
            <div class="form-row">
                <label for="year">Année académique</label>
                <select id="year" name="year">
                    <?php $__empty_1 = true; $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <option value="<?php echo e($option); ?>" <?php if($option === $year): echo 'selected'; endif; ?>><?php echo e($option); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <option value="">Aucune année disponible</option>
                    <?php endif; ?>
                </select>
            </div>
            <noscript><button class="btn" type="submit">Afficher</button></noscript>
        </form>
    </div>

    <div class="grid grid-cards">
        <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="team-card">
                <?php if (isset($component)) { $__componentOriginal48d39083cd02d2187a96a5cc1c848cd1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48d39083cd02d2187a96a5cc1c848cd1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar-initials','data' => ['name' => $member->name,'photo' => $member->photo_path]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('avatar-initials'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($member->name),'photo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($member->photo_path)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48d39083cd02d2187a96a5cc1c848cd1)): ?>
<?php $attributes = $__attributesOriginal48d39083cd02d2187a96a5cc1c848cd1; ?>
<?php unset($__attributesOriginal48d39083cd02d2187a96a5cc1c848cd1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48d39083cd02d2187a96a5cc1c848cd1)): ?>
<?php $component = $__componentOriginal48d39083cd02d2187a96a5cc1c848cd1; ?>
<?php unset($__componentOriginal48d39083cd02d2187a96a5cc1c848cd1); ?>
<?php endif; ?>
                <h3><?php echo e($member->name); ?></h3>
                <p class="muted"><?php echo e($member->position); ?></p>
                <small><?php echo e($member->academic_year); ?></small>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">Aucune fiche validée pour cette année.</div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?><script src="<?php echo e(asset('js/team-year.js')); ?>" defer></script><?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/public/clubs/team.blade.php ENDPATH**/ ?>