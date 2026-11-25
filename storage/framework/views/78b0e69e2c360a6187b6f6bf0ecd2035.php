<?php $__env->startSection('title', 'Modération — Ensaj Clubs'); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Modération']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Modération']])]); ?>
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
    <div class="page-head"><h1>Modération</h1></div>

    <nav class="tabs" aria-label="Onglets de modération">
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="<?php if($tab === $key): ?> is-active <?php endif; ?>" href="<?php echo e(route('admin.moderation', ['tab' => $key])); ?>">
                <?php echo e(ucfirst($label)); ?> <span class="badge badge--pending"><?php echo e($counts[$key]); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $name = match($tab) {
                'user' => $item->full_name . ' — ' . $item->email,
                'post' => $item->title . ' (' . $item->club->name . ')',
                'team' => $item->name . ' — ' . $item->position . ' (' . $item->club->name . ')',
                'media' => ($item->title ?: $item->type->label()) . ' (' . $item->club->name . ')',
            };
        ?>
        <div class="card" style="margin-bottom: .75rem;">
            <div class="row spread">
                <div>
                    <strong><?php echo e($name); ?></strong>
                    <div class="muted">Proposé le <?php echo e($item->created_at?->format('d/m/Y H:i')); ?></div>
                </div>
                <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $item->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
            </div>
            <div class="row mt-2">
                <form method="POST" action="<?php echo e(route('admin.moderation.approve', [$tab, $item->id])); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn--small" type="submit">Valider</button>
                </form>
                <form method="POST" action="<?php echo e(route('admin.moderation.reject', [$tab, $item->id])); ?>" class="row" style="flex: 1;">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="rejection_reason" placeholder="Motif du refus (obligatoire)" value="<?php echo e(old('rejection_reason')); ?>" required maxlength="1000" style="flex: 1; min-width: 200px; padding: .45rem .6rem; border: 1px solid var(--color-border); border-radius: var(--radius);">
                    <button class="btn btn--small btn--danger" type="submit">Refuser</button>
                </form>
            </div>
            <?php $__errorArgs = ['rejection_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-state">Rien en attente dans cet onglet.</div>
    <?php endif; ?>
    <div class="mt-2"><?php echo e($items->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/admin/moderation.blade.php ENDPATH**/ ?>