<?php

namespace App\Http\Controllers;
/**
     * @OA\Info(
     *   title="Example API",
     *   version="1.0.0",
     * ),   
     *   @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     in="header",
     *     name="na",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *   )
     * )
     */
abstract class Controller
{
    //
}
