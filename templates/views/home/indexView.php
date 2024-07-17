<?php require_once INCLUDES . 'header.php'; ?>
<?php require_once INCLUDES . 'navbar.php'; ?>

<!-- content -->
<div class="container py-5">
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <h1><?php echo $d->title; ?></h1>
      <div class="">
        <a href="home/cotizando" class="btn btn-success"><i class="fas fa-plus fa-fw"></i> Nueva cotización</a>
      </div>
    </div>
    <div class="col-12">
      <?php echo Flasher::flash(); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <?php if (!empty($d->cotizaciones->rows)): ?>
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered" style="vertical-align: middle;">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Correo electrónico</th>
                <th>Total</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($d->cotizaciones->rows as $cotizacion): ?>
                <tr>
                  <td>
                    <?php echo _e($cotizacion->cliente, 'Cliente sin nombre'); ?>
                    <small class="text-muted d-block"><?php echo _e($cotizacion->empresa, 'Sin empresa'); ?></small>
                  </td>
                  <td><?php echo _e($cotizacion->email, 'Sin correo electrónico'); ?></td>
                  <td><?php echo money($cotizacion->total); ?></td>
                  <td><?php echo format_cotizacion_status($cotizacion->status); ?></td>
                  <td class="text-end">
                    <a href="<?php echo sprintf('home/cotizando/%s', $cotizacion->id); ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php echo $d->cotizaciones->pagination; ?>
      <?php else: ?>
        <div class="p-5 text-center">
          <h3>No hay cotizaciones.</h3>
          <a href="home/agregar" class="btn btn-success">Generar nueva</a>
        </div>
      <?php endif; ?>
  </div>
</div>
<!-- ends content -->

<?php require_once INCLUDES . 'footer.php'; ?>