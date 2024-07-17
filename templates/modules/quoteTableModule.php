<div class="row mt-3">
  <div class="col-12 col-md-3 mb-3">
    <div class="card">
      <div class="card-body">
        <h4 class="m-0"><?php echo _e($d->cliente, 'Cliente desconocido'); ?></h4>
        <p><i class="fas fa-envelope fa-fw me-2 text-dark fs-6"></i><?php echo _e($d->email, 'Sin correo electrónico'); ?></p>
        <p class="fw-bold m-0"><i class="fas fa-building fa-fw me-2 text-dark fs-6"></i><?php echo _e($d->empresa, 'Sin empresa'); ?></p>
      </div>
    </div>
  </div>
  <div class="col-12">
    <?php if (!empty($d->items)) : ?>
      <div class="table-responsive">
        <table class="table table-striped table-bordered" style="vertical-align:middle;">
          <thead>
            <tr>
              <th width="5%"></th>
              <th class="text-center">Tipo</th>
              <th width="50%">Concepto</th>
              <th class="text-center">Precio</th>
              <th class="text-center">Cantidad</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($d->items as $item) : ?>
              <tr>
                <td>
                  <button class="btn btn-sm btn-danger deleteConcepto" data-id="<?php echo $item->id; ?>"><i class="fas fa-trash"></i></button>
                </td>
                <td class="text-center">
                  <span class="d-flex justify-content-center align-items-center">
                    <img class="me-2" src="<?php echo $item->tipo === 'producto' ? get_image('product.png') : get_image('service.png'); ?>" alt="<?php echo $item->concepto; ?>" style="width: 15px;">
                    <?php echo $item->tipo === 'producto' ? 'Producto' : 'Servicio'; ?>
                  </span>
                </td>
                <td>
                  <div class="d-flex flex-row align-items-center">
                    <button class="btn btn-sm btn-light me-2 editConcepto" data-id="<?php echo $item->id; ?>"><i class="fas fa-edit"></i></button>
                    <?php echo $item->concepto; ?>
                  </div>
                </td>
                <td class="text-center"><?php echo money($item->precio); ?></td>
                <td class="text-center"><?php echo $item->cantidad; ?></td>
                <td class="text-end"><?php echo money($item->total); ?></td>
              </tr>
            <?php endforeach; ?>
            <tr>
              <td class="text-end" colspan="5">Subtotal</td>
              <td class="text-end"><?php echo money($d->subtotal); ?></td>
            </tr>
            <tr>
              <td class="text-end" colspan="5">Impuestos</td>
              <td class="text-end"><?php echo money($d->impuestos); ?></td>
            </tr>
            <tr>
              <td class="text-end" colspan="5">Envío</td>
              <td class="text-end"><?php echo money($d->envio); ?></td>
            </tr>
            <tr>
              <td class="text-end" colspan="6">
                <b>Total</b>
                <h3 class="text-success"><b><?php echo money($d->total); ?></b></h3>
                <small class="text-muted"><?php echo sprintf('Impuestos incluidos %s%% IVA', TAXES_RATE); ?></small>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php else : ?>
      <div class="text-center p-5">
        <img src="<?php echo get_image('empty.png'); ?>" alt="Sin conceptos" class="img-fluid" style="width: 150px;">
        <h3 class="text-muted mt-3">La cotización está vacía</h3>
        <p class="text-muted">Agrega conceptos para continuar.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
