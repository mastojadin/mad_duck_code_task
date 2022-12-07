<?php namespace App\Control;

use App\Validators\Login as LoginValidator;
use App\Repositories\DBLogin as LoginRepository;

class Login {
    public function index(): array
    {
        return [
            'Hello and Welcome Dear User, Do Login And Start Exploring',
        ];
    }

    public function log_me_in(array $post_data)
    {
        $validator = new LoginValidator;
        $validator->validate_login($post_data);
        
        $repository = new LoginRepository;
        
        $check = $repository->check_if_user_exists($post_data);
        if (!$check) {
            throw new \Exception('Wrong Credentials');
        }

        $token = $repository->get_bearer_token($post_data);
        return ['bearer_token' => $token];
    }
}