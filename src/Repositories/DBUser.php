<?php namespace App\Repositories;

use App\RepositoryInterfaces\User as UserInterface;
use App\RepositoryInterfaces\Auth as AuthInterface;
use App\Core\DB;
use App\Helpers\FirstHelper;

class DBUser extends Main implements UserInterface, AuthInterface {
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

    public function get_user_info(int $user_id): mixed
    {
        $query = "
            SELECT
                id,
                username,
                email,
                timezone
            FROM users
            WHERE 1=1
                AND id = :id
        ";
        $params = [
            ":id" => $user_id
        ];

        $res = DB::do_my_query($query, $params);
        return $res;
    }

    public function edit_user(int $user_id, array $post_data): mixed
    {
        $query = "UPDATE users SET timezone = :timezone WHERE id = :id";
        $params = [
            ":timezone" => $post_data['timezone'],
            ":id" => $user_id,
        ];

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry While Editing User Timezone. Please Try Again.');
        }

        return $res;
    }
}