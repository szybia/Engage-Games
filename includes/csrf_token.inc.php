<?php
if (empty($_SESSION['CSRFToken']))
{
    $_SESSION['CSRFToken'] = bin2hex(random_bytes(16));
}
