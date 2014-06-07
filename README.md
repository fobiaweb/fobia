fobia
=====

fobia/fobia



    UPDATE st_countries SET id= id - 218;
    UPDATE st_regions SET id=id - 1610, country_id = country_id - 218;
    UPDATE st_cities SET id=id - 17848,   region_id= region_id - 1610 , country_id =country_id - 218;
