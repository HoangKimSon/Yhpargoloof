# Script task
To remove all old links, the deletion script in file cronJob.php can be run as followed command:

    php <path_to_file>/cronJob.php

To remove all old links by sql, the followed statement can be used:

    SELECT * FROM `link` WHERE `id` NOT IN (SELECT DISTINCT `link_id` FROM `user_link`) AND DATEDIFF(CURRENT_DATE, updated_at) > 30