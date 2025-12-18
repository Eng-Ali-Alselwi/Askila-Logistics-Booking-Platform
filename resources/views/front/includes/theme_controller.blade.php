<!-- Modern Theme Toggle -->
<!-- <div class="theme-controller">
    <button id="theme-toggle" class="group relative w-12 h-12 rounded-full bg-white/10 dark:bg-gray-800/10 backdrop-blur-md border border-white/20 dark:border-gray-700/20 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
        <svg id="sun-icon" class="w-5 h-5 text-yellow-400 transition-all duration-300 absolute inset-0 m-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        
        <svg id="moon-icon" class="w-5 h-5 text-blue-300 transition-all duration-300 absolute inset-0 m-auto opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
        
        <div class="absolute inset-0 rounded-full bg-blue-500/20 scale-0 group-active:scale-100 transition-transform duration-200"></div>
    </button>
</div> -->

<style>
.theme-controller {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

/* Smooth theme transitions */
* {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

/* Dark mode specific styles */
.dark {
    color-scheme: dark;
}

/* Enhanced dark mode colors */
.dark .bg-white {
    background-color: #1f2937 !important;
}

.dark .text-gray-900 {
    color: #f9fafb !important;
}

.dark .text-gray-600 {
    color: #d1d5db !important;
}

.dark .border-gray-200 {
    border-color: #374151 !important;
}

.dark .shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.1) !important;
}

/* Dark mode card enhancements */
.dark .bg-gray-50 {
    background-color: #111827 !important;
}

.dark .bg-gray-100 {
    background-color: #1f2937 !important;
}

/* Dark mode navbar */
.dark .bg-white\/95 {
    background-color: rgba(31, 41, 55, 0.95) !important;
}

/* Dark mode service cards */
.dark .service-card {
    background-color: #1f2937 !important;
    border-color: #374151 !important;
}

.dark .service-card:hover {
    background-color: #374151 !important;
}

/* Dark mode gradients */
.dark .bg-gradient-to-br.from-gray-50.to-blue-50 {
    background: linear-gradient(to bottom right, #111827, #1e3a8a) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.getElementById('sun-icon');
    const moonIcon = document.getElementById('moon-icon');
    const html = document.documentElement;
    
    // Get saved theme or default to light
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    // Apply saved theme
    if (savedTheme === 'dark') {
        html.classList.add('dark');
        sunIcon.classList.add('opacity-0');
        moonIcon.classList.remove('opacity-0');
    } else {
        html.classList.remove('dark');
        sunIcon.classList.remove('opacity-0');
        moonIcon.classList.add('opacity-0');
    }
    
    // Theme toggle functionality
    themeToggle.addEventListener('click', function() {
        const isDark = html.classList.contains('dark');
        
        if (isDark) {
            // Switch to light mode
            html.classList.remove('dark');
            sunIcon.classList.remove('opacity-0');
            moonIcon.classList.add('opacity-0');
            localStorage.setItem('theme', 'light');
        } else {
            // Switch to dark mode
            html.classList.add('dark');
            sunIcon.classList.add('opacity-0');
            moonIcon.classList.remove('opacity-0');
            localStorage.setItem('theme', 'dark');
        }
        
        // Add a subtle animation effect
        document.body.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
    });
    
    // Listen for system theme changes
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        // Only apply system theme if no user preference is saved
        if (!localStorage.getItem('theme')) {
            if (mediaQuery.matches) {
                html.classList.add('dark');
                sunIcon.classList.add('opacity-0');
                moonIcon.classList.remove('opacity-0');
            }
        }
        
        // Listen for changes
        mediaQuery.addEventListener('change', function(e) {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    html.classList.add('dark');
                    sunIcon.classList.add('opacity-0');
                    moonIcon.classList.remove('opacity-0');
                } else {
                    html.classList.remove('dark');
                    sunIcon.classList.remove('opacity-0');
                    moonIcon.classList.add('opacity-0');
                }
            }
        });
    }
});
</script>