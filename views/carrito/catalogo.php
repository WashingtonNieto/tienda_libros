<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2><i class="bi bi-shop text-warning me-2"></i>Catálogo de Libros</h2>
        <a href="<?= BASE_URL ?>?c=carrito" class="btn btn-outline-dark">
            <i class="bi bi-cart3 me-1"></i> Ver Carrito
            <?php if (!empty($_SESSION['carrito'])): ?>
                <span class="badge bg-warning text-dark ms-1"><?= array_sum($_SESSION['carrito']) ?></span>
            <?php endif; ?>
        </a>
    </div>

    <form action="<?= BASE_URL ?>?c=carrito&a=catalogo" method="GET" class="row g-2 mb-4">
        <input type="hidden" name="c" value="carrito">
        <input type="hidden" name="a" value="catalogo">
        <div class="col-md-6">
            <input type="text" name="q" class="form-control" placeholder="Buscar por título o autor..." value="<?= htmlspecialchars($busqueda) ?>">
        </div>
        <div class="col-md-4">
            <select name="categoria_id" class="form-select">
                <option value="0">Todas las categorías</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoriaId == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-search me-1"></i> Filtrar</button>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
        <?php if (!empty($libros)): ?>
            <?php foreach ($libros as $l): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                        <img src="<?= BASE_URL ?>public/uploads/covers/<?= htmlspecialchars($l['caratula']) ?>"
                             class="card-img-top" alt="<?= htmlspecialchars($l['titulo']) ?>"
                             style="height: 260px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-secondary mb-2 align-self-start"><?= htmlspecialchars($l['categoria']) ?></span>
                            <h6 class="card-title fw-bold text-dark text-truncate" title="<?= htmlspecialchars($l['titulo']) ?>"><?= htmlspecialchars($l['titulo']) ?></h6>
                            <p class="card-text text-muted small mb-2"><?= htmlspecialchars($l['autor']) ?></p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-success fs-5">$<?= number_format($l['precio_venta'], 2) ?></span>
                                <span class="badge <?= $l['stock'] > 0 ? 'bg-info' : 'bg-danger' ?>">
                                    <?= $l['stock'] > 0 ? 'Stock: ' . $l['stock'] : 'Agotado' ?>
                                </span>
                            </div>
                            <form action="<?= BASE_URL ?>?c=carrito&a=agregar" method="POST" class="mt-auto d-flex gap-2">
                                <input type="hidden" name="libro_id" value="<?= $l['id'] ?>">
                                <input type="number" name="cantidad" value="1" min="1" max="<?= $l['stock'] ?>"
                                       class="form-control form-control-sm" style="width: 70px;"
                                       <?= $l['stock'] <= 0 ? 'disabled' : '' ?>>
                                <button type="submit" class="btn btn-warning btn-sm fw-bold flex-grow-1"
                                        <?= $l['stock'] <= 0 ? 'disabled' : '' ?>>
                                    <i class="bi bi-cart-plus me-1"></i> Agregar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-muted">No se encontraron libros con los criterios de búsqueda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
