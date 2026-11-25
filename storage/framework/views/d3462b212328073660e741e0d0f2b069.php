<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'photo' => null, 'small' => false]));

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

foreach (array_filter((['name', 'photo' => null, 'small' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $words = preg_split('/\s+/u', trim((string) $name));
    $initials = mb_strtoupper(mb_substr($words[0] ?? '', 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
?>
<?php if($photo): ?>
    <img class="avatar <?php if($small): ?> avatar-initials--small <?php endif; ?>" src="<?php echo e(Storage::disk('public')->url($photo)); ?>" alt="Photo de <?php echo e($name); ?>">
<?php else: ?>
    <span class="avatar-initials <?php if($small): ?> avatar-initials--small <?php endif; ?>" aria-label="Initiales de <?php echo e($name); ?>"><?php echo e($initials); ?></span>
<?php endif; ?>
<?php /**PATH /home/atlas-sec/gestion-de-club-ensaj/resources/views/components/avatar-initials.blade.php ENDPATH**/ ?>