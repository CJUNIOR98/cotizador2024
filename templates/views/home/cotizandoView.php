<?php require_once INCLUDES . 'header.php'; ?>
<?php require_once INCLUDES . 'navbar.php'; ?>

<!-- content -->
<div class="container py-5" id="mainWrapperCotizacion" data-id="<?php echo $d->folio_cotizacion; ?>">
  <div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <h1><?php echo sprintf('Resumen de la cotización #%s', $d->folio_cotizacion); ?></h1>
      <div class="">
        <button class="btn btn-danger" id="restartCotizacion"><i class="fas fa-undo me-2"></i>Reiniciar</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#clientInfoModal"><i class="fas fa-user fa-fw"></i> Cliente</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addConceptoModal"><i class="fas fa-plus fa-fw"></i> Agregar concepto</button>
        <button class="btn btn-light" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-circle-chevron-down"></i></button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="home"><i class="fas fa-home fa-fw me-2"></i> Regresar</a></li>
          <li><h6 class="dropdown-header">Acciones</h6></li>
          <li><a class="dropdown-item confirmar" href="<?php echo build_url(sprintf('home/borrar/%s', $d->cotizacion->id)); ?>"><i class="fas fa-trash fa-fw me-2"></i> Borrar</a></li>
        </ul>
      </div>
    </div>
    <div class="col-12">
      <?php echo Flasher::flash(); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <!-- Carta de resumen de cotización -->
      <div id="wrapperCotizacion"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div class="">
            <button class="btn btn-primary" id="downloadCotizacion"><i class="fas fa-download me-2"></i>Descargar PDF</button>
            <button class="btn btn-primary" id="sendCotizacion"><i class="fas fa-envelope me-2"></i>Enviar por correo</button>
            <button class="btn btn-danger" id="sendCotizacion" data-bs-toggle="modal" data-bs-target="#packFullStackModal"><i class="fas fa-heart me-2"></i>Promoción</button>
          </div>
          <button class="btn btn-success" id="completeCotizacion"><i class="fas fa-check me-2"></i>Completar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- ends content -->

<!-- Modal: Información del cliente -->
<div class="modal fade" id="clientInfoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="clientInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Información del cliente</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form class="modal-body" id="clientInfoForm">
        <div class="mb-3 row">
          <div class="col-12 col-md-6">
            <label for="cliente" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Walter White" required>
          </div>
          <div class="col-12 col-md-6">
            <label for="empresa" class="form-label">Empresa</label>
            <input type="text" class="form-control" id="empresa" name="empresa" placeholder="Breaking Bad" required>
          </div>
        </div>
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="walter@white.com" required>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="clientInfoFormSubmit" type="submit">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Agregar concepto -->
<div class="modal fade" id="addConceptoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addConceptoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Agregar nuevo concepto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form class="modal-body" id="addConceptoForm">
        <div class="row">
          <div class="col-12 mb-3">
            <label for="concepto" class="form-label">Concepto</label>
            <input type="text" class="form-control" id="concepto" name="concepto" placeholder="Guitarra eléctrica" required>
          </div>
          <div class="col-12 col-md-4">
            <label for="tipo" class="form-label">Tipo de producto</label>
            <select name="tipo" id="tipo" class="form-select">
              <option value="producto">Producto</option>
              <option value="servicio">Servicio</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" max="99999" value="1" required>
          </div>
          <div class="col-12 col-md-4">
            <label for="precio" class="form-label">Precio unitario</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input type="text" class="form-control" id="precio" name="precio" placeholder="0.00" required>
            </div>
          </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" id="addConceptoFormSubmit">Agregar concepto</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Editar concepto -->
<div class="modal fade" id="editConceptoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editConceptoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Editar concepto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form class="modal-body" id="editConceptoForm">
        <input type="hidden" class="form-control" id="id" name="id" required>
        <div class="row">
          <div class="col-12 mb-3">
            <label for="concepto" class="form-label">Concepto</label>
            <input type="text" class="form-control" id="concepto" name="concepto" placeholder="Guitarra eléctrica" required>
          </div>
          <div class="col-12 col-md-4">
            <label for="tipo" class="form-label">Tipo de producto</label>
            <select name="tipo" id="tipo" class="form-select">
              <option value="producto">Producto</option>
              <option value="servicio">Servicio</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" max="99999" value="1" required>
          </div>
          <div class="col-12 col-md-4">
            <label for="precio" class="form-label">Precio unitario</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">$</span>
              </div>
              <input type="text" class="form-control" id="precio" name="precio" placeholder="0.00" required>
            </div>
          </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="editConceptoFormCancel" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success" id="editConceptoFormSubmit">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Academia de joystick -->
<div class="modal fade" id="packFullStackModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="packFullStackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Gracias por tu apoyo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
       👋 ¡Hola! Te agradezco mucho tu apoyo y por haber descargado este proyecto.
        <br><br>
        Abajo hay un cupón aplicado para recibir un descuento extremo para todo mi paquete de cursos premium (+30 cursos hasta la fecha), si te es posible adquirirlo, te lo agradeceré de todo corazón, ya que me ayuda mucho a seguir creando más contenido y mantener a la Academia funcionando para todos.
        <br><br>
        ¡Gracias si decides unirte, no te arrepentirás! 🙏🙏🙏 
        <br><br>
        <h1 class="text-success fw-bold m-0">$99 <span class="fs-3">MXN</span></h1>
        <small class="text-muted">Precio regular de $1,000 MXN</small>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <a href="https://www.joystick.com.mx/precios/" class="btn btn-info" target="_blank">
          Más información
        </a>
        <a href="https://bit.ly/3StHKAF" class="btn btn-success" target="_blank">
          Comprar pack ahora <i class="fas fa-arrow-right fa-fw"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<?php require_once INCLUDES . 'footer.php'; ?>