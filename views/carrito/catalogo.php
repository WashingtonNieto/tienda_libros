<?php
$busqueda = $busqueda ?? ($_GET['busqueda'] ?? '');
$categoria_id = $categoria_id ?? (int)($_GET['categoria_id'] ?? 0);
?>

<div class="container my-4">
    <!-- Encabezado y botón al carrito -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-shop text-warning me-2"></i>Catálogo de Libros</h2>
        <a href="<?php echo BASE_URL; ?>?c=carrito" class="btn btn-outline-dark position-relative">
            <i class="bi bi-cart3 me-1"></i>Ver Carrito
            <?php if (!empty($_SESSION['carrito'])): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo count($_SESSION['carrito']); ?>
                </span>
            <?php endif; ?>
        </a>
    </div>

    <!-- Formulario de Búsqueda y Filtro por Categoría -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo BASE_URL; ?>" class="row g-3">
                <input type="hidden" name="c" value="carrito">
                <input type="hidden" name="a" value="catalogo">

                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="busqueda" class="form-control" placeholder="Buscar por título o autor..." value="<?php echo htmlspecialchars($busqueda); ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="categoria_id" class="form-select">
                        <option value="0">Todas las categorías</option>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($categoria_id == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-filter me-1"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Grilla de Libros -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php 
        $librosMostrados = 0;
        if (!empty($libros)): 
            foreach ($libros as $libro): 
                // Aplicar filtros de búsqueda y categoría si existen
                if ($categoria_id > 0 && $libro['categoria_id'] != $categoria_id) continue;
                if (!empty($busqueda)) {
                    $termino = mb_strtolower($busqueda);
                    $titulo  = mb_strtolower($libro['titulo']);
                    $autor   = mb_strtolower($libro['autor']);
                    if (strpos($titulo, $termino) === false && strpos($autor, $termino) === false) {
                        continue;
                    }
                }
                $librosMostrados++;
        ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 position-relative">
                    <?php if (!empty($libro['destacado'])): ?>
                        <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">Destacado</span>
                    <?php endif; ?>
                    
                    <img src="<?php echo BASE_URL; ?>public/uploads/covers/<?php echo !empty($libro['caratula']) ? htmlspecialchars($libro['caratula']) : 'default_cover.jpg'; ?>" 
                         class="card-img-top p-2 rounded" 
                         style="height: 240px; object-fit: contain; background-color: #f8f9fa;" 
                         alt="<?php echo htmlspecialchars($libro['titulo']); ?>">
                    
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2 text-wrap w-auto align-self-start">
                            <?php echo htmlspecialchars($libro['categoria'] ?? 'General'); ?>
                        </span>
                        <h6 class="card-title text-truncate fw-bold mb-1" title="<?php echo htmlspecialchars($libro['titulo']); ?>">
                            <?php echo htmlspecialchars($libro['titulo']); ?>
                        </h6>
                        <p class="card-text text-muted small mb-2"><?php echo htmlspecialchars($libro['autor']); ?></p>
                        
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-success">$<?php echo number_format($libro['precio_venta'], 2); ?></span>
                            <span class="badge <?php echo ($libro['stock'] > 0) ? 'bg-info text-dark' : 'bg-danger'; ?>">
                                Stock: <?php echo $libro['stock']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top-0 pt-0">
                        <?php if ($libro['stock'] > 0): ?>
                            <form action="<?php echo BASE_URL; ?>?c=carrito&a=add" method="POST">
                                <input type="hidden" name="libro_id" value="<?php echo $libro['id']; ?>">
                                <button type="submit" class="btn btn-outline-warning text-dark w-100 fw-semibold">
                                    <i class="bi bi-cart-plus me-1"></i>Agregar
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>Agotado</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php 
            endforeach; 
        endif; 

        if ($librosMostrados === 0): 
        ?>
            <div class="col-12 w-100 text-center py-5">
                <i class="bi bi-search text-muted display-4"></i>
                <p class="lead mt-3 text-muted">No se encontraron libros disponibles con los criterios seleccionados.</p>
                <a href="<?php echo BASE_URL; ?>?c=carrito&a=catalogo" class="btn btn-secondary btn-sm">Limpiar Filtros</a>
            </div>
        <?php endif; ?>
    </div>
</div>