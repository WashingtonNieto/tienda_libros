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
                                <select class="form-select" id="rol_id" name="rol_id" required>
                                    <option value="" selected disabled>Selecciona un rol...</option>
                                    <?php if (!empty($roles)): ?>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
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