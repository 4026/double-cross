<?php
/**
 * Application parameters for the prod environment (That is, AWS).
 */

$container->setParameter('database_driver', 'pdo_mysql');
$container->setParameter('database_host', $_SERVER['RDS_HOSTNAME']);
$container->setParameter('database_port', $_SERVER['RDS_PORT']);
$container->setParameter('database_name', $_SERVER['RDS_DB_NAME']);
$container->setParameter('database_user', $_SERVER['RDS_USERNAME']);
$container->setParameter('database_password', $_SERVER['RDS_PASSWORD']);

$container->setParameter('mailer_transport', 'smtp');
$container->setParameter('mailer_host', '127.0.0.1');
$container->setParameter('mailer_user', null);
$container->setParameter('mailer_password', null);

$container->setParameter('locale', 'en');
$container->setParameter('secret', 'ThisTokenIsNotSoSecretChangeIt');
$container->setParameter('debug_toolbar', false);
$container->setParameter('debug_redirects', false);
$container->setParameter('use_assetic_controller', false);