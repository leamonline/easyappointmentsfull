<?php extend('layouts/backend_layout'); ?>

<?php section('content'); ?>

<div class="container-fluid backend-page" id="reports-handoff-page">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-light text-black-50 mb-1">Evening Handoff Report</h3>
                    <h5><?= vars('report_date_formatted') ?></h5>
                </div>
                <div class="d-flex gap-2">
                    <form method="get" class="d-flex gap-2">
                        <input type="date" name="date" class="form-control" value="<?= vars('report_date') ?>">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h2 class="mb-0"><?= vars('total_dogs') ?></h2>
                            <small class="text-muted">Total Dogs</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center border-success">
                        <div class="card-body py-3">
                            <h2 class="mb-0 text-success"><?= vars('completed_count') ?></h2>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center border-warning">
                        <div class="card-body py-3">
                            <h2 class="mb-0 text-warning"><?= vars('cancelled_count') ?></h2>
                            <small class="text-muted">Cancelled</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card text-center border-danger">
                        <div class="card-body py-3">
                            <h2 class="mb-0 text-danger"><?= vars('no_show_count') ?></h2>
                            <small class="text-muted">No-Show</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grooming Appointments -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-dog me-2"></i>Grooming Appointments</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty(vars('grooming_appointments'))): ?>
                        <p class="text-muted p-3">No grooming appointments for this date.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Dog</th>
                                        <th>Breed</th>
                                        <th>Size</th>
                                        <th>Owner</th>
                                        <th>Service</th>
                                        <th>Seats</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (vars('grooming_appointments') as $appt): ?>
                                        <?php
                                            $size_colors = ['small' => '#4CAF50', 'medium' => '#2196F3', 'large' => '#FF9800'];
                                            $pet = $appt['pet'] ?? null;
                                            $size = $pet['size'] ?? 'small';
                                            $color = $size_colors[$size] ?? '#999';
                                        ?>
                                        <tr>
                                            <td><?= date('H:i', strtotime($appt['start_datetime'])) ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($pet['name'] ?? 'Unknown') ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($pet['breed'] ?? '-') ?></td>
                                            <td>
                                                <span class="badge" style="background-color: <?= $color ?>">
                                                    <?= htmlspecialchars($size) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars(
                                                    ($appt['customer']['first_name'] ?? '') . ' ' . ($appt['customer']['last_name'] ?? '')
                                                ) ?>
                                            </td>
                                            <td><?= htmlspecialchars($appt['service']['name'] ?? '-') ?></td>
                                            <td><?= (int) ($appt['seats_required'] ?? 1) ?></td>
                                            <td>
                                                <?php
                                                    $status = $appt['status'] ?? 'booked';
                                                    $status_class = 'secondary';
                                                    if (in_array($status, ['Completed', 'completed'])) $status_class = 'success';
                                                    elseif (in_array($status, ['Cancelled', 'cancelled'])) $status_class = 'warning';
                                                    elseif ($status === 'no_show') $status_class = 'danger';
                                                ?>
                                                <span class="badge bg-<?= $status_class ?>"><?= htmlspecialchars($status) ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($pet['behavioural_notes'])): ?>
                                                    <small class="text-warning" title="<?= htmlspecialchars($pet['behavioural_notes']) ?>">
                                                        <i class="fas fa-exclamation-triangle"></i> Behaviour note
                                                    </small>
                                                <?php endif; ?>
                                                <?php if (!empty($appt['notes'])): ?>
                                                    <small class="text-muted"><?= htmlspecialchars($appt['notes']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Walk-in Services -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-walking me-2"></i>Walk-in Services</h5>
                    <span class="badge bg-success fs-6">Revenue: &pound;<?= number_format(vars('walkin_revenue'), 2) ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty(vars('walkin_services'))): ?>
                        <p class="text-muted p-3">No walk-in services for this date.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Service</th>
                                        <th>Customer</th>
                                        <th>Price</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (vars('walkin_services') as $walkin): ?>
                                        <tr>
                                            <td><?= date('H:i', strtotime($walkin['start_datetime'])) ?></td>
                                            <td><?= htmlspecialchars($walkin['service']['name'] ?? '-') ?></td>
                                            <td>
                                                <?= htmlspecialchars(
                                                    ($walkin['customer']['first_name'] ?? '') . ' ' . ($walkin['customer']['last_name'] ?? '')
                                                ) ?>
                                            </td>
                                            <td>&pound;<?= number_format((float) ($walkin['service']['price'] ?? 0), 2) ?></td>
                                            <td>
                                                <?php if (!empty($walkin['notes'])): ?>
                                                    <small class="text-muted"><?= htmlspecialchars($walkin['notes']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@media print {
    .backend-menu, .btn, form, nav, .navbar { display: none !important; }
    .backend-page { padding: 0 !important; }
    .card { break-inside: avoid; }
}
</style>

<?php end_section('content'); ?>
