<?php namespace App\RepositoryInterfaces;

interface ToDo extends Main {
    public function get_all_todos(int $user_id, int $timezone, array $post_data, array $get_data);
    public function new_todo(int $user_id, array $post_data);
    public function edit_todo(array $post_data);
    public function delete_todo(array $post_data);
}