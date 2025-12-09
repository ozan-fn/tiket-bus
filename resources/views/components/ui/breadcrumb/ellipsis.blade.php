@props(['class' => ''])

<span data-slot="breadcrumb-ellipsis" role="presentation" aria-hidden="true"
    class="flex size-9 items-center justify-center {{ $class }}" {{ $attributes }}>
    <x-ui::icon.more-horizontal class="size-4" />
    <span class="sr-only">More</span>
</span>