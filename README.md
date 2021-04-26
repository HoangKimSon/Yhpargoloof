
# Run project

To run project:

 1. Change database information in constant.php
 2. Run all sql files in folder sql by order


# Script task

To remove all old links, the deletion script in file cronJob.php can be run as followed command:

      php <path_to_file>/cronJob.php

To remove all old links by sql, the followed statement can be used:

    SELECT * FROM `link` WHERE `id` NOT IN (SELECT DISTINCT `link_id` FROM `user_link`) AND DATEDIFF(CURRENT_DATE, updated_at) > 30

# Solution for run script automatically
 

Because PHP natively doesn't support automating tasks, therefore I come up with two solutions:
- Solution 1: Using external libraries. In this method, it has all advantages and disadvantages of the used library, therefore, picking a library from the beginning must be carefully.
- Solution 2: Using OS service to run the script. On windows can use Task Scheduler, and on Linus can use Cron. This method requires some permissions on a server and depend on service on the OS.