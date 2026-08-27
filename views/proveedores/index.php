<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-truck text-warning me-2"></i>Gestión de Proveedores</h2>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalProveedor" onclick="limpiarProveedor()">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Proveedor
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover datatable align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>NIT/RUT</th>
                        <th>Razón Social</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proveedores as $prov): ?>
                        <tr>
                            <td><?= htmlspecialchars($prov['nit_rut']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($prov['razon_social']) ?></td>
                            <td><?= htmlspecialchars($prov['contacto']) ?></td>
                            <td><?= htmlspecialchars($prov['telefono']) ?></td>
                            <td><?= htmlspecialchars($prov['email']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick='editarProveedor(<?= json_encode($prov) ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminacion('<?= BASE_URL ?>?c=proveedores&a=delete&id=<?= $prov['id'] ?>')">
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
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>?c=proveedores&a=save" method="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalTitleProv">Nuevo Proveedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="prov_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIT / RUT</label>
                        <input type="text" name="nit_rut" id="prov_nit_rut" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Razón Social</label>
                        <input type="text" name="razon_social" id="prov_razon_social" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Persona de Contacto</label>
                        <input type="text" name="contacto" id="prov_contacto" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" id="prov_telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="prov_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion" id="prov_direccion" class="form-control">
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
function limpiarProveedor() {
    document.getElementById('prov_id').value = '';
    document.getElementById('prov_nit_rut').value = '';
    document.getElementById('prov_razon_social').value = '';
    document.getElementById('prov_contacto').value = '';
    document.getElementById('prov_telefono').value = '';
    document.getElementById('prov_email').value = '';
    document.getElementById('prov_direccion').value = '';
    document.getElementById('modalTitleProv').innerText = 'Nuevo Proveedor';
}

function editarProveedor(data) {
    document.getElementById('prov_id').value = data.id;
    document.getElementById('prov_nit_rut').value = data.nit_rut;
    document.getElementById('prov_razon_social').value = data.razon_social;
    document.getElementById('prov_contacto').value = data.contacto;
    document.getElementById('prov_telefono').value = data.telefono;
    document.getElementById('prov_email').value = data.email;
    document.getElementById('prov_direccion').value = data.direccion;
    document.getElementById('modalTitleProv').innerText = 'Editar Proveedor';
    new bootstrap.Modal(document.getElementById('modalProveedor')).show();
}
</script>
