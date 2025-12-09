@props([
    'defaultOpen' => true,
    'side' => 'left', // left | right
    'variant' => 'sidebar', // sidebar | floating | inset
    'collapsible' => 'offcanvas', // offcanvas | icon | none
    'class' => '',
])

@php
    $side = in_array($side, ['left', 'right']) ? $side : 'left';
    $variant = in_array($variant, ['sidebar', 'floating', 'inset']) ? $variant : 'sidebar';
    $collapsible = in_array($collapsible, ['offcanvas', 'icon', 'none']) ? $collapsible : 'offcanvas';
@endphp

<div
    x-data="{
        open: {{ $defaultOpen ? 'true' : 'false' }},
        openMobile: false,
        get state() { return this.open ? 'expanded' : 'collapsed' },
        toggleSidebar() {
            if (window.innerWidth < 768) {
                this.openMobile = !this.openMobile
            } else {
                this.open = !this.open
            }
        }
    }"
    x-init="
        // Baca cookie sidebar_state
        (() => {
            const match = document.cookie.match(/(?:^|; )sidebar_state=([^;]*)/);
            if (match) {
                const val = match[1];
                open = (val === 'true' || val === '1');
            }
        })()
    "
    x-effect="
        // Tulis cookie sidebar_state setiap open berubah
        document.cookie = 'sidebar_state=' + (open ? '1' : '0') + '; path=/; max-age=604800';
    "
    x-on:resize.window.debounce.150ms="
        if (window.innerWidth >= 768) {
            openMobile = false
        }
    "
    x-on:keydown.window="
        if (
            ($event.key === 'b' || $event.key === 'B') &&
            ($event.metaKey || $event.ctrlKey)
        ) {
            $event.preventDefault();
            toggleSidebar();
        }
    "
    data-slot="sidebar-wrapper"
    x-bind:data-state="state"
    data-side="{{ $side }}"
    data-variant="{{ $variant }}"
    data-collapsible="{{ $collapsible }}"
    style="--sidebar-width: 16rem; --sidebar-width-icon: 3rem;"
    class="group/sidebar-wrapper flex min-h-svh w-full {{ $variant === 'inset' ? 'bg-sidebar' : '' }} {{ $class }}"
>
    {{ $slot }}
</div>
