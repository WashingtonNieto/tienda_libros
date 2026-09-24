<div class="container my-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-warning display-4"></i>
                        <h3 class="fw-bold mt-2">Crear Cuenta</h3>
                        <p class="text-muted small">Registra un nuevo usuario en la plataforma</p>
                    </div>

                    <form action="<?= BASE_URL ?>?c=auth&a=store" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Juan Pérez" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="rol_id" class="form-label fw-semibold">Rol asignado</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <select class="form-select" id="rol_id" name="rol_id" required onchange="toggleDatosCliente()">
                                    <option value="" selected disabled>Selecciona un rol...</option>
                                    <?php if (!empty($roles)): ?>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id'] ?>" data-rol-nombre="<?= htmlspecialchars($rol['nombre']) ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div id="datosCliente" style="display:none;">
                            <div class="mb-3">
                                <label for="documento" class="form-label fw-semibold">Documento</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                    <input type="text" class="form-control" id="documento" name="documento" placeholder="Cédula / Documento">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ej: 3001234567">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label fw-semibold">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección de entrega">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" minlength="6" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2 shadow-sm mb-3">
                            <i class="bi bi-check-circle me-1"></i> Registrar Usuario
                        </button>

                        <div class="text-center">
                            <a href="<?= BASE_URL ?>?c=auth&a=login" class="text-decoration-none small text-muted">¿Ya tienes cuenta? Inicia sesión aquí</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDatosCliente() {
    var select = document.getElementById('rol_id');
    var opcion = select.options[select.selectedIndex];
    var esCliente = opcion && opcion.dataset.rolNombre === 'Cliente';
    var bloque = document.getElementById('datosCliente');
    bloque.style.display = esCliente ? 'block' : 'none';
    document.getElementById('documento').required = esCliente;
}
</script>