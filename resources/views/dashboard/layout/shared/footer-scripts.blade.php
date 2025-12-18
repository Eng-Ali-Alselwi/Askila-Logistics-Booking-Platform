<!-- Plugin Js (Mandatory in All Pages) -->
<!-- <script src="assets/libs/jquery/jquery.min.js"></script>
<script src="assets/libs/preline/preline.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/iconify-icon/iconify-icon.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script> -->

<!-- App Js (Mandatory in All Pages) -->
<!-- <script src="assets/js/app.js"></script> -->


<!-- App js -->

{{-- @vite(['resources/js/app.js']) --}}

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

<script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>
