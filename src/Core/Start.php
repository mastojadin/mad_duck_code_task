<?php namespace App\Core;

use App\Helpers\FirstHelper;

class Start {
    private string $class_name;
    private string $method_name;
    private array $body;

    /**
     * setting stuff
     * @param string $body
     * 
     * @return null
     */
    public function in(string $body): void
    {
        $this->body = json_decode($body, 1);
    }

    /**
     * checking if the Content-Type is application/json
     * 
     * @return null
     */
    public function check_headers(): void
    {
        FirstHelper::headers('isset_content_type');
        FirstHelper::headers('is_content_type_application_json');
    }

    /**
     * checking if the request is ok
     * method, route, ...
     * 
     * @return null
     */
    public function check_request(): void
    {
        FirstHelper::request('check_method');
        FirstHelper::request('check_route');
    }

    /**
     * checking if the route needs auth
     * and if it is auth
     * 
     * @return null
     */
    public function check_auth(): void
    {
        FirstHelper::auth('is_auth');
    }

    /**
     * setting class name and method name
     * 
     * @return null
     */
    public function set_class_method_names(): void
    {
        $class_method_names_array = FirstHelper::request('get_class_method_names');
        $this->class_name = $class_method_names_array[0];
        $this->method_name = $class_method_names_array[1];
    }

    /**
     * acctualy going into the app
     * and returning the request/response
     * 
     * @return array
     */
    public function out(): array
    {
        $c = 'App\Control\\' . $this->class_name;
        $m = $this->method_name;

        $call = new $c;
        $r = $call->$m($this->body);
        return $r;
    }
}