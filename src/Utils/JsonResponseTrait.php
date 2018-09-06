<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;;

trait JsonResponseTrait
{
	public  function sendJsonResponse($data, $statusCode)
    {
    	return new Response($data, $statusCode,
        	[
        		'Content-Type' => 'application/json'
        	]
        );
    }
}