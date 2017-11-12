<?php
//Print function to avoid XSS
function xss($message)
{
    echo(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
}
