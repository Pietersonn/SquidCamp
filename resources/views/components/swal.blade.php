<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SUCCESS
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 2000,
            showConfirmButton: false,
            backdrop: false  // <-- Tambahkan ini agar background tidak gelap
        });
    @endif

    // ERROR (Biasanya error tetap butuh fokus, jadi backdrop dibiarkan default/gelap)
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session("error") }}'
        });
    @endif

    // VALIDATION ERRORS
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    @endif

    // STATUS MESSAGE
    @if (session('status'))
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: '{{ session("status") }}',
            backdrop: false // Opsional: Status juga bisa dibuat tidak gelap
        });
    @endif
</script>
