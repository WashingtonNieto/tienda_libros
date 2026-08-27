<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-tags text-warning me-2"></i>Gestión de Categorías</h2>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalCategoria" onclick="limpiarForm()">
            <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover datatable align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($cat['nombre']) ?></td>
                            <td><?= htmlspecialchars($cat['descripcion'] ?? 'Sin descripción') ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick='editar(<?= json_encode($cat) ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminacion('<?= BASE_URL ?>?c=categorias&a=delete&id=<?= $cat['id'] ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Formulario -->
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>?c=categorias&a=save" method="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalTitle">Nueva Categoría</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="cat_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input type="text" name="nombre" id="cat_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea name="descripcion" id="cat_descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function limpiarForm() {
    document.getElementById('cat_id').value = '';
    document.getElementById('cat_nombre').value = '';
    document.getElementById('cat_descripcion').value = '';
    document.getElementById('modalTitle').innerText = 'Nueva Categoría';
}

function editar(data) {
    document.getElementById('cat_id').value = data.id;
    document.getElementById('cat_nombre').value = data.nombre;
    document.getElementById('cat_descripcion').value = data.descripcion;
    document.getElementById('modalTitle').innerText = 'Editar Categoría';
    new bootstrap.Modal(document.getElementById('modalCategoria')).show();
}
</script>
