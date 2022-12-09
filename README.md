-- v-host example => http://MY_DOMANIN.test/
-- v-host option example => http://MY_DOMANIN.test/lists/

-- localhost example => http://locahost/MY_DIRECTORY/PUBLIC/index.php/
-- localhost option example => http://locahost/MY_DIRECTORY/PUBLIC/index.php/lists


-- Care for config folder, especialy vars.php file, please enter real values
-- Create vars.php file from vars_example.php

-- After git clone, do the composer install

-- php migrate_seed.php will create tables and seed them; after defining the database parameters
-- users have to be created via seed; care about the salt ;)

-- check out cron_job folder for cron options
-- When testing mails remmember to check spam folder

-- timezones are integers +/- hours of timezones from gmt