<?php

class PrincipalController
{
    public function __construct()
    {
       
    }
    
    public function processRequest(string $method, array $parts): array
    {
        global $server_host_solar, $server_database_solar, $server_user_solar, $server_password_solar;
        $database = new Database($server_host_solar, $server_database_solar, $server_user_solar, $server_password_solar);
        $gateway = new SolarGateway($database);
        $controllerSolar = new SolarController($gateway);
        $id = $parts[2] ?? null;
        $response=$controllerSolar->processRequest($_SERVER["REQUEST_METHOD"], $id);
        return $response;
    }
    
    
}
