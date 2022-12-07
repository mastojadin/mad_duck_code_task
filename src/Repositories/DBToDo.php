<?php namespace App\Repositories;

use App\RepositoryInterfaces\ToDo as ToDoInterface;
use App\RepositoryInterfaces\Auth as AuthInterface;
use App\Core\DB;
use App\Helpers\FirstHelper;
use Carbon\Carbon;

class DBToDo extends Main implements ToDoInterface, AuthInterface {
    public function check_token(): mixed
    {
       $token = FirstHelper::headers('get_bearer');
       
       $query = "
            SELECT
                id,
                timezone
            FROM users
            WHERE 1=1
                AND token = :token
            LIMIT 1
        ";
        $params = [
            ":token" => $token,
        ];

        $res = DB::do_my_query($query, $params);
        return $res;
    }

    public function get_all_todos(int $user_id, array $post_data, array $get_data): mixed
    {
        $this->check_list_owner($user_id, $post_data['id']);

        $done = $get_data['done'] ?? false;
        $deadline = $get_data['deadline'] ?? false;

        $done_condition = '';
        if ($done) {
            $done_condition = 'AND done = :done';
        }
        $deadline_condition = '';
        if ($deadline) {
            $date = new Carbon($deadline);
            $deadline_start = $date->toDateString() . ' 00:00:00';
            $deadline_end = $date->toDateString() . ' 23:59:59';

            $deadline_condition = 'AND deadline BETWEEN :deadline_start AND :deadline_end';
        }

        $query = "
            SELECT
                id,
                list_id,
                title,
                description,
                deadline,
                done
            FROM tasks
            WHERE 1=1
                AND list_id = :list_id
                $done_condition
                $deadline_condition
        ";
        $params = [":list_id" => $post_data['id']];
        if ($done) {
            $params[":done"] = $done;
        }
        if ($deadline) {
            $params[":deadline_start"] = $deadline_start;
            $params[":deadline_end"] = $deadline_end;
        }

        $res = DB::do_my_query($query, $params);
        return $res;
    }

    public function new_todo(int $user_id, array $post_data): mixed
    {
        $list_id = $post_data['list_id'];

        $this->check_list_owner($user_id, $post_data['list_id']);

        $title = $post_data['title'];
        $description = $post_data['description'];
        $tmp_deadline = new Carbon($post_data['deadline']);
        $deadline = $tmp_deadline->toDateTimeString();

        $query = "
            INSERT INTO tasks (list_id, title, description, deadline) VALUES (:list_id, :title, :description, :deadline)
        ";
        $params = [
            ":list_id" => $list_id,
            ":title" => $title,
            ":description" => $description,
            ":deadline" => $deadline,
        ];

        $res = DB::do_my_query($query, $params);
        return $res;
    }

    public function edit_todo(array $post_data): mixed
    {
        $id = $post_data['id'];
        $title = $post_data['title'] ?? false;
        $description = $post_data['description'] ?? false;
        $tmp_deadline = !empty($post_data['deadline']) ?new Carbon($post_data['deadline']) : false;
        $deadline = $tmp_deadline ? $tmp_deadline->toDateTimeString() : false;
        $done = $post_data['done'] ?? false;

        $title_set = $title ? "title = :title, " : '';
        $description_set = $description ? "description = :description, " : '';
        $deadline_set = $deadline ? "deadline = :deadline, " : '';
        $done_set = $done ? "done = :done" : '';
        $full_set = $title_set . $description_set . $deadline_set . $done_set;
        $full_set = trim($full_set, ', ');

        $query = "
            UPDATE tasks SET $full_set WHERE id = :id
        ";

        if ($title) {
            $params[":title"] = $title;
        }
        if ($description) {
            $params[":description"] = $description;
        }
        if ($deadline) {
            $params[":deadline"] = $deadline;
        }
        if ($done) {
            $params[":done"] = $done;
        }
        $params[":id"] = $id;

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry While Editing List. Please Try Again.');
        }
        return $res;
    }

    public function delete_todo(array $post_data): mixed
    {
        $query = "
            DELETE FROM tasks WHERE id = :id
        ";
        $params = [":id" => $post_data['id']];

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry While Deleting Task. Please Try Again.');
        }
        return $res;
    }

    private function check_list_owner(int $user_id, int $list_id): void
    {
        $query = "
            SELECT
                id,
                user_id
            FROM lists
            WHERE 1=1
                AND id = :id
                AND user_id = :user_id
            LIMIT 1
        ";
        $params = [
            ":id" => $list_id,
            ":user_id" => $user_id,
        ];
        
        $res = DB::do_my_query($query, $params);
        if ($res['id'] != $list_id || $res['user_id'] != $user_id) {
            throw new \Exception('You Dont Own This List');
        }
    }
}