<?php namespace App\Helpers;

class Headers {
    private array $headers;

    public function __construct()
    {
        $this->headers = getallheaders();
    }

    public function isset_content_type(): void
    {
        if (!isset($this->headers['Content-Type'])) {
            throw new \Exception('Content-Type Header Not Set');
        }
    }

    public function is_content_type_application_json(): void
    {
        if ($this->headers['Content-Type'] != 'application/json') {
            throw new \Exception('Content-Type Header Is Not application/json');
        }
    }

    public function isset_bearer(): void
    {
        if (!isset($this->headers['Bearer'])) {
            throw new \Exception('Bearer Header Not Set');
        }
    }

    public function get_bearer(): string
    {
        return $this->headers['Bearer'] ?? '';
    }
}