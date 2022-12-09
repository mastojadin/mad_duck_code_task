<?php namespace App\Control;

use App\Validators\User as UserValidator;

class User extends Main {
    public function show_users(): array
    {
        $user_info = $this->my_r->get_user_info($this->user_id);
        return ['user_info' => $user_info];
    }

    public function new_user(): array
    {
        return ['Functionality Not Implemented'];
    }

    public function edit_user(array $post_data): array
    {
        $validator = new UserValidator;
        $validator->validate_edit_user($post_data);

        $this->my_r->edit_user($this->user_id, $post_data);

        return ['User Timezone Changed'];
    }

    public function delete_user(): array
    {
        return ['Functionality Not Implemented'];
    }
}