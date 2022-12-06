<?php

$config = include_once 'config/vars.php';
$host = $config['db_host'];
$db = $config['db_name'];
$charset = $config['db_charset'];
$port = $config['db_port'];
$user = $config['db_username'];
$pass = $config['db_password'];

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
$pdo = new \PDO($dsn, $user, $pass, $options);

echo "\n";
echo "Migrating tables";
echo "\n";
echo "...";
echo "\n";
$create_users_table_query = "
    CREATE TABLE IF NOT EXISTS `users` (
        `id` INT (11) AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR (128) NOT NULL,
        `password` VARCHAR (128) NOT NULL,
        `timezone` VARCHAR (16) DEFAULT (0),
        `token` VARCHAR (128)
    ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
";
$pdo->query($create_users_table_query);
echo "Users table created\n";

$create_lists_table_query = "
    CREATE TABLE IF NOT EXISTS `lists` (
        `id` INT (11) AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT (11) NOT NULL,
        `title` VARCHAR (256) NOT NULL,
        `description` VARCHAR (1024) NOT NULL,
        `created_at` VARCHAR (32) NOT NULL,
        FOREIGN KEY (`user_id`)
            REFERENCES `users`(`id`)
            ON DELETE CASCADE
    ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
";
$pdo->query($create_lists_table_query);
echo "Lists table created\n";

$create_tasks_table_query = "
    CREATE TABLE IF NOT EXISTS `tasks` (
        `id` INT (11) AUTO_INCREMENT PRIMARY KEY,
        `list_id` INT (11) NOT NULL,
        `title` VARCHAR (256) NOT NULL,
        `description` VARCHAR (1024) NOT NULL,
        `deadline` VARCHAR (32) NOT NULL,
        `done` INT (1) DEFAULT (0),
        FOREIGN KEY (`list_id`)
            REFERENCES `lists`(`id`)
            ON DELETE CASCADE
    ) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
";
$pdo->query($create_tasks_table_query);
echo "Tasks table created\n";


echo "\n";
echo "Seeding tables";
echo "\n";
echo "...";
echo "\n";
$pdo->query("INSERT INTO `users` (`username`, `password`, `timezone`) VALUES ('test user', '$2y$10\$HcIqwVO6BcN3ecgQbWt6nu7YxXAVUtoGi4k0O8Br6B8ycUwzttIHW', '0')");
$pdo->query("INSERT INTO `users` (`username`, `password`, `timezone`) VALUES ('real user', '$2y$10$0XJYgIugSW.8MDPYK7OSeedW5wf137UVdcHSF3zcekvpZT2SNWZW.', '0')");

echo "Users table seeded";
echo "\n";

$pdo->query("INSERT INTO `lists` (`user_id`, `title`, `description`, `created_at`) VALUES (1, 'My First Test List', 'This Is the Description', '2022-12-01 12:42:13')");
$pdo->query("INSERT INTO `lists` (`user_id`, `title`, `description`, `created_at`) VALUES (1, 'My Second Test List', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut ipsum consequat, condimentum tortor vitae, semper sem. Phasellus semper ex sed maximus vulputate. Mauris tincidunt nisi a eros scelerisque volutpat. Morbi a magna eu magna aliquet tempor. Maecenas ut orci aliquam, malesuada leo in, ullamcorper erat. Pellentesque justo eros, venenatis at pharetra quis, egestas sit amet urna. Nam ac vehicula massa, in rhoncus nibh. Morbi auctor laoreet odio, vitae rutrum diam viverra porttitor. Proin at eros pulvinar enim ultricies aliquet.

Sed vel elementum nunc, nec ultricies nisi. Morbi non massa elementum, vehicula eros et, dignissim diam. Donec sit amet massa nec nulla rutrum consequat. Aenean condimentum euismod ex. Aliquam eu neque eget sem laoreet egestas id cursus justo. Morbi tempus ligula non fringilla aliquet. Vestibulum et nibh tincidunt, dignissim nibh ut, interdum nisi. Fusce vel aliquam ante.', '2022-12-02 12:42:13')");

echo "Lists table seeded";
echo "\n";

$pdo->query("INSERT INTO `tasks` (`list_id`, `title`, `description`, `deadline`) VALUES (1, 'My First Test Task', 'This Is the Description', '2022-12-02 15:59:59')");
$pdo->query("INSERT INTO `tasks` (`list_id`, `title`, `description`, `deadline`, `done`) VALUES (1, 'My Second Test Task', 'This Is the Description', '2022-12-02 15:59:59', 1)");
$pdo->query("INSERT INTO `tasks` (`list_id`, `title`, `description`, `deadline`) VALUES (1, 'My Third Test Task', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut ipsum consequat, condimentum tortor vitae, semper sem. Phasellus semper ex sed maximus vulputate. Mauris tincidunt nisi a eros scelerisque volutpat. Morbi a magna eu magna aliquet tempor. Maecenas ut orci aliquam, malesuada leo in, ullamcorper erat. Pellentesque justo eros, venenatis at pharetra quis, egestas sit amet urna. Nam ac vehicula massa, in rhoncus nibh. Morbi auctor laoreet odio, vitae rutrum diam viverra porttitor. Proin at eros pulvinar enim ultricies aliquet.

Sed vel elementum nunc, nec ultricies nisi. Morbi non massa elementum, vehicula eros et, dignissim diam. Donec sit amet massa nec nulla rutrum consequat. Aenean condimentum euismod ex. Aliquam eu neque eget sem laoreet egestas id cursus justo. Morbi tempus ligula non fringilla aliquet. Vestibulum et nibh tincidunt, dignissim nibh ut, interdum nisi. Fusce vel aliquam ante.', '2022-12-02 15:59:59')");
$pdo->query("INSERT INTO `tasks` (`list_id`, `title`, `description`, `deadline`) VALUES (1, 'My Fourth Test Task', 'This Is the Description', '2022-12-02 15:59:59')");
$pdo->query("INSERT INTO `tasks` (`list_id`, `title`, `description`, `deadline`) VALUES (2, 'My Second First Test Task', 'This Is the Description', '2022-12-01 15:59:59')");
$pdo->query("INSERT INTO `tasks` (`list_id`, `title`, `description`, `deadline`) VALUES (2, 'My Second Second Test Task', 'This Is the Description', '2022-12-03 15:59:59')");

echo "Tasks table seeded";
echo "\n";

exit;