<?php namespace App\Control;

class Main {
    protected object $my_r;
    protected int $user_id;
    protected string $timezone;
    protected array $get_data;
    
    public function __construct()
    {
        $child_class_full_name = get_class($this);
        $child_class_name = str_replace('App\Control\\', '', $child_class_full_name);

        $repository_to_call = $this->call_repository($child_class_name);
        $this->my_r = new $repository_to_call;

        $check_token = $this->my_r->check_token();
        if (!$check_token) {
            throw new \Exception('Token mismatch');
        }

        $this->user_id = $check_token['id'];
        $this->timezone = $check_token['timezone'];

        $this->get_data = $this->extract_get_data();
    }

    protected function call_repository($name)
    {
        $name = ucfirst($name);

        return 'App\Repositories\DB' . $name;
    }

    private function extract_get_data()
    {
        $to_return = [];
        foreach ($_GET as $key => $value) {
            $to_return[$key] = $value;
        }

        return $to_return;
    }
}