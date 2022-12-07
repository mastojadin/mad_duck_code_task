<?php namespace App\Control;

use App\Validators\ToDo as ToDoValidator;

class ToDo extends Main {
    public function show_todo(array $post_data): array
    {
        $validator = new ToDoValidator;
        $validator->validate_show_todo($post_data);
        $validator->validate_get_data($this->get_data);

        $all_todos = $this->my_r->get_all_todos($this->user_id, $post_data, $this->get_data);
        return ['all_todos' => $all_todos];

        return ['show_todo'];
    }

    public function new_todo(array $post_data): array
    {
        $validator = new ToDoValidator;
        $validator->validate_new_todo($post_data);

        $new_todo_id = $this->my_r->new_todo($this->user_id, $post_data);

        return ['new todo saved' => ["id" => $new_todo_id]];
    }

    public function edit_todo(array $post_data): array
    {
        $validator = new ToDoValidator;
        $validator->validate_edit_todo($post_data);

        $this->my_r->edit_todo($post_data);

        return ['task edited' => ['id' => $post_data['id']]];
    }

    public function delete_todo(array $post_data): array
    {
        $validator = new ToDoValidator;
        $validator->validate_delete_todo($post_data);

        $this->my_r->delete_todo($post_data);

        return ['task deleted' => ['id' => $post_data['id']]];
    }
}