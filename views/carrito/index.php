<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2><i class="bi bi-cart3 text-warning me-2"></i>Mi Carrito</h2>
        <a href="<?= BASE_URL ?>?c=carrito&a=catalogo" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-1"></i> Seguir Comprando
        </a>
    </div>

    <?php if (empty($items)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x display-4 text-muted"></i>
                <p class="text-muted mt-3 mb-3">Tu carrito está vacío.</p>
                <a href="<?= BASE_URL ?>?c=carrito&a=catalogo" class="btn btn-warning fw-bold">
                    <i class="bi bi-shop me-1"></i> Explorar Catálogo
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Libro</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($items as $item): $total += $item['subtotal']; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?= BASE_URL ?>public/uploads/covers/<?= htmlspecialchars($item['caratula']) ?>"
                                                 class="rounded shadow-sm" width="45" height="60" style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($item['titulo']) ?></div>
                                                <div class="text-muted small"><?= htmlspecialchars($item['autor']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$<?= number_format($item['precio_venta'], 2) ?></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>?c=carrito&a=actualizar" method="POST" class="d-flex gap-1">
                                            <input type="hidden" name="libro_id" value="<?= $item['id'] ?>">
                                            <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1"
                                                   max="<?= $item['stock'] ?>" class="form-control form-control-sm" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Actualizar cantidad">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="fw-bold">$<?= number_format($item['subtotal'], 2) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?c=carrito&a=eliminar&libro_id=<?= $item['id'] ?>"
                                           class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5">Total:</td>
                                <td colspan="2" class="fw-bold fs-5 text-success">$<?= number_format($total, 2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <form action="<?= BASE_URL ?>?c=carrito&a=checkout" method="POST" class="text-end">
            <button type="submit" class="btn btn-success btn-lg fw-bold">
                <i class="bi bi-check-circle me-1"></i> Finalizar Compra
            </button>
        </form>
    <?php endif; ?>
</div>
