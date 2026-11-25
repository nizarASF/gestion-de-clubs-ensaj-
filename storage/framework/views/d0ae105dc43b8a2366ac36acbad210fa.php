<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['club']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['club']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<a class="club-card" href="<?php echo e(route('clubs.show', $club)); ?>">
    <?php if($club->logo_path): ?>
        <img class="club-card__logo" src="<?php echo e(Storage::disk('public')->url($club->logo_path)); ?>" alt="Logo de <?php echo e($club->name); ?>">
    <?php else: ?>
        <img class="club-card__logo" src="<?php echo e(asset('images/club-default.svg')); ?>" alt="Logo par défaut">
    <?php endif; ?>
    <h3 class="club-card__name"><?php echo e($club->name); ?></h3>
    <p class="club-card__desc"><?php echo e($club->short_description); ?></p>
    <span class="muted">Découvrir le club →</span>
</a>
<?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/components/club-card.blade.php ENDPATH**/ ?>