    <div
        x-show="open"
        x-transition
        class="absolute left-full top-0 ml-1 min-w-[8rem] rounded-md border bg-popover p-1 shadow-lg"
        data-slot="dropdown-menu-sub-content"
    >
        {{ $submenu }}
    </div>
</div>
