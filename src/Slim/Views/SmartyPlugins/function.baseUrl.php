<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.baseUrl.php
 * Type:     function
 * Name:     baseUrl
 * Purpose:  outputs url for a function with the defined name method
 * version   0.1.2
 * package   SlimViews
 * -------------------------------------------------------------
 */
function smarty_function_baseUrl($params, $template)
{
    $withUri = isset($params['withUri']) ? $params['withUri'] : false;

    $app  = \App::instance();
    $req = $app->request;

    /* @var $req Slim\Http\Request */

    $uri = $req->getPath();

   if ($withUri) {
       $uri = $req->getUrl() . $uri;
   }

    return $uri;
}
