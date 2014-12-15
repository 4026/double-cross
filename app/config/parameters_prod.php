<?php
/**
 * Application parameters for the prod environment (That is, AWS).
 */

if (php_sapi_name() == 'cli')
{
    /* In the CLI (used by the Symfony app/console script to perform setup & maintenance tasks), RDS environment variables
     * aren't set automatically, so we're going to import them manually.
     *
     * This approach lifted from this SO answer: http://stackoverflow.com/a/23936080
     */

    $env_vars_file = "/opt/elasticbeanstalk/support/envvars"; //This seems to be where Elastic Beanstalk stores the RDS env vars.

    if (file_exists($env_vars_file) && is_file($env_vars_file)) {
        $contents = file_get_contents($env_vars_file);
        foreach(explode("\n", $contents) as $line) {
            if (empty($line)) continue;

            $new_line = str_replace('export ', '', $line);
            $first_part = strpos($new_line, '=');

            $last_part = substr($new_line, $first_part, strlen($new_line));
            $variable_value = str_replace(array('=', '"'), array('',''), $last_part);

            $variable_name = substr($new_line, 0, $first_part);
            putenv($variable_name."=".$variable_value);
        }
    }
}

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
$container->setParameter('secret', $_SERVER['RDS_PASSWORD']);
$container->setParameter('debug_toolbar', false);
$container->setParameter('debug_redirects', false);
$container->setParameter('use_assetic_controller', false);