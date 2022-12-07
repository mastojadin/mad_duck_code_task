<?php namespace App\Control;

use App\Validators\TaskList as TaskListValidator;

class TaskList extends Main {
    public function show_lists(): array
    {
        $validator = new TaskListValidator;
        $validator->validate_get_data($this->get_data);

        $all_lists = $this->my_r->get_all_lists($this->user_id, $this->get_data);
        return ['all_lists' => $all_lists];
    }

    public function new_list(array $post_data): array
    {
        $validator = new TaskListValidator;
        $validator->validate_new_list($post_data);

        $new_list_id = $this->my_r->save_new_list($this->user_id, $post_data);

        return ['new list saved' => ["id" => $new_list_id]];
    }

    public function edit_list(array $post_data): array
    {
        $validator = new TaskListValidator;
        $validator->validate_edit_list($post_data);

        $this->my_r->edit_list($post_data);

        return ['list edited' => ['id' => $post_data['id']]];
    }

    public function delete_list(array $post_data): array
    {
        $validator = new TaskListValidator;
        $validator->validate_delete_list($post_data);

        $this->my_r->delete_list($post_data);

        return ['list deleted' => ['id' => $post_data['id']]];
    }
}