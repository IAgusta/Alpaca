<nav x-data="{ open: false, pluginsOpen: false, loading: false }" class="bg-white dark:bg-gray-800 border-b border-gray-500 dark:border-gray-300 fixed top-0 left-0 w-full z-30">
    <!-- Loading Bar -->
    <div x-show="loading" class="fixed top-0 left-0 w-full h-1 bg-blue-500 z-50" x-transition></div>

    <!-- Primary Navigation Menu -->
    @include('layouts.components.primary-navigation')

    <!-- Mobile Navigation Menu -->
    @include('layouts.components.mobile-navigation')
</nav>

<script>
function mobileSearch() {
    return {
        open: false,
        query: '',
        results: { users: [], courses: [] },
        loading: false,

        close() {
            this.open = false;
            this.query = '';
            this.results = { users: [], courses: [] };
        },

        searchAll() {
            const q = this.query.trim();
            if (q.length < 2) {
                this.results = { users: [], courses: [] };
                this.loading = false;
                return;
            }

            this.loading = true;
            fetch(`/search-global?query=${encodeURIComponent(q)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                this.results = data;
                this.loading = false;
            })
            .catch(err => {
                console.error('Mobile search failed:', err);
                this.loading = false;
            });
        }
    };
}
</script>