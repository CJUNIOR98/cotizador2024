$(document).ready(function () {
  /**
   * Alerta para confirmar una acción establecida en un link o ruta específica
   */
  $('body').on('click', '.confirmar', async function (e) {
    e.preventDefault();

    const url    = $(this).attr('href');
    const result = await Swal.fire({
      title: "¿Estás seguro?",
      showCancelButton: true,
      confirmButtonText: "Si, está bien",
    }).then((result) => result);

    if (result.isConfirmed == false) {
      console.log('Acción cancelada.');
      return;
    }

    // Confirmar acción
    Swal.fire('¡Confirmado!', 'Redirigiendo...', "success");

    setTimeout(() => {
      window.location = url;
      return;
    }, 1500);
  });

  /**
   * Inicializa summernote el editor de texto avanzado para textareas
   */
  function init_summernote() {
    if ($('.summernote').length == 0) return;

    $('.summernote').summernote({
      placeholder: 'Escribe en este campo...',
      tabsize: 2,
      height: 300
    });
  }

  /**
   * Inicializa tooltips en todo el sitio
   */
  function init_tooltips() {
    if (['bs', 'bs5', 'bs_lumen', 'bs_lux', 'bs_litera', 'bs_vapor', 'bs_zephyr'].includes(Bee.css_framework) != true) return;

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  }

  /**
   * Dismiss notificaciones para Bulma framework
   */
  $('body').on('click', '.delete-bulma-notification', delete_bulma_notification);
  function delete_bulma_notification(e) {
    var notification = $(this).closest('.notification');
    notification.remove();
  }

  /** 
   * Configuración inicial de Toastr js | si es necesario se puede retirar o quitar 
   * */
  function init_toastr_setup() {
    if (Bee.toastr === false) return;

    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
  }

  /**
   * Desactiva el envío de formularios si hay campos faltantes y agrega clases para agregar feedback visual de los errores
   */
  (() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
  })()

  // Inicialización de elementos
  init_summernote();
  init_tooltips();
  init_toastr_setup();

  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ///////// INGRESA TU FUNCIONALIDAD CON JQUERY AQUÍ ABAJO
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
});

document.addEventListener('DOMContentLoaded', () => {
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ///////// FUNCIONES SÓLO DE PRUEBA, PUEDEN SER BORRADAS
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  init_bee_greeting();
  test_ajax();

  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  ///////// INGRESA TU FUNCIONALIDAD CON VANILLA JAVASCRIPT AQUÍ ABAJO
  ////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////
  const mainWrapperCotizacion   = document.getElementById('mainWrapperCotizacion');
  const wrapperCotizacion       = document.getElementById('wrapperCotizacion');
  const numeroCotizacion        = mainWrapperCotizacion.dataset.id;

  const clientInfoModal         = new bootstrap.Modal('#clientInfoModal');
  const clientInfoForm          = document.getElementById('clientInfoForm');
  const clientInfoFormSubmit    = document.getElementById('clientInfoFormSubmit');

  const addConceptoModal        = new bootstrap.Modal('#addConceptoModal');
  const addConceptoForm         = document.getElementById('addConceptoForm');
  const addConceptoFormSubmit   = document.getElementById('addConceptoFormSubmit');

  const editConceptoModal       = new bootstrap.Modal('#editConceptoModal');
  const editConceptoForm        = document.getElementById('editConceptoForm');
  const editConceptoFormCancel  = document.getElementById('editConceptoFormCancel');
  const editConceptoFormSubmit  = document.getElementById('editConceptoFormSubmit');
  const restartCotizacion       = document.getElementById('restartCotizacion');

  const downloadCotizacion      = document.getElementById('downloadCotizacion');
  const sendCotizacion          = document.getElementById('sendCotizacion');
  const completeCotizacion      = document.getElementById('completeCotizacion');

  clientInfoFormSubmit.addEventListener('click', agregar_cliente);
  addConceptoFormSubmit.addEventListener('click', agregar_concepto);
  restartCotizacion.addEventListener('click', reiniciar_cotizacion);
  editConceptoFormCancel.addEventListener('click', (e) => {
    e.preventDefault();
    editConceptoForm.reset();
    editConceptoModal.hide();
  });

  editConceptoFormSubmit.addEventListener('click', editar_concepto);

  completeCotizacion.addEventListener('click', completar);

  async function cargar_cotizacion(e = null) {
    if (e) e.preventDefault();

    const payload          = {
      csrf: Bee.csrf,
      numero: numeroCotizacion
    }

    showLoader();

    const res = await fetch('ajax/cargar-cotizacion', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(res.msg));

    hideLoader();

    if (res.status != 200) {
      toastr.error(res.msg);
      wrapperCotizacion.innerHTML = res.msg;
      return;
    }

    const { cotizacion, html }    = res.data;

    // Mostrar la tabla del resumen de cotización
    wrapperCotizacion.innerHTML   = html;

    // Poblar información en los campos del formulario del cliente
    clientInfoForm.elements['cliente'].value = cotizacion.cliente;
    clientInfoForm.elements['empresa'].value = cotizacion.empresa;
    clientInfoForm.elements['email'].value   = cotizacion.email;

    // Botones de borrado y edición de conceptos
    const editConceptoBtns   = document.querySelectorAll('.editConcepto');
    const deleteConceptoBtns = document.querySelectorAll('.deleteConcepto');

    Array.from(editConceptoBtns).forEach(btn => {
      const idConcepto = btn.dataset.id;

      btn.addEventListener('click', async (e) => {
        const res = await cargar_concepto(idConcepto);

        if (res.status !== 200) {
          toastr.error(res.msg);
          return;
        }

        const concepto = res.data;

        editConceptoForm.elements['id'].value       = concepto.id;
        editConceptoForm.elements['concepto'].value = concepto.concepto;
        editConceptoForm.elements['cantidad'].value = concepto.cantidad;
        editConceptoForm.elements['precio'].value   = parseFloat(concepto.precio);
        Array.from(editConceptoForm.elements['tipo'].options).forEach((option => {
          if (option.value == concepto.tipo) {
            option.selected = true;
          }
        }));
        editConceptoModal.show();
      });
    });

    Array.from(deleteConceptoBtns).forEach(btn => {
      const idConcepto = btn.dataset.id;

      btn.addEventListener('click', async (e) => {
        const confirmacion = await Swal.fire({
          title: "¿Seguro quieres borrar el concepto?",
          showCancelButton: true,
          confirmButtonText: "Borrar ahora"
        });

        if (confirmacion.isConfirmed == false) {
          return;
        }

        const payload = {
          csrf  : Bee.csrf,
          numero: numeroCotizacion
        };

        const res = await fetch(`ajax/borrar-concepto/${idConcepto}`, {
          method: 'post',
          body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .catch(err => toastr.error(err));

        if (res.status !== 200) {
          toastr.error(res.msg);
          return;
        }

        // Concepto borrado con éxito
        Swal.fire("Concepto borrado", res.msg, "success");
        cargar_cotizacion();
      });
    });

    // Revisar el estado de la cotización
    completeCotizacion.disabled = cotizacion.status === 'completed';
  }
  cargar_cotizacion();

  async function reiniciar_cotizacion(e) {
    e.preventDefault();

    const confirmacion = await Swal.fire({
      title: '¿Estás seguro?',
      showCancelButton: true,
      confirmButtonText: 'Reiniciar'
    });

    if (confirmacion.isConfirmed != true) {
      return;
    }

    const payload = {
      csrf: Bee.csrf,
      numero: numeroCotizacion
    }

    const res = await fetch('ajax/reiniciar-cotizacion', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(err));

    if (res.status !== 200) {
      toastr.error(res.msg);
      return;
    }

    toastr.success(res.msg);
    cargar_cotizacion();
  }

  async function agregar_concepto(e) {
    e.preventDefault();

    // Información del concepto
    const concepto = addConceptoForm.elements['concepto'];
    const tipo     = addConceptoForm.elements['tipo'];
    const cantidad = addConceptoForm.elements['cantidad'];
    const precio   = addConceptoForm.elements['precio'];

    showLoader();

    const payload = {
      csrf    : Bee.csrf,
      numero  : numeroCotizacion,
      concepto: concepto.value,
      tipo    : tipo.value,
      cantidad: cantidad.value,
      precio  : parseFloat(precio.value)
    }

    const res = await fetch('ajax/agregar-concepto', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(err));

    hideLoader();

    if (res.status !== 201) {
      toastr.error(res.msg);
      concepto.focus();
      return;
    }

    // Se agregó el concepto
    toastr.success(res.msg);
    addConceptoForm.reset();
    addConceptoModal.hide();
    cargar_cotizacion();
  }

  async function editar_concepto(e) {
    e.preventDefault();

    // Información del concepto
    const id       = editConceptoForm.elements['id'];
    const concepto = editConceptoForm.elements['concepto'];
    const tipo     = editConceptoForm.elements['tipo'];
    const cantidad = editConceptoForm.elements['cantidad'];
    const precio   = editConceptoForm.elements['precio'];

    showLoader();

    const payload = {
      csrf    : Bee.csrf,
      numero  : numeroCotizacion,
      id      : id.value,
      concepto: concepto.value,
      tipo    : tipo.value,
      cantidad: cantidad.value,
      precio  : parseFloat(precio.value)
    }

    const res = await fetch('ajax/editar-concepto', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(err));

    hideLoader();

    if (res.status !== 200) {
      toastr.error(res.msg);
      concepto.focus();
      return;
    }

    // Se agregó el concepto
    toastr.success(res.msg);
    editConceptoForm.reset();
    editConceptoModal.hide();
    cargar_cotizacion();
  }

  async function agregar_cliente(e) {
    e.preventDefault();

    // Información del cliente
    const cliente = clientInfoForm.elements['cliente'];
    const empresa = clientInfoForm.elements['empresa'];
    const email   = clientInfoForm.elements['email'];

    showLoader();

    const payload = {
      csrf    : Bee.csrf,
      cliente : cliente.value,
      empresa : empresa.value,
      email   : email.value,
      numero  : numeroCotizacion
    }

    const res = await fetch('ajax/agregar-cliente', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(err));

    hideLoader();

    if (res.status !== 200) {
      toastr.error(res.msg);
      cliente.focus();
      return;
    }

    // Se actualizó el cliente
    clientInfoModal.hide();
    toastr.success(res.msg);
    cargar_cotizacion();
  }

  downloadCotizacion.addEventListener('click', async (e) => {
    e.preventDefault();

    const payload = {
      csrf: Bee.csrf,
      numero: numeroCotizacion
    };

    // Petición al backend
    const res = await fetch('ajax/descargar-cotizacion', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(err));

    if (res.status !== 200) {
      Swal.fire({
        title: "¡Error!",
        text: res.msg,
        icon: "error"
      });

      return;
    }

    Swal.fire({
      title: "¡Excelente!",
      text: res.msg,
      icon: "success"
    });

    setTimeout(() => {
      // URL del archivo que quieres descargar
      const url = res.data;

      // Abre una nueva pestaña/redirección para la descarga
      const nuevaPestana = window.open(url, '_blank');
      nuevaPestana.focus();
    }, 1500);
  });

  sendCotizacion.addEventListener('click', async (e) => {
    e.preventDefault();

    const payload = {
      csrf: Bee.csrf,
      numero: numeroCotizacion
    };

    // Petición al backend
    const res = await fetch('ajax/enviar-cotizacion', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(err));

    if (res.status !== 200) {
      Swal.fire({
        title: "¡Error!",
        text: res.msg,
        icon: "error"
      });

      return;
    }

    Swal.fire({
      title: "¡Excelente!",
      text: res.msg,
      icon: "success"
    });
  });

  async function cargar_concepto(idConcepto) {
    return await fetch(`ajax/cargar-concepto/${idConcepto}`)
    .then(res => res.json())
    .catch(err => toastr.error(err));
  }

  async function completar(e) {
    e.preventDefault();

    const confirmacion = await Swal.fire({
      title: '¿Estás seguro?',
      showCancelButton: true,
      confirmButtonText: 'Completar'
    });

    if (confirmacion.isConfirmed != true) {
      return;
    }

    const payload = {
      csrf: Bee.csrf,
      numero: numeroCotizacion
    }

    const res = await fetch('ajax/completar', {
      method: 'post',
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .catch(err => toastr.error(err));

    if (res.status !== 200) {
      Swal.fire({
        title: "¡Error!",
        text: res.msg,
        icon: "error"
      });
      return;
    }

    Swal.fire({
      title: "¡Listo!",
      text: res.msg,
      icon: "success"
    });
    cargar_cotizacion();
  }
});

// Función para crear y mostrar el loader dinámicamente
function showLoader() {
  var loaderContainer = document.createElement('div');
  loaderContainer.className = 'loader-container';
  loaderContainer.id = 'loaderContainer';
  loaderContainer.style.display = 'block';

  var loader = document.createElement('div');
  loader.className = 'loader';
  loader.textContent = 'Cargando...';

  loaderContainer.appendChild(loader);
  document.body.appendChild(loaderContainer);
}

// Función para eliminar el loader dinámicamente
function hideLoader() {
  var loaderContainer = document.getElementById('loaderContainer');
  if (loaderContainer) {
    loaderContainer.parentNode.removeChild(loaderContainer);
  }
}

/**
 * Mostrar Bee object en entorno de desarrollo
 */
function init_bee_greeting() {
  console.log('////////// Bienvenido a Bee Framework Versión ' + Bee.bee_version + ' //////////');
  console.log('//////////////////// www.joystick.com.mx ////////////////////');
  if (Bee?.is_local == true) {
    console.log(Bee);
  }
}

/**
 * Prueba de peticiones ajax al backend en versión 1.1.3
 */
async function test_ajax() {
  const wrapper = document.getElementById('test_ajax');

  if (!wrapper) return;

  showLoader();

  const body = {
    csrf: Bee.csrf
  }

  try {
    const res = await fetch('ajax/test', {
      headers: { "Content-Type": "application/json" },
      method: "POST",
      body: JSON.stringify(body)
    })
      .then(res => res.json())
      .catch(error => {
        throw new Error(error);
      });

    if (res.status === 200) {
      toastr.success(res.msg, 'Prueba AJAX');
    } else {
      toastr.error(res.msg, '¡Error!');
    }
  } catch (error) {
    toastr.error(error, '¡Error!');
  }

  hideLoader();
}