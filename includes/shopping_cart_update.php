<?php
session_start();

$max_number_of_games =      100000;
$max_quantity_per_product = 9;

if (empty($_SESSION['email']) || empty($_POST['update']))
{
    exit();
}
else
{
    //Include database connection
    require_once('db.inc.php');

    //Read in AJAX POST variable, replace everything except numbers and ':'
    $update = $_POST['update'];
    $update = preg_replace("/[^0-9:]/", "", $update);

    //Split into two parts, ignore everything after second ':'
    $update = explode(":", $update);
    $id = $update[0];
    $quantity = $update[1];

    if ($id > $max_number_of_games || $quantity > $max_quantity_per_product)
    {
        exit();
    }
    else
    {
        //If quantity is 0 delete the game
        if ($quantity == 0)
        {
            $prepared_statement = $db->prepare("DELETE FROM shopping_cart WHERE email = ? AND game_id = ?");
            $prepared_statement->bind_param("si", $_SESSION['email'], $id);
            $prepared_statement->execute();
            $prepared_statement->close();
            exit();
        }
        //If quantity isn't 0 user either updated quantity or undid deletion
        else
        {
            //Check if game already exists for update
            $prepared_statement = $db->prepare("SELECT count(*) FROM shopping_cart WHERE email = ? AND game_id = ?");
            $prepared_statement->bind_param("si", $_SESSION['email'], $id);
            $prepared_statement->execute();
            $prepared_statement->bind_result($result);
            $prepared_statement->fetch();
            $prepared_statement->close();

            //Game exists (UPDATE)
            if ($result > 0)
            {
                $prepared_statement = $db->prepare("UPDATE shopping_cart SET quantity = ? WHERE email = ? AND game_id = ?");
                $prepared_statement->bind_param("isi", $quantity, $_SESSION['email'], $id);
                $prepared_statement->execute();
                $prepared_statement->close();
                exit();
            }
            else
            {
                //Game doesn't exist so insert it
                $prepared_statement = $db->prepare("INSERT INTO shopping_cart values (?, ?, ?)");
                $prepared_statement->bind_param("isi", $id, $_SESSION['email'], $quantity);
                $prepared_statement->execute();
                $prepared_statement->close();
                exit();
            }
        }
    }
}

?>
