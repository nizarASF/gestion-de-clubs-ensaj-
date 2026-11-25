<?php $__env->startSection('title', $club->name . ' — Ensaj Clubs'); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('home')], ['label' => $club->name]]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('home')], ['label' => $club->name]])]); ?>
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

    <section class="card">
        <div class="row">
            <?php if($club->logo_path): ?>
                <img src="<?php echo e(Storage::disk('public')->url($club->logo_path)); ?>" alt="Logo de <?php echo e($club->name); ?>" style="width: 90px; height: 90px; object-fit: contain;">
            <?php else: ?>
                <img src="<?php echo e(asset('images/club-default.svg')); ?>" alt="Logo par défaut" style="width: 90px; height: 90px;">
            <?php endif; ?>
            <div>
                <h1>Introduction de <?php echo e($club->name); ?></h1>
                <p class="muted"><?php echo e($club->short_description); ?></p>
            </div>
        </div>
        <div class="mt-2"><?php echo nl2br(e($club->description)); ?></div>
    </section>

    <?php if($media->isNotEmpty()): ?>
        <section class="section" id="galerie">
            <div class="section-head"><h2>Réels / vidéos</h2><span class="muted"><?php echo e($media->count()); ?> média(s)</span></div>
            <div class="thumb-grid" id="gallery-grid">
                <?php $__currentLoopData = $media; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="thumb">
                        <?php if($item->type === \App\Enums\MediaType::Photo && $item->thumbnail_path): ?>
                            <button class="thumb-button" type="button" data-lightbox-src="<?php echo e(Storage::disk('public')->url($item->path)); ?>" aria-label="Agrandir <?php echo e($item->title ?: 'la photo'); ?>">
                                <img src="<?php echo e(Storage::disk('public')->url($item->thumbnail_path)); ?>" alt="<?php echo e($item->title ?: 'Photo du club'); ?>" loading="lazy">
                            </button>
                        <?php elseif($item->type === \App\Enums\MediaType::Video): ?>
                            <video controls preload="metadata" src="<?php echo e(Storage::disk('public')->url($item->path)); ?>" aria-label="<?php echo e($item->title ?: 'Vidéo du club'); ?>"></video>
                        <?php else: ?>
                            <img src="<?php echo e(Storage::disk('public')->url($item->path)); ?>" alt="<?php echo e($item->title ?: 'Photo du club'); ?>" loading="lazy">
                        <?php endif; ?>
                        <?php if($item->title): ?><div class="thumb__label"><?php echo e($item->title); ?></div><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="section" id="posts">
        <div class="section-head"><h2>Actualités</h2></div>
        <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="post-card">
                <h3><?php echo e($post->title); ?></h3>
                <div class="post-card__meta">Par <?php echo e($post->author->full_name); ?> · <?php echo e($post->published_at?->format('d/m/Y H:i')); ?></div>
                <div><?php echo nl2br(e($post->body)); ?></div>
                <?php if($post->images->isNotEmpty()): ?>
                    <div class="post-images">
                        <?php $__currentLoopData = $post->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img src="<?php echo e(Storage::disk('public')->url($image->thumbnail_path ?: $image->path)); ?>" alt="Image du post <?php echo e($post->title); ?>" loading="lazy">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">Aucune actualité publiée pour ce club.</div>
        <?php endif; ?>
        <?php echo e($posts->links()); ?>

    </section>

    <div class="card row" style="justify-content: center; gap: 1rem;">
        <a class="btn" href="<?php echo e(route('conversations.contact', $club)); ?>">Contacter le club</a>
        <a class="btn btn--secondary" href="<?php echo e(route('clubs.team', $club)); ?>">Meet the Team</a>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/gallery.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/public/clubs/show.blade.php ENDPATH**/ ?>