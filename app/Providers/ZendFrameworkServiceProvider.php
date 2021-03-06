<?php namespace IXP\Providers;

/*
 * Copyright (C) 2009-2016 Internet Neutral Exchange Association Limited.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */



use Illuminate\Support\ServiceProvider;
use IXP\Services\Helpdesk\ConfigurationException;
use Config;

/**
 * ZendFramwork Service Provider
 *
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @category   IXP
 * @package    IXP\Providers
 * @copyright  Copyright (c) 2009 - 2016, Internet Neutral Exchange Association Ltd
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class ZendFrameworkServiceProvider extends ServiceProvider {

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( 'ZendFramwork', function($app) {

            // Define path to application directory
            defined('APPLICATION_PATH')
                || define('APPLICATION_PATH', realpath( __DIR__ . '/../../application' ) );

            // Define application environment
            if( php_sapi_name() == 'cli-server' ) {
                // running under PHP's built in web server: php -S
                // as such, .htaccess is not processed
                include( __DIR__ . '/../../bin/utils.inc' );
                define( 'APPLICATION_ENV', scriptutils_get_application_env() );
            } else {
                // probably Apache or other web server
                defined('APPLICATION_ENV')
                    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
            }

            /** Zend_Application */
            require_once 'Zend/Application.php';

            require_once( APPLICATION_PATH . '/../library/IXP/Version.php' );

            // Create application, bootstrap, and run
            $application = new \Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
            );

            return $application->bootstrap();
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ZendFramwork'];
    }


}
