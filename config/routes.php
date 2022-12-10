<?php

return [
    "get" => [
        "/" => ["Login", "index"], // no params
        "/users" => ["User", "show_users"], // no params ( shows only thyself )
        "/lists" => ["TaskList", "show_lists"], // get => title, page, date ( all optional )
        "/todos" => ["ToDo", "show_todo"], // get => deadline, done ( all optional )
    ],
    "post" => [
        "/" => ["Login", "log_me_in"], // post(body) => email, password ( both mandatory )
        "/users" => ["User", "new_user"], // does nothing ( add users through migrate_seed.php ( care for salt in password ) )
        "/lists" => ["TaskList", "new_list"], // post(body) => title, description ( both mandatory )
        "/todos" => ["ToDo", "new_todo"], // post(body) => list_id, title, description, deadline ( all mandatory )
    ],
    "put" => [
        "/users" => ["User", "edit_user"], // post(body) => timezone ( mandatory )
        "/lists" => ["TaskList", "edit_list"], // post(body) => id, title ( both mandatory )
        "/todos" => ["ToDo", "edit_todo"], // post(body) => id, title ( both mandatory )
    ],
    "delete" => [
        "/users" => ["User", "delete_user"], // does nothing ( delete user(s) manualy )
        "/lists" => ["TaskList", "delete_list"], // id ( mandatory )
        "/todos" => ["ToDo", "delete_todo"], // id ( mandatory )
    ],
];