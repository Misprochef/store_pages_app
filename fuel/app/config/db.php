<?php
return array(
  'development' => array(
    'type'           => 'pdo',
    'connection'     => array(
      'hostname'       => 'localhost',
      'port'           => '8889',
      'dsn'            => 'mysql:host=localhost;dbname=store_pages_app_db',
      'username'       => 'Misprochef',
      'password'       => 'y0uR_p@ssW0rd',
      'persistent'     => false,
      'compress'       => false,
    ),
    'identifier'     => '"',
    'table_prefix'   => '',
    'charset'        => 'utf8',
    'enable_cache'   => true,
    'profiling'      => false,
    'readonly'       => false,
  ),
);