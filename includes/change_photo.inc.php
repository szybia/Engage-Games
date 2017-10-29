<?php
session_start();

require_once('include_only.inc.php');

function redirect($message)
{
    header("Location: ../profile.php?request=picture&q=$message");
    exit();
}

if (empty($_SESSION['email']))
{
    redirect("loggedin");
}
else
{
    if (empty($_POST['CSRFToken']))
    {
        redirect("csrftoken");
    }
    else
    {
        if ($_POST['CSRFToken'] != $_SESSION['CSRFToken'])
        {
            redirect("invalidtoken");
        }
        else
        {
            if ($_FILES['profile_picture']['name'] == ""    ||
                $_FILES['profile_picture']['size'] == 0)
            {
                redirect("empty");
            }
            else
            {
                //Include database connection
                require_once('db.inc.php');

                //Constant variables
                $path = "../assets/img/users/";
                $max_size = 1048576;
                $whitelist_ext = array('jpeg','jpg','png','gif', 'PNG', 'JPG', 'JPEG', 'GIF');
                $whitelist_type = array('image/jpeg', 'image/jpg', 'image/png','image/gif');

                //File info
                $file_info = pathinfo($_FILES['profile_picture']['name']);
                $file_name = $file_info['filename'];
                $file_ext  = $file_info['extension'];

                //If not legal extension
                if (!in_array($file_ext, $whitelist_ext))
                {
                    redirect("ext");
                }

                //If not legal file type
                if (!in_array($_FILES['profile_picture']["type"], $whitelist_type))
                {
                    redirect("ext");
                }

                //If image size too big
                if ($_FILES['profile_picture']["size"] > $max_size)
                {
                    redirect("size");
                }

                if (!getimagesize($_FILES['profile_picture']['tmp_name']))
                {
                    redirect("invalidimage");
                }

                $new_filename = bin2hex(random_bytes(15)) . '.' .  $file_ext;

                //While file with same name exists generate new one
                while (file_exists($path . $new_filename))
                {
                    $new_filename = bin2hex(random_bytes(15)) . '.' .  $file_ext;
                }

                //Move file to img folder
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $path . $new_filename))
                {
                    if ($_SESSION['user_image_path'] != "default.png")
                    {
                        //Delete old profile picture
                        if (is_writable($path . $_SESSION['user_image_path']))
                        {
                            unlink($path . $_SESSION['user_image_path']);
                        }
                    }

                    $_SESSION['user_image_path'] = $new_filename;

                    $prepared_statement = $db->prepare("UPDATE user SET user_image_path = ?
                                                        WHERE email = ?;");
                    $prepared_statement->bind_param("ss", $new_filename, $_SESSION['email']);
                    $prepared_statement->execute();
                    $prepared_statement->close();

                    redirect("success");
                }
                else
                {
                    redirect("servererror");
                }
            }
        }
    }
}
