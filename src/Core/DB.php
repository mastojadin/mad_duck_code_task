<?php namespace App\Core;

use App\Core\Config;

class DB {
    private string $host;
    private string $charset;
    private string $port;
    private string $name;
    private string $username;
    private string $password;

    private \PDO $pdo;

    private function __construct()
    {
        $vars = Config::get_me('vars');
        $this->host = $vars['db_host'];
        $this->charset = $vars['db_charset'];
        $this->port = $vars['db_port'];
        $this->name = $vars['db_name'];
        $this->username = $vars['db_username'];
        $this->password = $vars['db_password'];

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $dsn = "mysql:host=$this->host;dbname=$this->name;charset=$this->charset;port=$this->port";
        try {
            $this->pdo = new \PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * executing the query with paramethers
     * 
     * @return mixed
     */
    public static function do_my_query(string $query, array $params = []): mixed
    {
        $db = new DB;
        return $db->real_do_my_query($query, $params);
    }

    /**
     * executing the query with paramethers
     * but for real
     * 
     * @return mixed
     */
    private function real_do_my_query(string $query, array $params): mixed
    {
        $stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $to_return = false;
        if (strpos(trim($query), 'SELECT') === 0) {
            $from_db = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            $to_return = [];
            if (!empty($from_db)) {
                foreach ($from_db as $row) {
                    array_push($to_return, $row);
                }
                if(substr(trim($query), -7) === 'LIMIT 1') {
                    $to_return = $to_return[0];
                }
            }
        } else if (strpos(trim($query), 'INSERT') === 0) {
            $to_return = $this->pdo->lastInsertId();
        } else if (strpos(trim($query), 'UPDATE') === 0) {
            $to_return = true;
        } else if (strpos(trim($query), 'DELETE') === 0) {
            $to_return = true;
        }

        return $to_return;
    }
}