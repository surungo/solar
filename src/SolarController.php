<?php

class SolarController
{
    public function __construct(private SolarGateway $gateway)
    {
    }
    
    public function processRequest(string $method, ?string $id): array
    {
        if ($id) {
            return $this->processResourceRequest($method, $id);
        } else {
            return $this->processCollectionRequest($method);
        }
    }
    
    private function processResourceRequest(string $method, string $id): array
    {
        $solar = $this->gateway->findById($id);
        
        if ( ! $solar) {
            http_response_code(404);
            return ["message" => "solar not found"];
        }
        
        switch ($method) {
            case "GET":
                return $solar;
                
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    return ["errors" => $errors];
                }
                
                $rows = $this->gateway->update($solar, $data);
                
                return [
                    "message" => "solar $id updated",
                    "rows" => $rows
                ];
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                
                return [
                    "message" => "solar $id deleted",
                    "rows" => $rows
                ];
                
            default:
                http_response_code(405);
                $message = "Allow: GET, PATCH, DELETE";
                header($message);
                return [
                    "message" => $message
                ];
        }
    }
    
    private function processCollectionRequest(string $method): array
    {
        switch ($method) {
            case "GET":
                return $this->gateway->findAll();
                
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    return ["errors" => $errors];
                }
                
                $id = $this->gateway->insert($data);
                
                http_response_code(201);
                return [
                    "message" => "solar created",
                    "id" => $id
                ];
                
            case "PUT":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $id = $data["id"];
                $solar = $this->gateway->findById($data["id"]);
        
                if ( ! $solar) {
                    http_response_code(404);
                    return ["message" => "solar not found"];
                }
                
                $errors = $this->getValidationErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    return ["errors" => $errors];
                }
                
                $rows = $this->gateway->update($solar, $data);
                
                return [
                    "message" => "solar $id updated",
                    "rows" => $rows
                ];
                
            default:
                http_response_code(405);
                $message = "Allow: GET, POST, PUT";
                header($message);
                return [
                    "message" => $message
                ];
        }
    }
    
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        /*
        if ($is_new && empty($data["drive"])) {
            $errors[] = "drive is required";
        }
        
        if (array_key_exists("point", $data)) {
            if (filter_var($data["point"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "point must be an integer";
            }
        }
        */
        return $errors;
    }
}
