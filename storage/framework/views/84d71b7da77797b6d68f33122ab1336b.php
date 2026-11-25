<?php if(session('status')): ?>
    <div class="alert alert--success" role="status">
        <button class="alert-dismiss" type="button" data-dismiss-alert aria-label="Fermer">×</button>
        <?php echo e(session('status')); ?>

    </div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert--danger" role="alert">
        <button class="alert-dismiss" type="button" data-dismiss-alert aria-label="Fermer">×</button>
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>
<?php if($errors->any()): ?>
    <div class="alert alert--danger" role="alert">
        <button class="alert-dismiss" type="button" data-dismiss-alert aria-label="Fermer">×</button>
        <strong>Veuillez corriger les erreurs ci-dessous.</strong>
    </div>
<?php endif; ?>
<?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/components/flash.blade.php ENDPATH**/ ?>