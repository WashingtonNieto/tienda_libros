<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cash-stack text-warning me-2"></i>Módulo de Ventas (Salidas)</h2>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalVenta">
            <i class="bi bi-plus-circle me-1"></i> Nueva Venta
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover datatable align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>N° Factura</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Fecha</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($v['numero_factura']) ?></td>
                            <td><?= htmlspecialchars($v['cliente']) ?></td>
                            <td><?= htmlspecialchars($v['vendedor']) ?></td>
                            <td><?= $v['fecha_venta'] ?></td>
                            <td class="text-success fw-bold">$<?= number_format($v['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Formulario de Venta -->
<div class="modal fade" id="modalVenta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>?c=ventas&a=store" method="POST">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Registrar Nueva Venta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cliente</label>
                        <select name="cliente_id" class="form-select" required>
                            <option value="" disabled selected>Selecciona un cliente...</option>
                            <?php foreach ($clientes as $cli): ?>
                                <option value="<?= $cli['id'] ?>"><?= htmlspecialchars($cli['nombre']) ?> (<?= $cli['documento'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <hr>
                    <h6 class="fw-bold">Detalle de Libros</h6>
                    <div id="ventaItemsContainer">
                        <div class="row mb-2 venta-item-row">
                            <div class="col-md-6">
                                <select name="libro_id[]" class="form-select libro-select" onchange="actualizarPrecio(this)" required>
                                    <option value="" disabled selected>Selecciona Libro...</option>
                                    <?php foreach ($libros as $l): ?>
                                        <option value="<?= $l['id'] ?>" data-precio="<?= $l['precio_venta'] ?>" data-stock="<?= $l['stock'] ?>">
                                            <?= htmlspecialchars($l['titulo']) ?> (Stock: <?= $l['stock'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="cantidad[]" class="form-control" placeholder="Cant." min="1" value="1" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="precio_unitario[]" class="form-control precio-input" placeholder="Precio" readonly required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.venta-item-row').remove()"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="agregarFilaVenta()">+ Agregar otro libro</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold">Completar Venta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function actualizarPrecio(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const precio = selectedOption.getAttribute('data-precio') || 0;
    const row = selectElement.closest('.venta-item-row');
    row.querySelector('.precio-input').value = precio;
}

function agregarFilaVenta() {
    const container = document.getElementById('ventaItemsContainer');
    const firstRow = container.querySelector('.venta-item-row');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input').forEach(i => i.value = '');
    container.appendChild(newRow);
}
</script>