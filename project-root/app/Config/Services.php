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

    public static function foundationService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('foundationService');
        }

        return new \App\Services\FoundationService();
    }

    public static function transactionTypeService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('transactionTypeService');
        }

        return new \App\Services\TransactionTypeService();
    }

    public static function fireCodeService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('fireCodeService');
        }

        return new \App\Services\FireCodeService();
    }

    public static function coverageService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('coverageService');
        }

        return new \App\Services\CoverageService();
    }

    public static function insurerService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('insurerService');
        }

        return new \App\Services\InsurerService();
    }

    public static function slaPolicyService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('slaPolicyService');
        }

        return new \App\Services\SLAPolicyService();
    }

    public static function slaSettingService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('slaSettingService');
        }

        return new \App\Services\SLASettingService();
    }

    public static function floodQuoteService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('floodQuoteService');
        }

        return new \App\Services\FloodQuoteService();
    }

    public static function floodZoneService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('floodZoneService');
        }

        return new \App\Services\FloodZoneService();
    }

    public static function floodFoundationService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('floodFoundationService');
        }

        return new \App\Services\FloodFoundationService();
    }

    public static function floodOccupancyService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('floodOccupancyService');
        }

        return new \App\Services\FloodOccupancyService();
    }

    public static function deductibleService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('deductibleService');
        }

        return new \App\Services\DeductibleService();
    }

    public static function bindAuthorityService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('bindAuthorityService');
        }

        return new \App\Services\BindAuthorityService();
    }

    public static function producerService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('producerService');
        }

        return new \App\Services\ProducerService();
    }

    public static function floodQuoteMortgageService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('floodQuoteMortgageService');
        }

        return new \App\Services\FloodQuoteMortgageService();
    }

    public static function commercialOccupancyService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('commercialOccupancyService');
        }

        return new \App\Services\CommercialOccupancyService();
    }

    public static function hiscoxQuoteService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('hiscoxQuoteService');
        }

        return new \App\Services\HiscoxQuoteService();
    }
}
