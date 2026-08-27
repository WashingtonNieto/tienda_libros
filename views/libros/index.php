<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-journal-bookmark text-warning me-2"></i>Catálogo de Libros</h2>
        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalLibro" onclick="limpiarLibro()">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Libro
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover datatable align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Portada</th>
                        <th>ISBN</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>P. Venta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $l): ?>
                        <tr>
                            <td>
                                <img src="<?= BASE_URL ?>public/uploads/covers/<?= htmlspecialchars($l['caratula']) ?>" class="rounded shadow-sm" width="45" height="60" style="object-fit: cover;">
                            </td>
                            <td><?= htmlspecialchars($l['isbn']) ?></td>
                            <td class="fw-bold">
                                <?= htmlspecialchars($l['titulo']) ?>
                                <?= $l['destacado'] ? '<span class="badge bg-warning text-dark ms-1">Destacado</span>' : '' ?>
                            </td>
                            <td><?= htmlspecialchars($l['categoria']) ?></td>
                            <td>
                                <span class="badge <?= $l['stock'] <= 5 ? 'bg-danger' : 'bg-success' ?> fs-6">
                                    <?= $l['stock'] ?>
                                </span>
                            </td>
                            <td>$<?= number_format($l['precio_venta'], 2) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick='editarLibro(<?= json_encode($l) ?>)'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminacion('<?= BASE_URL ?>?c=libros&a=delete&id=<?= $l['id'] ?>')">
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
<div class="modal fade" id="modalLibro" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>?c=libros&a=save" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalTitleLibro">Nuevo Libro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="lib_id">
                    <input type="hidden" name="caratula_actual" id="lib_caratula_actual">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">ISBN</label>
                            <input type="text" name="isbn" id="lib_isbn" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Categoría</label>
                            <select name="categoria_id" id="lib_categoria_id" class="form-select" required>
                                <option value="" disabled selected>Selecciona...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text" name="titulo" id="lib_titulo" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Autor</label>
                            <input type="text" name="autor" id="lib_autor" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Precio Compra</label>
                            <input type="number" step="0.01" name="precio_compra" id="lib_precio_compra" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Precio Venta</label>
                            <input type="number" step="0.01" name="precio_venta" id="lib_precio_venta" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3" id="stockGroup">
                            <label class="form-label fw-semibold">Stock Inicial</label>
                            <input type="number" name="stock" id="lib_stock" class="form-control" value="0">
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Carátula (Imagen)</label>
                            <input type="file" name="caratula" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="destacado" value="1" id="lib_destacado" class="form-check-input">
                                <label class="form-check-label fw-semibold" for="lib_destacado">Destacado / Recomendado</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold">Guardar Libro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function limpiarLibro() {
    document.getElementById('lib_id').value = '';
    document.getElementById('lib_caratula_actual').value = '';
    document.getElementById('lib_isbn').value = '';
    document.getElementById('lib_titulo').value = '';
    document.getElementById('lib_autor').value = '';
    document.getElementById('lib_precio_compra').value = '';
    document.getElementById('lib_precio_venta').value = '';
    document.getElementById('lib_stock').value = '0';
    document.getElementById('stockGroup').style.display = 'block';
    document.getElementById('lib_destacado').checked = false;
    document.getElementById('modalTitleLibro').innerText = 'Nuevo Libro';
}

function editarLibro(data) {
    document.getElementById('lib_id').value = data.id;
    document.getElementById('lib_caratula_actual').value = data.caratula;
    document.getElementById('lib_isbn').value = data.isbn;
    document.getElementById('lib_categoria_id').value = data.categoria_id;
    document.getElementById('lib_titulo').value = data.titulo;
    document.getElementById('lib_autor').value = data.autor;
    document.getElementById('lib_precio_compra').value = data.precio_compra;
    document.getElementById('lib_precio_venta').value = data.precio_venta;
    document.getElementById('stockGroup').style.display = 'none';
    document.getElementById('lib_destacado').checked = data.destacado == 1;
    document.getElementById('modalTitleLibro').innerText = 'Editar Libro';
    new bootstrap.Modal(document.getElementById('modalLibro')).show();
}
</script>