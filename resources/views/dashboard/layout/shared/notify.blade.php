<script>
    window.addEventListener("notify", (event) => {
        showNotification(event.detail.body.type, event.detail.body.message);
    });

    function showNotification(type, title, message) {
        Swal.fire({
            icon: type,
            title: title,
            toast: true,
            // text: message,
            position : "{{ app()->getLocale() == 'ar' ? 'top-right' : 'top-end' }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }
</script>
    @if (Session::has('success'))
        <script>
            showNotification('success', '{{ t(session('success')) }}');
        </script>
    @elseif(Session::has('error'))
        <script>
            showNotification('error',  '{{ t(session('error')) }}');
        </script>
    @elseif(Session::has('warning'))
        <script>
            showNotification('warning', '{{ t(session('warning')) }}');
        </script>
    @elseif(Session::has('info'))
        <script>
            showNotification('info','{{ t(session('info')) }}');
        </script>
    @endif
<script>
