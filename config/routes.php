<?php

return [
    "get" => [
        "/" => ["Login", "index"],
        "/users" => ["User", "show_users"],
        "/lists" => ["TaskList", "show_lists"],
        "/todos" => ["ToDo", "show_todo"],
    ],
    "post" => [
        "/" => ["Login", "log_me_in"],
        "/users" => ["User", "new_user"],
        "/lists" => ["TaskList", "new_list"],
        "/todos" => ["ToDo", "new_todo"],
    ],
    "put" => [
        "/users" => ["User", "edit_user"],
        "/lists" => ["TaskList", "edit_list"],
        "/todos" => ["ToDo", "edit_todo"],
    ],
    "delete" => [
        "/users" => ["User", "delete_user"],
        "/lists" => ["TaskList", "delete_list"],
        "/todos" => ["ToDo", "delete_todo"],
    ],
];