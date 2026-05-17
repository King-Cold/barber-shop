<nav class="-mx-3 flex flex-1 justify-end">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white font-medium"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="rounded-md px-4 py-2 bg-bronze-gold hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors shadow-sm text-sm"
        >
            Iniciar Sesión
        </a>
    @endauth
</nav>
