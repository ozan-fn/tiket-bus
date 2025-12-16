@props(['sideOffset' => 4])

<div x-data="{
    open: false,
    position: 'left',
    updatePosition() {
        if (!this.$refs.trigger) return;
        const rect = this.$refs.trigger.getBoundingClientRect();
        const dropdownWidth = 192; // w-48 = 12rem = 192px
        const viewportWidth = window.innerWidth;
        this.position = (rect.left + dropdownWidth > viewportWidth) ? 'right' : 'left';
    }
}" class="relative inline-block">
    <div
        x-on:click="open = !open; updatePosition()"
        x-ref="trigger"
        data-slot="dropdown-menu-trigger"
    >
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:click.outside="open = false"
        x-ref="content"
        x-bind:class="position === 'right' ? 'right-0' : 'left-0'"
        class="absolute top-full mt-1 w-48 bg-popover border border-border rounded-md shadow-lg z-50 p-1 flex flex-col"
    >
        {{ $slot }}
    </div>
</div>
