<?php namespace App\RepositoryInterfaces;

interface Login {
    public function check_if_user_exists(array $data);
    public function get_bearer_token(array $data);
}