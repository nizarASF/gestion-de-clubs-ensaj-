<?php $__env->startSection('title', 'Utilisateurs — Ensaj Clubs'); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Utilisateurs']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('home')], ['label' => 'Administration', 'url' => route('admin.dashboard')], ['label' => 'Utilisateurs']])]); ?>
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
    <div class="page-head"><h1>Utilisateurs</h1></div>

    <form class="filters" method="GET" action="<?php echo e(route('admin.users.index')); ?>">
        <div class="form-row">
            <label for="q">Rechercher</label>
            <input id="q" type="text" name="q" value="<?php echo e($search); ?>" placeholder="Nom ou email">
        </div>
        <div class="form-row">
            <label for="role">Rôle</label>
            <select id="role" name="role">
                <option value="">Tous</option>
                <?php $__currentLoopData = \App\Enums\Role::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleCase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($roleCase->value); ?>" <?php if($role === $roleCase->value): echo 'selected'; endif; ?>><?php echo e($roleCase->label()); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button class="btn" type="submit">Filtrer</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Utilisateur</th><th>Filière</th><th>Rôle actuel</th><th>Changer le rôle</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($user->full_name); ?></strong><br><small><?php echo e($user->email); ?></small></td>
                    <td><?php echo e($user->field_of_study?->label()); ?></td>
                    <td><?php echo e($user->role->label()); ?><?php if($user->club): ?> <br><small><?php echo e($user->club->name); ?></small><?php endif; ?></td>
                    <td>
                        <?php if($user->id === auth()->id()): ?>
                            <span class="muted">Vous-même</span>
                        <?php else: ?>
                            <form method="POST" action="<?php echo e(route('admin.users.updateRole', $user)); ?>" class="row">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <select name="role" aria-label="Nouveau rôle" style="max-width: 150px;">
                                    <?php $__currentLoopData = [\App\Enums\Role::Student, \App\Enums\Role::ClubMember, \App\Enums\Role::Bde]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleCase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($roleCase->value); ?>" <?php if($user->role === $roleCase): echo 'selected'; endif; ?>><?php echo e($roleCase->label()); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <select name="club_id" aria-label="Club (pour les membres)" style="max-width: 170px;">
                                    <option value="">— Club —</option>
                                    <?php $__currentLoopData = $clubs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $club): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($club->id); ?>" <?php if($user->club_id === $club->id): echo 'selected'; endif; ?>><?php echo e($club->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button class="btn btn--small" type="submit">Appliquer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="empty-state">Aucun utilisateur trouvé.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-2"><?php echo e($users->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/admin/users.blade.php ENDPATH**/ ?>