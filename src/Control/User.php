<?php namespace App\Control;

class User extends Main {
    public function show_users(): array
    {
        return ['timezone' => $this->timezone];
    }

    public function new_user(): array
    {
        return ['Functionality Not Implemented'];
    }

    public function edit_user(): array
    {
        return ['Functionality Not Implemented'];
    }

    public function delete_user(): array
    {
        return ['Functionality Not Implemented'];
    }
}