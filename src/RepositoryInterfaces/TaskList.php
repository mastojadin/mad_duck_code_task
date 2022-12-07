<?php namespace App\RepositoryInterfaces;

interface TaskList extends Main {
    public function get_all_lists(int $user_id, array $get_data);
    public function save_new_list(int $user_id, array $post_data);
    public function edit_list(array $post_data);
    public function delete_list(array $post_data);
}