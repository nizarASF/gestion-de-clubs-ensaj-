<?php $__env->startSection('title', 'Conversation — Ensaj Clubs'); ?>
<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [['label' => 'Accueil', 'url' => route('home')], ['label' => 'Mes conversations', 'url' => route('conversations.index')], ['label' => 'Conversation']]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['label' => 'Accueil', 'url' => route('home')], ['label' => 'Mes conversations', 'url' => route('conversations.index')], ['label' => 'Conversation']])]); ?>
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
        <h1><?php if($isInitiator): ?> <?php echo e($conversation->club->name); ?> <?php else: ?> <?php echo e($conversation->user->full_name); ?> <?php endif; ?></h1>
        <a class="btn btn--small btn--secondary" href="<?php echo e(route('conversations.index')); ?>">Retour aux conversations</a>
    </div>

    <div class="chat" id="chat"
         data-store-url="<?php echo e(route('conversations.messages.store', $conversation)); ?>"
         data-fetch-url="<?php echo e(route('conversations.messages.index', $conversation)); ?>">
        <div class="chat__messages" id="chat-messages" data-last-id="<?php echo e($conversation->messages->last()?->id ?? 0); ?>">
            <?php $__empty_1 = true; $__currentLoopData = $conversation->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $mine = $message->author_id === auth()->id();
                    $unread = ! $mine && $message->read_at === null && $message->from_club === $isInitiator;
                ?>
                <article class="bubble <?php if($mine): ?> bubble--mine <?php endif; ?> <?php if($unread): ?> bubble--unread <?php endif; ?>">
                    <div class="bubble__head">
                        <strong><?php echo e($message->author->full_name); ?></strong>
                        <span><?php echo e($message->created_at->format('d/m/Y H:i')); ?></span>
                        <?php if($message->type): ?><span class="badge badge--info"><?php echo e($message->type->label()); ?></span><?php endif; ?>
                        <?php if($unread): ?><span class="badge badge--pending">Non lu</span><?php endif; ?>
                    </div>
                    <div class="bubble__body"><?php echo e($message->body); ?></div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="chat__empty">Aucun message. Écrivez le premier !</p>
            <?php endif; ?>
        </div>

        <form id="chat-form" class="card form-grid">
            <?php echo csrf_field(); ?>
            <?php if($isInitiator): ?>
                <div class="form-row">
                    <label for="chat-type">Type de message</label>
                    <select id="chat-type" name="type">
                        <?php $__currentLoopData = \App\Enums\MessageType::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->value); ?>"><?php echo e($type->label()); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            <?php endif; ?>
            <div class="form-row">
                <label for="chat-body">Votre message</label>
                <textarea id="chat-body" name="body" required maxlength="2000" rows="3"></textarea>
                <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-actions"><button class="btn" type="submit">Envoyer</button></div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?><script src="<?php echo e(asset('js/chat.js')); ?>" defer></script><?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/conversations/show.blade.php ENDPATH**/ ?>