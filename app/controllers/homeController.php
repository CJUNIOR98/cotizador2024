<?php 

class homeController extends Controller implements ControllerInterface {
  function __construct()
  {
    // Validación de sesión de usuario, descomentar si requerida
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }

    // Ejecutar la funcionalidad del Controller padre
    parent::__construct();
  }

  function index()
  {
    // Cargar todas las cotizaciones
    $cotizaciones = cotizacionModel::all_paginated();

    $this->setTitle('Inicio');
    $this->addToData('cotizaciones', $cotizaciones);
    $this->setView('index');
    $this->render();
  }

  function cotizando($idCotizacion = null)
  {
    // Validar que exista la cotización
    if (!$cotizacion = cotizacionModel::by_id($idCotizacion)) {
      // Generar una nueva cotización
      $data = 
      [
        'numero'    => rand(111111, 999999),
        'cliente'   => '',
        'empresa'   => '',
        'email'     => '',
        'subtotal'  => 0,
        'impuestos' => 0,
        'envio'     => 0,
        'total'     => 0,
        'status'    => 'draft',
        'creado'    => now()
      ];

      // Se inserta la nueva cotización y se carga para mandar a la vista
      $idCotizacion = cotizacionModel::insertOne($data);
      $cotizacion   = cotizacionModel::by_id($idCotizacion);

      Redirect::to(sprintf('home/cotizando/%s', $idCotizacion));
    }

    $this->setTitle(sprintf('Cotización #%s', $cotizacion['numero']));
    $this->addToData('folio_cotizacion', $cotizacion['numero']);
    $this->addToData('cotizacion', $cotizacion);
    $this->setView('cotizando');
    $this->render();
  }

  function borrar($idCotizacion = null)
  {
    // Validar que exista la cotización
    try {
      if (!$cotizacion = cotizacionModel::by_id($idCotizacion)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      $numero = $cotizacion['numero'];
      $status = $cotizacion['status'];
      $pdf    = $cotizacion['pdf'];
      $items  = $cotizacion['items'];

      // Validar estado
      if ($status === 'completed') {
        throw new Exception('No puedes borrar esta cotización porque está completada.');
      }

      // Borrar cotización
      cotizacionModel::remove(cotizacionModel::$t1, ['id' => $idCotizacion], 1);
      Flasher::success(sprintf('La cotización <b>%s</b> ha sido borrada con éxito.', $numero));

      // Borrar items
      if (!empty($items)) {
        cotizacionModel::remove(cotizacionModel::$t2, ['id_cotizacion' => $idCotizacion]);
      }

      // Borrar el pdf si existe
      if (is_file(UPLOADS . $pdf)) {
        unlink(UPLOADS . $pdf);
        Flasher::success('Se ha borrado el documento PDF de la cotización.');
      }

      Redirect::to('home');

    } catch (Exception $e) {
      Flasher::error($e->getMessage());
      Redirect::back();
    }
  }
}