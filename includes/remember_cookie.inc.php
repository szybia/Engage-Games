<?php

//If session doesn't exist
if (empty($_SESSION['email']))
{
    //If remember me cookies is set
    if (isset($_COOKIE['remembermeengage']))
    {
        //Expand cookie
        $cookie = $_COOKIE['remembermeengage'];
        $cookie = explode(".", $cookie);

        //Search for cookie selector
        $prepared_statement = $db->prepare("SELECT email, username, user_image_path, remember_me_verifier from user where remember_me_selector = ?");
        $prepared_statement->bind_param("s", $cookie[0]);
        $prepared_statement->execute();
        $prepared_statement->bind_result($d_email, $d_username, $d_user_image_path, $d_verifier);
        $prepared_statement->fetch();
        $prepared_statement->close();

        //If cookie verifier is correct
        if ($cookie[1] == $d_verifier)
        {
            $_SESSION['user_image_path'] = empty($d_user_image_path) ? "default.png" : $d_user_image_path;
            $_SESSION['username'] = $d_username;
            $_SESSION['email'] = $d_email;
        }
    }
}
