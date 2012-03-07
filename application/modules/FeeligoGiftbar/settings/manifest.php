<?php return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'feeligo-giftbar',
    'version' => '4.1.0.1.1beta',
    'path' => 'application/modules/FeeligoGiftbar',
    'title' => 'Feeligo GiftBar',
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
      0 => 'application/modules/FeeligoGiftbar',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/feeligo-giftbar.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onRenderLayoutDefault',
      'resource' => 'FeeligoGiftbar_Plugin_Core'
    )
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    
  )
); ?>