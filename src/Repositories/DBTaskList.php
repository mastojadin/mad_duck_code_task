<?php namespace App\Repositories;

use App\RepositoryInterfaces\TaskList as TaskListInterface;
use App\RepositoryInterfaces\Auth as AuthInterface;
use App\Core\DB;
use App\Helpers\FirstHelper;
use Carbon\Carbon;

class DBTaskList extends Main implements TaskListInterface, AuthInterface {
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

    public function get_all_lists(int $user_id, array $get_data): array
    {
        $title_condition = '';
        $title = $get_data['title'] ?? false;
        if ($title) {
            $title_condition = "AND title = :title";
        }

        $date_start = Carbon::today('GMT')->toDateTimeString();
        $date_end = Carbon::tomorrow('GMT')->toDateTimeString();
        $tmp_date = $get_data['date'] ?? false;
        if ($tmp_date) {
            $date = new Carbon($tmp_date);
            $date_start = $date->toDateString() . ' 00:00:00';
            $date_end = $date->toDateString() . ' 23:59:59';
        }

        $page = $get_data['page'] ?? 1;
        $res_from = ($page - 1) * $this->per_page;
        $res_to = $page * $this->per_page;

        $query = "
            SELECT
                id,
                user_id,
                title,
                description,
                DATE(created_at) as date
            FROM lists
            WHERE 1=1
                AND user_id = :user_id
                AND created_at BETWEEN :date_start AND :date_end
                $title_condition
        ";
        $params = [
            ":user_id" => $user_id,
            ":date_start" => $date_start,
            ":date_end" => $date_end,
        ];
        if ($title) {
            $params[":title"] = $title;
        }

        $res = DB::do_my_query($query, $params);
        return array_slice($res, $res_from, $res_to);
    }

    public function save_new_list(int $user_id, array $post_data): mixed
    {
        $title = $post_data['title'];
        $description = $post_data['description'];
        $tmp_date = !empty($post_data['date']) ? new Carbon($post_data['date']) : false;
        $date = !$tmp_date ? Carbon::now("GMT")->toDateTimeString() : $tmp_date->toDateTimeString();

        $query = "
            INSERT INTO lists (user_id, title, description, created_at) VALUES (:user_id, :title, :description, :date)
        ";
        $params = [
            ":user_id" => $user_id,
            ":title" => $title,
            ":description" => $description,
            ":date" => $date,
        ];
        
        $res = DB::do_my_query($query, $params);
        return $res;
    }

    public function edit_list(array $post_data): mixed
    {
        $query = "SELECT id FROM lists WHER id = :id";
        $params = [":id" => $post_data['id']];
        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('No Such List Detected');
        }

        $title = $post_data['title'] ?? false;
        $description = $post_data['description'] ?? false;
        $tmp_date = !empty($post_data['date']) ? new Carbon($post_data['date']) : false;
        $date = !$tmp_date ? false : $tmp_date->toDateTimeString();

        $title_set = $title ? "title = :title, " : '';
        $description_set = $description ? "description = :description, " : '';
        $date_set = $date ? "created_at = :date" : '';
        $full_set = $title_set . $description_set . $date_set;
        $full_set = trim($full_set, ', ');

        $query = "
            UPDATE lists SET $full_set WHERE id = :id
        ";

        if ($title) {
            $params[":title"] = $title;
        }
        if ($description) {
            $params[":description"] = $description;
        }
        if ($date) {
            $params[":date"] = $date;
        }
        $params[":id"] = $post_data['id'];

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry While Editing List. Please Try Again.');
        }
        return $res;
    }

    public function delete_list(array $post_data): mixed
    {
        $query = "
            DELETE FROM lists WHERE id = :id
        ";
        $params = [":id" => $post_data['id']];

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry While Deleting List. Please Try Again.');
        }
        return $res;
    }
}