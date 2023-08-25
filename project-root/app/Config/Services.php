<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function brokerService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('brokerService');
        }

        return new \App\Services\BrokerService();
    }

    public static function authenticationService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('authenticationService');
        }

        return new \App\Services\AuthenticationService();
    }

    public static function locationService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('locationService');
        }

        return new \App\Services\LocationService();
    }

    public static function clientService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('clientService');
        }

        return new \App\Services\ClientService();
    }

    public static function entityService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('entityService');
        }

        return new \App\Services\EntityService();
    }

    public static function countyService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('countyService');
        }

        return new \App\Services\CountyService();
    }

    public static function constructionService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('constructionService');
        }

        return new \App\Services\ConstructionService();
    }

    public static function occupancyService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('occupancyService');
        }

        return new \App\Services\OccupancyService();
    }
}
