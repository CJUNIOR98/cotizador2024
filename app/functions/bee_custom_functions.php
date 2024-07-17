<?php
// Funciones directamente del proyecto en curso

/**
 * Ejemplo para agregar endpoints autorizados para la API
 * Esto sólo es necesario si usarás más controladores a parte de apiController como endpoints de API
 * De lo contrario no requieres anexarlos a la lista de endpoints
 */
BeeHookManager::registerHook('init_set_up', 'setUpRoutes');

function setUpRoutes(Bee $instance)
{
  // Prueba ingresando a esta URL (depende de tu ubicación del proyecto): http://localhost:8848/Bee-Framework/reportes
  $instance->addEndpoint('reportes');
  $instance->addEndpoint('citas');
  $instance->addEndpoint('sucursales');

  $instance->addAjax('ajax2'); // http://localhost:8848/Bee-Framework/ajax2
}

function format_cotizacion_status(?string $status)
{
  $classes = '';
  $icon    = '';
  $text    = '';

  switch ($status) {
    case 'draft':
      $classes = 'bg-info';
      $icon    = 'fa-eraser';
      $text    = 'Borrador';
      break;

    case 'sent':
      $classes = 'bg-primary';
      $icon    = 'fa-paper-plane';
      $text    = 'Enviada';
      break;

    case 'completed':
      $classes = 'bg-success';
      $icon    = 'fa-check';
      $text    = 'Completada';
      break;
    
    default:
      $classes = 'bg-danger';
      $icon    = 'fa-times';
      $text    = 'Desconocido';
      break;
  }

  return sprintf('<span class="badge %s"><i class="fas fa-fw %s"></i> %s</span>', $classes, $icon, $text);
}