<?php namespace App\Validators;

class User {
    public function validate_edit_user(array $post_data): void
    {
        if (empty($post_data['timezone'])) {
            throw new \Exception('TimeZone Must Be Present And Not Empty');
        }
        if (!filter_var($post_data['timezone'], FILTER_VALIDATE_INT)) {
            throw new \Exception('TimeZone Must Be An Integer');
        }
    }
}