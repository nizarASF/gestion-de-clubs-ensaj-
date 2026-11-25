<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Ensaj Clubs'); ?></title>
    <link rel="icon" href="<?php echo e(asset('images/club-default.svg')); ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="<?php echo e(route('home')); ?>">Ensaj<span class="brand-dot">Clubs</span></a>
        <button class="nav-toggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>
        <nav class="site-nav" aria-label="Navigation principale">
            <ul>
                <li><a class="nav-link" href="<?php echo e(route('home')); ?>">Accueil</a></li>
                <?php if(auth()->guard()->guest()): ?>
                    <li><a class="nav-link" href="<?php echo e(route('login')); ?>">Se connecter</a></li>
                    <li><a class="nav-link" href="<?php echo e(route('register')); ?>">S'inscrire</a></li>
                <?php endif; ?>
                <?php if(auth()->guard()->check()): ?>
                    <li><a class="nav-link" href="<?php echo e(route('profile.edit')); ?>"><span class="user-chip"><?php echo e(Auth::user()->full_name); ?></span></a></li>
                    <li>
                        <a class="nav-link" href="<?php echo e(route('notifications.index')); ?>" aria-label="Notifications">
                            <span class="nav-icon">🔔</span><span>Notifications</span>
                            <?php if(($unreadNotificationsCount ?? 0) > 0): ?><span class="nav-count"><?php echo e($unreadNotificationsCount); ?></span><?php endif; ?>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="<?php echo e(route('conversations.index')); ?>" aria-label="Messages">
                            <span class="nav-icon">✉️</span><span>Messages</span>
                            <?php if(($unreadMessagesCount ?? 0) > 0): ?><span class="nav-count"><?php echo e($unreadMessagesCount); ?></span><?php endif; ?>
                        </a>
                    </li>
                    <?php if(Auth::user()->isClubMember()): ?>
                        <li><a class="nav-link" href="<?php echo e(route('manage.dashboard')); ?>">Espace membre</a></li>
                    <?php endif; ?>
                    <?php if(Auth::user()->isBde()): ?>
                        <li><a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">Administration</a></li>
                    <?php endif; ?>
                    <li>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="nav-link btn--link" type="submit">Se déconnecter</button>
                        </form>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main class="container">
    <?php echo $__env->make('components.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="site-footer">
    <div>Ensaj Clubs — Projet universitaire de développement web · <a href="<?php echo e(route('home')); ?>">Accueil</a></div>
</footer>

<script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/layouts/app.blade.php ENDPATH**/ ?>