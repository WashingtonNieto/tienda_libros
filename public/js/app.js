$(document.ready).ready(function () {
    // Inicialización global de DataTables en Español
    if ($('.datatable').length > 0) {
        $('.datatable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            pageLength: 10,
            order: [[0, 'desc']]
        });
    }
});

/**
 * Helper global para lanzar SweetAlert2 de confirmación de eliminación
 */
function confirmarEliminacion(url, mensaje = "¡Esta acción no se puede deshacer!") {
    Swal.fire({
        title: '¿Estás seguro?',
        text: mensaje,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}