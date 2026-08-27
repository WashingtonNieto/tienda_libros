<div class="container my-auto flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-graph-up-arrow text-warning me-2"></i>Informe Mensual de Ventas</h2>
        <button class="btn btn-outline-dark btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Imprimir Reporte
        </button>
    </div>

    <!-- Tarjetas KPI -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white shadow-sm rounded-4">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-currency-dollar display-6"></i>
                    <h6 class="text-white-50 mt-2">Ingresos Totales Históricos</h6>
                    <h3 class="fw-bold mb-0">$<?= number_format($kpis['total_ventas_historico'], 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-success text-white shadow-sm rounded-4">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-receipt display-6"></i>
                    <h6 class="text-white-50 mt-2">Transacciones Realizadas</h6>
                    <h3 class="fw-bold mb-0"><?= $kpis['total_facturas'] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-warning text-dark shadow-sm rounded-4">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-book display-6"></i>
                    <h6 class="text-dark-50 mt-2">Libros Vendidos (Unidades)</h6>
                    <h3 class="fw-bold mb-0"><?= $kpis['total_unidades_vendidas'] ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-white rounded-3">
            <form action="<?= BASE_URL ?>" method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="c" value="reportes">
                
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filtrar por Año</label>
                    <select name="anio" class="form-select">
                        <option value="">Todos los años</option>
                        <?php for ($a = date('Y'); $a >= 2024; $a--): ?>
                            <option value="<?= $a ?>" <?= (isset($_GET['anio']) && $_GET['anio'] == $a) ? 'selected' : '' ?>><?= $a ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Filtrar por Mes</label>
                    <select name="mes" class="form-select">
                        <option value="">Todos los meses</option>
                        <?php 
                        $meses = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
                        foreach ($meses as $num => $nombre): ?>
                            <option value="<?= $num ?>" <?= (isset($_GET['mes']) && $_GET['mes'] == $num) ? 'selected' : '' ?>><?= $nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-bold flex-grow-1"><i class="bi bi-filter me-1"></i> Filtrar</button>
                    <a href="<?= BASE_URL ?>?c=reportes" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla DataTables -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover datatable align-middle w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Año</th>
                        <th>Mes / Período</th>
                        <th>Transacciones</th>
                        <th>Libros Vendidos</th>
                        <th>Total Recaudado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reportes)): ?>
                        <?php foreach ($reportes as $r): ?>
                            <tr>
                                <td class="fw-bold"><?= $r['anio'] ?></td>
                                <td><span class="badge bg-secondary fs-6"><?= $r['periodo'] ?></span></td>
                                <td><?= $r['total_transacciones'] ?> facturas</td>
                                <td><?= $r['total_libros_vendidos'] ?> uds.</td>
                                <td class="text-success fw-bold fs-6">$<?= number_format($r['ingresos_totales'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
