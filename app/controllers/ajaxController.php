<?php 
use Dompdf\Dompdf;
use Dompdf\Options;

class ajaxController extends Controller implements ControllerInterface {

  function __construct()
  {
    parent::__construct('ajax');
  }

  function index()
  {
    http_response_code(404);
    json_output(json_build(404, null, 'Ruta no encontrada.'));
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////// FUNCIONALIDADES DE PRUEBA | PUEDES BORRAR TODO ESTO
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////

  /**
   * Realiza una prueba de conexióna la base de datos
   * @since 1.1.4
   *
   * @return void
   */
  function db_test()
  {
    try {
      $db = Db::connect(true);
      json_output(json_build(200, null, sprintf('Conexión realizada con éxito a la base de datos <b>%s</b>.', is_local() ? LDB_NAME : add_ellipsis(DB_NAME, 5))));
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  /**
   * Función de pruebas bee framework
   * @since 1.1.4
   *
   * @return void
   */
  function test()
  {
    try {
      json_output(json_build(200, is_ajax(), 'Prueba de AJAX realizada con éxito.'));
    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  /**
   * Función de pruebas para Vuejs
   * @since 1.1.4
   *
   * @return void
   */
  function test_posts()
  {
    try {
      $posts = Model::list('pruebas');
      json_output(json_build(200, $posts));

    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  /**
   * Función de pruebas para cargar un post de la base de datos
   *
   * @return void
   */
  function test_get_post()
  {
    try {
      if (!check_posted_data(['id'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      if (!$post = Model::list('pruebas', ['id' => $this->data['id']], 1)) {
        throw new Exception(get_bee_message('not_found'));
      }

      json_output(json_build(200, $post));

    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  /**
   * Función de pruebas para agregar un post a la base de datos
   *
   * @return void
   */
  function test_add_post()
  {
    try {
      if (!check_posted_data(['titulo','contenido','nombre'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      if (!Auth::validate()) {
        throw new Exception(get_bee_message('auth'));
      }

      $id        = null;
      $nombre    = clean($this->data['nombre']);
      $titulo    = clean($this->data['titulo']);
      $contenido = clean($this->data['contenido']);

      $data =
      [
        'nombre'    => $nombre,
        'titulo'    => $titulo,
        'contenido' => $contenido,
        'creado'    => now()
      ];

      if (!$id = Model::add('pruebas', $data)) {
        throw new Exception(get_bee_message('not_added'));
      }

      $post = Model::list('pruebas', ['id' => $id], 1);
      
      json_output(json_build(201, $post, get_bee_message('added')));

    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  /**
   * Función de pruebas para actualizar un post de la base de datos
   *
   * @return void
   */
  function test_update_post()
  {
    try {
      if (!check_posted_data(['id','titulo','contenido','nombre'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      $id        = clean($this->data['id']);
      $nombre    = clean($this->data['nombre']);
      $titulo    = clean($this->data['titulo']);
      $contenido = clean($this->data['contenido']);

      if (!$post = Model::list('pruebas', ['id' => $id], 1)) {
        throw new Exception(get_bee_message('not_found'));
      }

      $data =
      [
        'nombre'    => $nombre,
        'titulo'    => $titulo,
        'contenido' => $contenido
      ];

      if (!Model::update('pruebas', ['id' => $id], $data)) {
        throw new Exception(get_bee_message('not_updated'));
      }

      $post = Model::list('pruebas', ['id' => $id], 1);
      
      json_output(json_build(200, $post, get_bee_message('updated')));

    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  /**
   * Función de pruebas para borrar un post de la base de datos
   * @since 1.1.4
   *
   * @return void
   */
  function test_delete_post()
  {
    try {
      if (!check_posted_data(['id'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      if (!$post = Model::list('pruebas', ['id' => $this->data['id']], 1)) {
        throw new Exception(get_bee_message('not_found'));
      }

      if (!Model::remove('pruebas', ['id' => $post['id']])) {
        throw new Exception(get_bee_message('not_deleted'));
      }
      
      json_output(json_build(200, $post, 'Post borrado con éxito.'));

    } catch (Exception $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////// INSERTA TUS MÉTODOS DESPUÉS DE ESTE BLOQUE
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////

  function cargar_cotizacion()
  {
    try {
      if (!check_posted_data(['numero'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      $numeroCotizacion = sanitize_input($this->data['numero']);

      // Vamos a cargar la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      // Cargar toda la información
      $cotizacion = cotizacionModel::by_id($cotizacion['id']);
      $html       = get_module('quoteTable', $cotizacion);

      json_output(json_build(200, ['cotizacion' => $cotizacion, 'html' => $html]));

    } catch (Exception  $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function reiniciar_cotizacion()
  {
    try {
      // Verificar acceso
      if (!check_posted_data(['numero'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      $numeroCotizacion = sanitize_input($this->data['numero']);

      // Vamos a cargar la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      // Cargar toda la información
      $idCotizacion = $cotizacion['id'];
      $cotizacion   = cotizacionModel::by_id($idCotizacion);
      $items        = $cotizacion['items'];

      // Validar si hay conceptos
      if(empty($items)) {
        throw new Exception('No es necesario reiniciar la cotización, no hay conceptos en ella.');
      }
    
      // Borrar todos los conceptos
      cotizacionModel::deleteItems($idCotizacion);

      // Actualizar todo a cero y en blanco
      $data = 
      [
        'cliente'   => '',
        'empresa'   => '',
        'email'     => '',
        'subtotal'  => 0,
        'impuestos' => 0,
        'envio'     => 0,
        'total'     => 0,
        'status'    => 'draft'
      ];
      
      cotizacionModel::update_by_id($idCotizacion, $data);

      // Recargar la cotización
      $cotizacion = cotizacionModel::by_id($idCotizacion);
    
      json_output(json_build(200, $cotizacion, 'La cotización se ha reiniciado con éxito.'));
  
    } catch (Exception  $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function cargar_concepto($id = null)
  {
    try {
      $idConcepto = $id;

      if (!$item = cotizacionModel::getItem($idConcepto)) {
        throw new Exception('No existe el concepto en la base de datos.');
      }

      json_output(json_build(200, $item));

    } catch (Exception  $e) {
      json_output(json_build(400, null, $e->getMessage()));
    }
  }

  function cargar_conceptos($idCotizacion = null)
  {
    
  }

  function agregar_cliente()
  {
    try {
      if (!check_posted_data(['cliente', 'empresa', 'email', 'numero'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      array_map('sanitize_input', $this->data);

      $numeroCotizacion = $this->data['numero'];
      $cliente          = $this->data['cliente'];
      $empresa          = $this->data['empresa'];
      $email            = $this->data['email'];

      // Validar la existencia de la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      $idCotizacion = $cotizacion['id'];

      // Validar el nombre del cliente
      if (!is_alphanumeric($cliente, true) || strlen($cliente) < 5) {
        throw new Exception('Ingresa un nombre válido, debe contar con mínimo 5 caracteres.');
      }

      // Validar el nombre de la empresa
      if (!is_alphanumeric($empresa, true) || strlen($empresa) < 5) {
        throw new Exception('Ingresa una empresa válida, debe contar con mínimo 5 caracteres.');
      }

      // Validar el correo electrónico
      if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        throw new Exception('Ingresa un correo electrónico válido por favor.');
      }

      $data =
      [
        'cliente' => $cliente,
        'empresa' => $empresa,
        'email'   => $email
      ];

      // Actualizar información
      cotizacionModel::update_by_id($idCotizacion, $data);

      // Volver a cargar
      $cotizacion = cotizacionModel::by_id($idCotizacion);

      json_output(json_build(200, $cotizacion, 'Cliente actualizado con éxito.'));

    } catch (Exception $e) {
      json_output(json_build(400, [], $e->getMessage()));
    }
  }

  function agregar_concepto()
  {
    try {
      if (!check_posted_data(['numero', 'concepto' ,'tipo' ,'cantidad' ,'precio'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      array_map('sanitize_input', $this->data);

      $numeroCotizacion = $this->data['numero'];
      $concepto         = $this->data['concepto'];
      $tipo             = $this->data['tipo'];
      $cantidad         = (int) $this->data['cantidad'];
      $precio           = (float) $this->data['precio'];
      $impuestos        = 0;
      $total            = 0;

      // Validar la existencia de la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      $idCotizacion = $cotizacion['id']; // Para insertar en la db

      if (strlen($concepto) < 5) {
        throw new Exception('Ingresa un concepto de 5 o más caracteres.');
      }

      if (!in_array($tipo, ['producto', 'servicio'])) {
        throw new Exception('El tipo de producto no es válido.');
      }

      if ($cantidad == 0) {
        throw new Exception('Ingresa una cantidad mayor a 0.');
      }

      if ($precio <= 0) {
        throw new Exception('Ingresa un precio mayor a $0 pesos.');
      }

      // Registrando el concepto
      $subtotal  = $precio * $cantidad;
      $impuestos = $subtotal * (TAXES_RATE / 100);
      $total     = $subtotal + $impuestos;
      $data      =
      [
        'id_cotizacion' => $idCotizacion,
        'concepto'      => $concepto,
        'tipo'          => $tipo,
        'cantidad'      => $cantidad,
        'precio'        => $precio,
        'impuestos'     => $impuestos,
        'total'         => $total
      ];

      // Se agrega a la base de datos
      if (!$idItem = cotizacionModel::add(cotizacionModel::$t2, $data)) {
        throw new Exception('Hubo un problema al agregar el concepto.');
      }

      // Recalcular la información de la cotización
      cotizacionModel::recalculate($idCotizacion);

      // Cargar la información del registro
      $item = cotizacionModel::getItem($idItem);

      json_output(json_build(201, $item, 'Concepto agregado con éxito.'));

    } catch (Exception $e) {
      json_output(json_build(400, [], $e->getMessage()));
    }
  }

  function editar_concepto()
  {
    try {
      if (!check_posted_data(['id', 'numero', 'concepto' ,'tipo' ,'cantidad' ,'precio'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      array_map('sanitize_input', $this->data);

      $numeroCotizacion = $this->data['numero'];
      $id               = $this->data['id'];
      $concepto         = $this->data['concepto'];
      $tipo             = $this->data['tipo'];
      $cantidad         = (int) $this->data['cantidad'];
      $precio           = (float) $this->data['precio'];
      $impuestos        = 0;
      $total            = 0;

      // Validar la existencia de la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      $idCotizacion = $cotizacion['id']; // Para insertar en la db

      // Validar que exista el concepto
      if (!$item = cotizacionModel::getItem($id)) {
        throw new Exception('No existe el concepto en la base de datos.');
      }

      // Validar que pertenezca a esta cotización
      if ($item['id_cotizacion'] !== $idCotizacion) {
        throw new Exception('No puedes modificar este concepto.');
      }

      if (strlen($concepto) < 5) {
        throw new Exception('Ingresa un concepto de 5 o más caracteres.');
      }

      if (!in_array($tipo, ['producto', 'servicio'])) {
        throw new Exception('El tipo de producto no es válido.');
      }

      if ($cantidad == 0) {
        throw new Exception('Ingresa una cantidad mayor a 0.');
      }

      if ($precio <= 0) {
        throw new Exception('Ingresa un precio mayor a $0 pesos.');
      }

      // Registrando el concepto
      $subtotal  = $precio * $cantidad;
      $impuestos = $subtotal * (TAXES_RATE / 100);
      $total     = $subtotal + $impuestos;
      $data      =
      [
        'concepto'      => $concepto,
        'tipo'          => $tipo,
        'cantidad'      => $cantidad,
        'precio'        => $precio,
        'impuestos'     => $impuestos,
        'total'         => $total
      ];

      // Se agrega a la base de datos
      if (!$idItem = cotizacionModel::update(cotizacionModel::$t2, ['id' => $id], $data)) {
        throw new Exception('Hubo un problema al agregar el concepto.');
      }

      // Recalcular la información de la cotización
      cotizacionModel::recalculate($idCotizacion);

      // Cargar la información del registro
      $item = cotizacionModel::getItem($idItem);

      json_output(json_build(200, $item, 'Concepto actualizado con éxito.'));

    } catch (Exception $e) {
      json_output(json_build(400, [], $e->getMessage()));
    }
  }

  function borrar_concepto($id = null)
  {
    try {
      if (!check_posted_data(['numero'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      array_map('sanitize_input', $this->data);

      $idConcepto       = (int) $id;
      $numeroCotizacion = $this->data['numero'];

      // Vamos a cargar la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      // Validar que existe el concepto
      if (!$item = cotizacionModel::getItem($idConcepto)) {
        throw new Exception('No existe el concepto en la base de datos.');
      }

      $idCotizacion = $cotizacion['id'];
      $cotizacion   = cotizacionModel::by_id($idCotizacion);

      // Validar el estado de la cotización
      if ($cotizacion['status'] == 'completed') {
        throw new Exception('La cotización ya no puede ser editada.');
      }

      // Borrar concepto
      cotizacionModel::remove(cotizacionModel::$t2, ['id' => $idConcepto], 1);

      // Recalcular la información de la cotización
      cotizacionModel::recalculate($idCotizacion);

      json_output(json_build(200, [], sprintf('Concepto <b>%s</b> borrado con éxito.', $item['concepto'])));

    } catch (Exception $e) {
      json_output(json_build(400, [], $e->getMessage()));
    }
  }

  function descargar_cotizacion()
  {
    try {
      if (!check_posted_data(['numero'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      $numeroCotizacion = sanitize_input($this->data['numero']);

      // Vamos a cargar la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      // Cargar toda la información
      $idCotizacion = $cotizacion['id'];
      $cotizacion   = cotizacionModel::by_id($idCotizacion);
      $items        = $cotizacion['items'];

      // Validar que haya información del cliente
      if (!filter_var($cotizacion['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Es requerido un correo electrónico válido del cliente.');
      }

      if (empty($items)) {
        throw new Exception('No hay conceptos en la cotización.');
      }

      // Nombre del archivo
      $filename     = sprintf('Cotización-%s.pdf', $numeroCotizacion);

      // Generando el archivo PDF
      $html         = get_module('pdf', $cotizacion);
      $download     = sprintf(UPLOADED . '%s', $filename);
      
      // Mostrar imágenes remotas
      $options = new Options();
      $options->set('isRemoteEnabled', true);

      // Instancia de la clase
      $pdf = new Dompdf($options);

      // Formato
      $pdf->setPaper('A4');

      // Contenido
      $pdf->loadHtml($html);
      $pdf->render();
      $output = $pdf->output();
      file_put_contents(UPLOADS . $filename, $output);

      // Actualizar el PDF en la base de datos
      cotizacionModel::update_by_id($idCotizacion, ['pdf' => $filename]);

      // $pdf->stream($filename);
      json_output(json_build(200, $download, 'Cotización descargada con éxito.'));

    } catch (Exception $e) {
      json_output(json_build(400, [], $e->getMessage()));
    }
  }

  function enviar_cotizacion()
  {
    try {
      if (!check_posted_data(['numero'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      $numeroCotizacion = sanitize_input($this->data['numero']);

      // Vamos a cargar la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      // Cargar toda la información
      $idCotizacion = $cotizacion['id'];
      $cotizacion   = cotizacionModel::by_id($idCotizacion);
      $cliente      = $cotizacion['cliente'];
      $email        = $cotizacion['email'];
      $items        = $cotizacion['items'];
      $pdf          = $cotizacion['pdf'];

      // Validar que haya información del cliente
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Es requerido un correo electrónico válido del cliente.');
      }

      // Validar que haya conceptos
      if (empty($items)) {
        throw new Exception('No hay conceptos en la cotización.');
      }

      // Validar si existe el PDF ya
      if (empty($pdf) || !is_file(UPLOADS . $pdf)) {
        // Nombre del archivo
        $filename     = sprintf('Cotización-%s.pdf', $numeroCotizacion);
  
        // Generando el archivo PDF
        $html         = get_module('pdf', $cotizacion);
        
        // Mostrar imágenes remotas
        $options = new Options();
        $options->set('isRemoteEnabled', true);
  
        // Instancia de la clase
        $pdf = new Dompdf($options);
  
        // Formato
        $pdf->setPaper('A4');
  
        // Contenido
        $pdf->loadHtml($html);
        $pdf->render();
        $output = $pdf->output();
        file_put_contents(UPLOADS . $filename, $output);

        // Actualizar el PDF en la base de datos
        cotizacionModel::update_by_id($idCotizacion, ['pdf' => $filename]);
        $cotizacion = cotizacionModel::by_id($idCotizacion);

        // Nombre del pdf
        $pdf = $cotizacion['pdf'];
      }

      // Enviar por correo electrónico
      $subject     = sprintf('Cotización número %s recibida', $numeroCotizacion);
      $alt         = sprintf('Nueva cotización de %s recibida', get_sitename());
      $body        = '<h1>Nueva cotización</h1><br><p>Hola <b>%s</b>, has recibido una cotización con folio <b>%s</b> por parte de <b>%s</b>, se encuentra adjunta a este correo.</p>';
      $body        = sprintf($body, $cliente, $numeroCotizacion, get_sitename());
      $attachment  = UPLOADS . $pdf;

      $mail = new BeeMailer;
      $mail->useTemplate(true);
      $mail->disableDebug();
      $mail->disableSmtp();
      $mail->sendTo($email);
      $mail->setSubject($subject);
      $mail->setAlt($alt);
      $mail->setBody($body);
      $mail->addAttachment($attachment);

      if(!$mail->send()) {
        throw new Exception('Hubo un problema al enviar el correo electrónico.');
      }

      // Actualizar status de cotización
      cotizacionModel::update_by_id($idCotizacion, ['status' => 'sent']);

      json_output(json_build(200, $cotizacion, 'Cotización enviada con éxito.'));

    } catch (Exception $e) {
      json_output(json_build(400, [], $e->getMessage()));
    }
  }

  function completar()
  {
    try {
      if (!check_posted_data(['numero'], $this->data)) {
        throw new Exception('Parámetros faltantes.');
      }

      array_map('sanitize_input', $this->data);

      $numeroCotizacion = $this->data['numero'];

      // Validar la existencia de la cotización
      if (!$cotizacion = cotizacionModel::list(cotizacionModel::$t1, ['numero' => $numeroCotizacion], 1)) {
        throw new Exception('No existe la cotización en la base de datos.');
      }

      $cotizacion   = cotizacionModel::by_id($cotizacion['id']);
      $idCotizacion = $cotizacion['id'];
      $status       = $cotizacion['status'];
      $items        = $cotizacion['items'];
      $cliente      = $cotizacion['cliente'];
      $empresa      = $cotizacion['empresa'];
      $email        = $cotizacion['email'];

      // Validar el estado de la cotización
      if ($status === 'completed') {
        throw new Exception('Esta cotización ya está completada.');
      }

      if (!in_array($status, ['draft', 'sent'])) {
        throw new Exception('No puedes completar esta cotización.');
      }

      if (empty($items)) {
        throw new Exception('No hay conceptos en la cotización, no puedes completarla.');
      }

      if (empty($cliente) || empty($empresa) || empty($email)) {
        throw new Exception('La información del cliente está incompleta.');
      }

      // Actualizar información
      cotizacionModel::update_by_id($idCotizacion, ['status' => 'completed']);

      // Volver a cargar
      $cotizacion = cotizacionModel::by_id($idCotizacion);

      json_output(json_build(200, $cotizacion, 'Cotización completada con éxito.'));

    } catch (Exception $e) {
      json_output(json_build(400, [], $e->getMessage()));
    }
  }
}