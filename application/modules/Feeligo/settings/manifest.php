<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'feeligo',
    'version' => '4.1.0.2.6',
    'path' => 'application/modules/Feeligo',
    'title' => 'Feeligo',
    'description' => 'Monetize and Engage your community by allowing your members to send virtual gifts to each other!',
    'author' => 'Feeligo',
    'callback' => 
    array (
      'class' => 'Engine_Package_Installer_Module',
    ),
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
      5 => 'remove'
    ),
    'directories' => 
    array (
      0 => 'application/modules/Feeligo',
    ),
    'files' => 
    array (
      'application/languages/en/feeligo.csv',
      'application/languages/fr/feeligo.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onRenderLayoutDefault',
      'resource' => 'Feeligo_Plugin_Core'
    )
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'feeligo_gift'
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    // User - Specific
    'feeligo_api' => array(
      'route' => 'feeligo/api',
      
      'defaults' => array(
        'module' => 'feeligo',
        'controller' => 'api',
        'action' => 'index'
      )
    ),
  )
); ?>