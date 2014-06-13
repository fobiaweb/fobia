<?php
/**
 * autoload_apimap.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


return array(
    'stdata.getCountries'       => '\\Api\\Stdata::getCountries',
    'stdata.getRegions'         => '\\Api\\Stdata::getRegions',
    'stdata.getCountriesById'   => '\\Api\\Stdata::getCountriesById',
    'stdata.getCities'          => '\\Api\\Stdata::getCities',
    'stdata.getCitiesById'      => '\\Api\\Stdata::getCitiesById',
    'users.create'              => '\\Api\\Users::create',
    'users.edit'                => '\\Api\\Users::edit',
    'users.delete'              => '\\Api\\Users::delete',
    'users.get'                 => '\\Api\\Users::get',
    'users.search'              => '\\Api\\Users::search',
    'events.subscribe'          => '\\Api\\Events::subscribe',
    'events.unsubscribe'        => '\\Api\\Events::unsubscribe',
);