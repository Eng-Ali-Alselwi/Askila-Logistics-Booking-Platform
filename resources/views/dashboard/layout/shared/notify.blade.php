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
            showNotification('success', '{{ Session::get('success') }}');
        </script>
    @elseif(Session::has('error'))
        <script>
            showNotification('error',  '{{ Session::get('error') }}');
        </script>
    @elseif(Session::has('warning'))
        <script>
            showNotification('warning', '{{ Session::get('warning') }}');
        </script>
    @elseif(Session::has('info'))
        <script>
            showNotification('info','{{ Session::get('info') }}');
        </script>
    @endif
<script>
