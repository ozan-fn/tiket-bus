<button
    type="button"
    @click="toggleTheme"
    x-data="{
        theme: localStorage.getItem('theme') || 'light',
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        },
        init() {
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        }
    }"
    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9"
    title="Toggle theme"
>
    <!-- Sun Icon (Light Mode) -->
    <x-lucide-sun
        x-show="theme === 'dark'"
        x-cloak
        class="h-5 w-5"
    />

    <!-- Moon Icon (Dark Mode) -->
    <x-lucide-moon
        x-show="theme === 'light'"
        x-cloak
        class="h-5 w-5"
    />
</button>
