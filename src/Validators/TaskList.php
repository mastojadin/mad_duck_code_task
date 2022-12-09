<?php namespace App\Validators;

use Carbon\Carbon;

class TaskList {
    public function validate_get_data(array $data): void
    {
        if (isset($data['title']) && $data['title'] == "") {
            throw new \Exception('Title Must Not Be Empty');
        }

        if (isset($data['page']) && !filter_var($data['page'], FILTER_VALIDATE_INT)) {
            throw new \Exception('Page Must Be An Integer');
        }

        if (isset($data['date'])) {
            try {
                new Carbon($data['date']);
            } catch (\Exception $e) {
                throw new \Exception('Date Format Not Regonized');
            }
        }
    }

    public function validate_new_list(array $data): void
    {
        if (empty($data['title'])) {
            throw new \Exception('Title Filed Must Be Present And Not Empty');
        }

        if (strlen($data['title']) > 256) {
            throw new \Exception('Title Filed Must Be Less In Lenght Than 256 Characters');
        }

        if (empty($data['description'])) {
            throw new \Exception('Description Filed Must Be Present And Not Empty');
        }

        if (strlen($data['description']) > 1024) {
            throw new \Exception('Description Filed Must Be Less In Lenght Than 1024 Characters');
        }

        if (isset($data['date'])) {
            try {
                new Carbon($data['date']);
            } catch (\Exception $e) {
                throw new \Exception('Date Format Not Regonized');
            }
        }
    }

    public function validate_edit_list($data): void
    {
        if (empty($data['id'])) {
            throw new \Exception('List ID Must Be Present');
        }

        if (!filter_var($data['id'], FILTER_VALIDATE_INT)) {
            throw new \Exception('List ID Must Be An Integer');
        }

        if (empty($data['title']) && empty($data['descritpion']) && empty($data['date'])) {
            throw new \Exception('Choose Something To Edit. Title, Description Or Date');
        }

        if (!empty($data['title']) && strlen($data['title']) > 256) {
            throw new \Exception('Title Filed Must Be Less In Lenght Than 256 Characters');
        }

        if (!empty($data['description']) && strlen($data['description']) > 1024) {
            throw new \Exception('Description Filed Must Be Less In Lenght Than 1024 Characters');
        }

        if (isset($data['date'])) {
            try {
                new Carbon($data['date']);
            } catch (\Exception $e) {
                throw new \Exception('Date Format Not Regonized');
            }   
        }
    }

    public function validate_delete_list($data): void
    {
        if (empty($data['id'])) {
            throw new \Exception('List ID Must Be Present');
        }

        if (!filter_var($data['id'], FILTER_VALIDATE_INT)) {
            throw new \Exception('List ID Must Be An Integer');
        }
    }
}