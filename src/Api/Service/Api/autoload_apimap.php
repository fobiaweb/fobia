<?php
/**
 * autoload_apimap.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


return array(
    'stdata.getCountries' => '\\Congress\\Service\\Api\\Stdata::getCountries',
    'stdata.getRegions ' => '\\Congress\\Service\\Api\\Stdata::getRegions',
    'stdata.getCountriesById ' => '\\Congress\\Service\\Api\\Stdata::getCountriesById',
    'stdata.getCities ' => '\\Congress\\Service\\Api\\Stdata::getCities',
    'stdata.getCitiesById ' => '\\Congress\\Service\\Api\\Stdata::getCitiesById',
    'users.create'        => '\\Congress\\Service\\Api\\Users::create',
    'users.edit'          => '\\Congress\\Service\\Api\\Users::edit',
    'users.delete'        => '\\Congress\\Service\\Api\\Users::delete',
    'users.get'           => '\\Congress\\Service\\Api\\Users::get',
    'users.search'        => '\\Congress\\Service\\Api\\Users::search',
    'events.subscribe'        => '\\Congress\\Service\\Api\\Events::subscribe',
    'events.unsubscribe'        => '\\Congress\\Service\\Api\\Events::unsubscribe',
);