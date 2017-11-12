Configuration & Deployment

PHP:
    -Requires PHP7 due to functions only available in version 7 (random_bytes())

Email:
    -Configuration for SENDMAIL.INI
        smtp_server=smtp.gmail.com
        smtp_port=465
        smtp_ssl=ssl
        auth_username=<EMAIL>
        auth_password=<PASSWORD>
        force_sender=<EMAIL>

    -Configuration for PHP.INI
        SMTP = smtp.gmail.com
        smtp_port = 25

PHPMyAdmin:
    -SQL Database file included.
    -Database name: engage
    -db.inc.php contains connection to DB

PHP & JS Constants
    -Max quantity per product in
        -assets/js/game_add.js
        -includes/shopping_cart_update.php

    -Max number of games in database
        -includes/shopping_cart_update.php
