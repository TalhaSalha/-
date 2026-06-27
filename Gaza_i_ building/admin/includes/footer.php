    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // Sidebar Toggler
        const toggler = document.querySelector('.sidebar-toggler');
        if (toggler) {
            toggler.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('.sidebar').classList.toggle('open');
                document.querySelector('.content').classList.toggle('open');
            });
        }
    </script>
</body>
</html>
