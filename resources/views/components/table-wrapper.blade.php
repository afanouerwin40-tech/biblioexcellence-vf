@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'overflow-x-auto -mx-4 lg:-mx-6 ' . $class]) }}>
    <div class="inline-block min-w-full align-middle px-4 lg:px-6">
        {{ $slot }}
    </div>
</div>