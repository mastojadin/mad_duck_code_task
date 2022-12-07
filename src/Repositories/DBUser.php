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
}