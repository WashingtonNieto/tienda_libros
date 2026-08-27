<footer class="footer mt-auto py-3 bg-dark text-white text-center">
        <div class="container">
            <small>&copy; <?= date('Y') ?> <?= APP_NAME ?> - Todos los derechos reservados.</small>
        </div>
    </footer>

    <!-- jQuery (Requerido para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    <!-- Script Global del Sistema -->
    <script src="<?= BASE_URL ?>public/js/app.js"></script>

    <!-- Notificaciones Flash con SweetAlert2 -->
    <script>
    <?php if (isset($_SESSION['flash_success'])): ?>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?= $_SESSION['flash_success'] ?>',
            timer: 3000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= $_SESSION['flash_error'] ?>',
            confirmButtonColor: '#dc3545'
        });
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
    </script>
</body>
</html>