<?php namespace App\RepositoryInterfaces;

interface User extends Main {
    public function get_user_info(int $user_id);
    public function edit_user(int $user_id, array $post_data);
}