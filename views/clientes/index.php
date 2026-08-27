<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people text-warning me-2"></i>Gestión de Clientes</h2>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalCliente" onclick="limpiarCliente()">
            <i class="bi bi-person-plus me-1"></i> Nuevo Cliente
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover datatable align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cli): ?>
                        <tr>
                            <td><?= htmlspecialchars($cli['documento']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($cli['nombre']) ?></td>
                            <td><?= htmlspecialchars($cli['email']) ?></td>
                            <td><?= htmlspecialchars($cli['telefono']) ?></td>
                            <td><?= htmlspecialchars($cli['direccion']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick='editarCliente(<?= json_encode($cli) ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminacion('<?= BASE_URL ?>?c=clientes&a=delete&id=<?= $cli['id'] ?>')">
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
<div class="modal fade" id="modalCliente" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>?c=clientes&a=save" method="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalTitleCli">Nuevo Cliente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="cli_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Documento / Cédula</label>
                        <input type="text" name="documento" id="cli_documento" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre Completo</label>
                        <input type="text" name="nombre" id="cli_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="cli_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" id="cli_telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion" id="cli_direccion" class="form-control">
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
function limpiarCliente() {
    document.getElementById('cli_id').value = '';
    document.getElementById('cli_documento').value = '';
    document.getElementById('cli_nombre').value = '';
    document.getElementById('cli_email').value = '';
    document.getElementById('cli_telefono').value = '';
    document.getElementById('cli_direccion').value = '';
    document.getElementById('modalTitleCli').innerText = 'Nuevo Cliente';
}

function editarCliente(data) {
    document.getElementById('cli_id').value = data.id;
    document.getElementById('cli_documento').value = data.documento;
    document.getElementById('cli_nombre').value = data.nombre;
    document.getElementById('cli_email').value = data.email;
    document.getElementById('cli_telefono').value = data.telefono;
    document.getElementById('cli_direccion').value = data.direccion;
    document.getElementById('modalTitleCli').innerText = 'Editar Cliente';
    new bootstrap.Modal(document.getElementById('modalCliente')).show();
}
</script>
