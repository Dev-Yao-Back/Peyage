<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'label' => null,
    'labelHidden' => false,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'label' => null,
    'labelHidden' => false,
]); ?>
<?php foreach (array_filter(([
    'label' => null,
    'labelHidden' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<fieldset
    <?php echo e($attributes->class([
            'fi-fieldset rounded-xl border border-gray-200 p-6 dark:border-white/10',
        ])); ?>

>
    <!--[if BLOCK]><![endif]--><?php if(filled($label)): ?>
        <legend
            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                '-ms-2 px-2 text-sm font-medium leading-6 text-gray-950 dark:text-white',
                'sr-only' => $labelHidden,
            ]); ?>"
        >
            <?php echo e($label); ?>

        </legend>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <?php echo e($slot); ?>

</fieldset>
<?php /**PATH /home/yao/Documents/G-Peyage/vendor/filament/support/src/../resources/views/components/fieldset.blade.php ENDPATH**/ ?>