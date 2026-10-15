<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-dark text-white p-3 rounded-top-3">
                    <h4 class="mb-0 text-center fw-bold">
                        <i class="bi bi-person-plus-fill me-2 text-warning"></i>Registro de Usuario
                    </h4>
                </div>
                <div class="card-body p-4">

                    <!-- Notificaciones de Errores o Mensajes de Flash -->
                    <?php if (isset($_SESSION['flash_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php 
                                echo htmlspecialchars($_SESSION['flash_error']); 
                                unset($_SESSION['flash_error']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>?c=auth&a=store" method="POST" id="formRegister" novalidate>
                        
                        <!-- Nombre Completo -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold">Nombre Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Juan Pérez" required>
                            </div>
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="nombre@ejemplo.com" required>
                            </div>
                        </div>

                        <!-- Contraseña con RegEx -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control" 
                                       placeholder="••••••••"
                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            
                            <!-- Lista interactiva de requerimientos de contraseña -->
                            <div class="mt-2 p-2 bg-light rounded border small">
                                <span class="d-block fw-bold text-secondary mb-1">Requisitos de la clave:</span>
                                <div id="req-length" class="text-danger"><i class="bi bi-x-circle me-1"></i> Mínimo 8 caracteres</div>
                                <div id="req-upper" class="text-danger"><i class="bi bi-x-circle me-1"></i> Al menos una letra mayúscula (A-Z)</div>
                                <div id="req-lower" class="text-danger"><i class="bi bi-x-circle me-1"></i> Al menos una letra minúscula (a-z)</div>
                                <div id="req-number" class="text-danger"><i class="bi bi-x-circle me-1"></i> Al menos un número (0-9)</div>
                            </div>
                        </div>

                        <!-- Selección de Rol -->
                        <div class="mb-4">
                            <label for="rol_id" class="form-label fw-semibold">Rol Asignado</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                                <select name="rol_id" id="rol_id" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un rol...</option>
                                    <?php if (!empty($roles)): ?>
                                        <?php foreach ($roles as $r): ?>
                                            <option value="<?php echo $r['id']; ?>">
                                                <?php echo htmlspecialchars($r['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark btn-lg fw-bold shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Completar Registro
                            </button>
                            <a href="<?php echo BASE_URL; ?>?c=auth&a=login" class="btn btn-outline-secondary">
                                ¿Ya tienes una cuenta? Iniciar Sesión
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('toggleIcon');
    const form = document.getElementById('formRegister');

    // Elementos de la lista de verificación
    const reqLength = document.getElementById('req-length');
    const reqUpper  = document.getElementById('req-upper');
    const reqLower  = document.getElementById('req-lower');
    const reqNumber = document.getElementById('req-number');

    // Función para mostrar/ocultar contraseña
    togglePasswordBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
    });

    // Validación dinámica en tiempo real según la RegEx /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/
    passwordInput.addEventListener('input', function() {
        const val = passwordInput.value;

        // Mínimo 8 caracteres
        if (val.length >= 8) {
            updateReq(reqLength, true);
        } else {
            updateReq(reqLength, false);
        }

        // Al menos una mayúscula
        if (/[A-Z]/.test(val)) {
            updateReq(reqUpper, true);
        } else {
            updateReq(reqUpper, false);
        }

        // Al menos una minúscula
        if (/[a-z]/.test(val)) {
            updateReq(reqLower, true);
        } else {
            updateReq(reqLower, false);
        }

        // Al menos un número
        if (/\d/.test(val)) {
            updateReq(reqNumber, true);
        } else {
            updateReq(reqNumber, false);
        }
    });

    function updateReq(element, isValid) {
        if (isValid) {
            element.classList.remove('text-danger');
            element.classList.add('text-success');
            element.querySelector('i').className = 'bi bi-check-circle-fill me-1';
        } else {
            element.classList.remove('text-success');
            element.classList.add('text-danger');
            element.querySelector('i').className = 'bi bi-x-circle me-1';
        }
    }

    // Validación al enviar el formulario
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

        if (!regex.test(password)) {
            e.preventDefault();
            e.stopPropagation();
            alert('La contraseña no cumple con la expresión regular establecida:\n• Mínimo 8 caracteres\n• Al menos una letra mayúscula\n• Al menos una letra minúscula\n• Al menos un número');
            passwordInput.focus();
        }
    });
});
</script>