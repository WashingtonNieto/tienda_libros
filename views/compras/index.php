<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart-plus text-warning me-2"></i>Compras a Proveedores (Entradas)</h2>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalCompra">
            <i class="bi bi-plus-circle me-1"></i> Nueva Compra
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover datatable align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>N° Factura</th>
                        <th>Proveedor</th>
                        <th>Registrado Por</th>
                        <th>Fecha</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as $c): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($c['numero_factura']) ?></td>
                            <td><?= htmlspecialchars($c['proveedor']) ?></td>
                            <td><?= htmlspecialchars($c['usuario']) ?></td>
                            <td><?= $c['fecha_compra'] ?></td>
                            <td class="text-success fw-bold">$<?= number_format($c['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Formulario de Compra -->
<div class="modal fade" id="modalCompra" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>?c=compras&a=store" method="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Registrar Entrada de Inventario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Proveedor</label>
                            <select name="proveedor_id" class="form-select" required>
                                <?php foreach ($proveedores as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['razon_social']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Número de Factura</label>
                            <input type="text" name="numero_factura" class="form-control" placeholder="FAC-PROV-123" required>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold">Detalle de Productos</h6>
                    <div id="itemsContainer">
                        <div class="row mb-2 item-row">
                            <div class="col-md-5">
                                <select name="libro_id[]" class="form-select" required>
                                    <option value="" disabled selected>Selecciona Libro...</option>
                                    <?php foreach ($libros as $l): ?>
                                        <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['titulo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="cantidad[]" class="form-control" placeholder="Cant." min="1" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="precio_unitario[]" class="form-control" placeholder="Precio Compra" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.item-row').remove()"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="agregarFila()">+ Agregar otro libro</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold">Procesar Compra</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function agregarFila() {
    const container = document.getElementById('itemsContainer');
    const firstRow = container.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input').forEach(i => i.value = '');
    container.appendChild(newRow);
}
</script>