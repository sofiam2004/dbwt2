<?php
/**
 * Mapping of paths to controllers.
 * Note, that the path only supports one level of directory depth:
 *     /demo is ok,
 *     /demo/subpage will not work as expected
 */

return array(
    '/'             => "HomeController@index",
    "/demo"         => "DemoController@demo",
    '/dbconnect'    => 'DemoController@dbconnect',
    '/debug'        => 'HomeController@debug',
    '/error'        => 'DemoController@error',
    '/requestdata'  => 'DemoController@requestdata',
    '/werbeseite'   => 'HomeController@displayPage',
    '/wunschgericht'=> 'HomeController@wunschgericht',
    '/anmeldung'    => 'LoginController@showLoginForm',
    '/anmeldung_verifizieren' => 'LoginController@verifyLogin',
    '/abmeldung' => 'LoginController@logout',



    '/m4_6a_queryparameter' => 'ExampleController@m4_6a_queryparameter',
    '/m4_7a_queryparameter' => 'ExampleController@m4_7a_queryparameter',
    '/m4_7b_kategorie' =>  'ExampleController@m4_7b_kategorie',
    '/m4_7c_gerichte' =>  'ExampleController@m4_7c_gerichte',
);

