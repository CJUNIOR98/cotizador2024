<?php
/**
 * Plantilla general de modelos
 * @version 1.2.0
 *
 * Modelo de cotizacion
 */
class cotizacionModel extends Model {
  /**
  * Nombre de la tabla
  */
  public static $t1 = 'cotizaciones';
  public static $t2 = 'items_cotizaciones';
  
  // Nombre de tablas secundarias
  // public static $t2 = '__tabla 2__'; 
  // public static $t3 = '__tabla 3__'; 

  // Esquema del Modelo
  

  function __construct()
  {
    // Constructor general
  }

  static function insertOne(array $data)
  {
    return parent::add(self::$t1, $data);
  }
  
  static function all()
  {
    // Todos los registros
    $sql = sprintf('SELECT * FROM %s ORDER BY id DESC', self::$t1);
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function all_paginated()
  {
    // Todos los registros
    $sql = sprintf('SELECT * FROM %s ORDER BY id DESC', self::$t1);
    return PaginationHandler::paginate($sql);
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 
    'SELECT 
    c.*
    FROM %s c 
    WHERE c.id = :id 
    LIMIT 1';
    $sql = sprintf($sql, self::$t1);

    // Validar si existe
    $rows = parent::query($sql, ['id' => $id]);

    if (!$rows) return [];

    $cotizacion = $rows[0];

    // Cargar los items de la cotización
    $cotizacion['items'] = self::getItems($id);

    return $cotizacion;
  }

  static function update_by_id($id, $params)
  {
    return parent::update(self::$t1, ['id' => $id], $params);
  }

  static function delete_by_id($id)
  {
    return parent::remove(self::$t1, ['id' => $id]);
  }

  /**
   * Carga todos los items de una cotización
   *
   * @param int $idCotizacion
   * @return array
   */
  static private function getItems(int $idCotizacion)
  {
    $sql = 
    'SELECT 
    ic.* 
    FROM %s ic 
    WHERE ic.id_cotizacion = :idCotizacion 
    ORDER BY ic.id DESC';
    $sql = sprintf($sql, self::$t2);

    return ($items = parent::query($sql, ['idCotizacion' => $idCotizacion])) ? $items : [];
  }

  /**
   * Carga un solo item de una cotización
   *
   * @param integer $idItem
   * @return array
   */
  static function getItem(int $idItem)
  {
    return parent::list(self::$t2, ['id' => $idItem], 1);
  }

  /**
   * Borra todos los items de una cotización
   *
   * @param integer $idCotizacion
   * @return bool
   */
  static function deleteItems(int $idCotizacion)
  {
    return self::remove(self::$t2, ['id_cotizacion' => $idCotizacion]);
  }

  static function recalculate(int $idCotizacion)
  {
    // Establecer valores iniciales
    $subtotal  = 0;
    $impuestos = 0;
    $ratio     = TAXES_RATE / 100;
    $envio     = 0;
    $total     = 0;

    // Cargar conceptos
    $items = self::getItems($idCotizacion);

    if (!empty($items)) {
      foreach ($items as $item) {
        $itemSubtotal  = $item['precio'] * $item['cantidad'];
        $itemImpuestos = $itemSubtotal * $ratio;
        $itemTotal     = $itemSubtotal + $itemImpuestos;

        $subtotal += $itemSubtotal;
      }

      // Calcular impuestos con base al subtotal de todos los conceptos
      $impuestos = $subtotal * $ratio;
      $total     = $subtotal + $impuestos;
      $envio     = $total >= 1000 ? 0 : 150; // Costo de envío gratis en pedidos mayores a 1000
      $total     = $total + $envio; 
    }

    // Actualizar registro de la cotización
    $data = 
    [
      'subtotal'  => $subtotal,
      'impuestos' => $impuestos,
      'envio'     => $envio,
      'total'     => $total,
      'status'    => 'draft'
    ];

    self::update_by_id($idCotizacion, $data); // actualiza el registro

    return true;
  }
}

