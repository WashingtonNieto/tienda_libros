<div class="container my-auto flex-grow-1 py-4">
    <!-- Banner de Bienvenida -->
    <div class="p-4 p-md-5 mb-5 bg-dark text-white rounded-4 shadow-sm text-center">
        <h1 class="display-5 fw-bold"><i class="bi bi-book-half text-warning me-2"></i><?= APP_NAME ?></h1>
        <p class="lead">Explora nuestro catálogo, conoce las últimas novedades y los títulos más leídos.</p>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['rol'] !== 'Cliente'): ?>
            <a href="<?= BASE_URL ?>?c=libros" class="btn btn-warning btn-lg fw-bold mt-2">
                <i class="bi bi-journal-bookmark me-2"></i>Ver Catálogo Completo
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>?c=carrito&a=catalogo" class="btn btn-warning btn-lg fw-bold mt-2">
                <i class="bi bi-shop me-2"></i>Ver Catálogo Completo
            </a>
        <?php endif; ?>
    </div>

    <!-- Sección: Libros Más Vendidos -->
    <?php if (!empty($masVendidos)): ?>
        <div class="mb-5">
            <h3 class="fw-bold border-bottom border-warning pb-2 mb-4">
                <i class="bi bi-fire text-danger me-2"></i>Los Más Vendidos
            </h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <?php foreach ($masVendidos as $libro): ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                            <img src="<?= BASE_URL ?>public/uploads/covers/<?= htmlspecialchars($libro['caratula']) ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($libro['titulo']) ?>" 
                                 style="height: 280px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-secondary mb-2 align-self-start"><?= htmlspecialchars($libro['categoria']) ?></span>
                                <h6 class="card-title fw-bold text-dark text-truncate"><?= htmlspecialchars($libro['titulo']) ?></h6>
                                <p class="card-text text-muted small mb-2"><?= htmlspecialchars($libro['autor']) ?></p>
                                <div class="mt-auto d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-success fs-5">$<?= number_format($libro['precio_venta'], 2) ?></span>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Top Sales</span>
                                </div>
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['rol'] === 'Cliente'): ?>
                                    <form action="<?= BASE_URL ?>?c=carrito&a=agregar" method="POST">
                                        <input type="hidden" name="libro_id" value="<?= $libro['id'] ?>">
                                        <input type="hidden" name="cantidad" value="1">
                                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold" <?= $libro['stock'] <= 0 ? 'disabled' : '' ?>>
                                            <i class="bi bi-cart-plus me-1"></i> Agregar al Carrito
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sección: Recomendados / Destacados -->
    <div class="mb-4">
        <h3 class="fw-bold border-bottom border-warning pb-2 mb-4">
            <i class="bi bi-hand-thumbs-up text-primary me-2"></i>Libros Recomendados
        </h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            <?php if (!empty($destacados)): ?>
                <?php foreach ($destacados as $libro): ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                            <img src="<?= BASE_URL ?>public/uploads/covers/<?= htmlspecialchars($libro['caratula']) ?>" 
                                 class="card-img-top" alt="<?= htmlspecialchars($libro['titulo']) ?>" 
                                 style="height: 280px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-secondary mb-2 align-self-start"><?= htmlspecialchars($libro['categoria']) ?></span>
                                <h6 class="card-title fw-bold text-dark text-truncate"><?= htmlspecialchars($libro['titulo']) ?></h6>
                                <p class="card-text text-muted small mb-2"><?= htmlspecialchars($libro['autor']) ?></p>
                                <div class="mt-auto d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-success fs-5">$<?= number_format($libro['precio_venta'], 2) ?></span>
                                    <span class="badge <?= $libro['stock'] > 0 ? 'bg-info' : 'bg-danger' ?>">
                                        <?= $libro['stock'] > 0 ? 'Stock: ' . $libro['stock'] : 'Agotado' ?>
                                    </span>
                                </div>
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['rol'] === 'Cliente'): ?>
                                    <form action="<?= BASE_URL ?>?c=carrito&a=agregar" method="POST">
                                        <input type="hidden" name="libro_id" value="<?= $libro['id'] ?>">
                                        <input type="hidden" name="cantidad" value="1">
                                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold" <?= $libro['stock'] <= 0 ? 'disabled' : '' ?>>
                                            <i class="bi bi-cart-plus me-1"></i> Agregar al Carrito
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><p class="text-muted">No hay libros recomendados para mostrar en este momento.</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>