<?php namespace App\Validators;

use Carbon\Carbon;

class ToDo {
    public function validate_show_todo($post_data): void
    {
        if (empty($post_data['id'])) {
            throw new \Exception('List ID Must Be Present');
        }

        if (!filter_var($post_data['id'], FILTER_VALIDATE_INT)) {
            throw new \Exception('List ID Must Be An Integer');
        }
    }

    public function validate_get_data($get_data): void
    {
        if (isset($get_data['done'])) {
            if ($get_data['done'] != '0' && $get_data['done'] != '1') {
                throw new \Exception('Filed Done Must Be Zero ( 0 ) Or One ( 1 )');
            }
        }

        if (isset($get_data['deadline'])) {
            try {
                new Carbon($get_data['deadline']);
            } catch (\Exception $e) {
                throw new \Exception('Deadline Date Format Not Regonized');
            }
        }
    }

    public function validate_new_todo($post_data): void
    {
        if (empty($post_data['list_id'])) {
            throw new \Exception('List ID Must Be Present');
        }
        if (!filter_var($post_data['list_id'], FILTER_VALIDATE_INT)) {
            throw new \Exception('List ID Must Be An Integer');
        }

        if (empty($post_data['title'])) {
            throw new \Exception('Title Filed Must Be Present And Not Empty');
        }
        if (strlen($post_data['title']) > 256) {
            throw new \Exception('Title Filed Must Be Less In Lenght Than 256 Characters');
        }

        if (empty($post_data['description'])) {
            throw new \Exception('Description Filed Must Be Present And Not Empty');
        }
        if (strlen($post_data['description']) > 1024) {
            throw new \Exception('Description Filed Must Be Less In Lenght Than 1024 Characters');
        }

        if (empty($post_data['deadline'])) {
            throw new \Exception('Deadline Filed Must Be Present And Not Empty');
        }
        try {
            new Carbon($post_data['deadline']);
        } catch (\Exception $e) {
            throw new \Exception('Deadline Date Format Not Regonized');
        }
    }

    public function validate_edit_todo($post_data): void
    {
        if (empty($post_data['id'])) {
            throw new \Exception('Task ID Must Be Present');
        }
        if (!filter_var($post_data['id'], FILTER_VALIDATE_INT)) {
            throw new \Exception('Task ID Must Be An Integer');
        }

        if (empty($post_data['title']) && empty($post_data['description']) && empty($post_data['deadline']) && empty($post_data['done'])) {
            throw new \Exception('Choose Something To Edit. Title, Description, Deadline Or Done');
        }

        if (!empty($post_data['title']) && strlen($post_data['title']) > 256) {
            throw new \Exception('Title Filed Must Be Less In Lenght Than 256 Characters');
        }

        if (!empty($post_data['description']) && strlen($post_data['description']) > 1024) {
            throw new \Exception('Description Filed Must Be Less In Lenght Than 1024 Characters');
        }

        if (!empty($post_data['deadline'])) {
            try {
                new Carbon($post_data['deadline']);
            } catch (\Exception $e) {
                throw new \Exception('Deadline Date Format Not Regonized');
            }
        }

        if (!empty($post_data['done'])) {
            if ($post_data['done'] != '0' && $post_data['done'] != '1') {
                throw new \Exception('Filed Done Must Be Zero ( 0 ) Or One ( 1 )');
            }
        }
    }

    public function validate_delete_todo($post_data): void
    {
        if (empty($post_data['id'])) {
            throw new \Exception('Task ID Must Be Present');
        }
        if (!filter_var($post_data['id'], FILTER_VALIDATE_INT)) {
            throw new \Exception('Task ID Must Be An Integer');
        }
    }
}