<?php namespace App\Validators;

class Login {
    public function validate_login(array $data)
    {
        if (empty($data['username'])) {
            throw new \Exception('Username Filed Must Be Present And Not Empty');
        }

        if (empty($data['password'])) {
            throw new \Exception('Password Filed Must Be Present And Not Empty');
        }
    }
}