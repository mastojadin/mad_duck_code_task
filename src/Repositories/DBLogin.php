<?php namespace App\Repositories;

use App\RepositoryInterfaces\Login as LoginRepositoryInterface;
use App\Core\DB;
use App\Helpers\FirstHelper;

class DBLogin extends Main implements LoginRepositoryInterface {
    public function check_if_user_exists(array $data): mixed
    {
        $username = $data['username'];
        $password = 'prefix_' . $data['password'] . '_sufix';

        $query = "
            SELECT
                password
            FROM users
            WHERE 1=1
                AND username = :username
            LIMIT 1
        ";
        $params = [
            ":username" => $username,
        ];

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry With The Database. Please Try Again In Five ( 5 ) Minutes');
        }

        return password_verify($password, $res['password']);
    }

    public function get_bearer_token(array $data): mixed
    {
        $username = $data['username'];

        $query = "
            SELECT
                id
            FROM users
            WHERE 1=1
                AND username = :username
            LIMIT 1
        ";
        $params = [
            ":username" => $username,
        ];

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry With The Database. Please Try Again In Five ( 5 ) Minutes');
        }
        
        $id = $res['id'];
        $token = FirstHelper::general('get_random_string');

        $query = "
            UPDATE users
            SET token = :token
            WHERE 1=1
                AND id = :id
        ";
        $params = [
            ":token" => $token,
            ":id" => $id,
        ];

        $res = DB::do_my_query($query, $params);
        if (!$res) {
            throw new \Exception('Something Went Awry With The Database. Please Try Again In Five ( 5 ) Minutes');
        }
        
        return $token;
    }
}