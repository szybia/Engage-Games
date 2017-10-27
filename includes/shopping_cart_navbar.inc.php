<?php
if (!empty($_SESSION['email']))
{    ?>
    <div class="logged-in">
      <a href="shopping_cart.php">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <div class="numberCircle">
            <?php
                //Number of items in shopping cart
                $prepared_statement = $db->prepare("select count(*) from user join shopping_cart using (email) where email = ?");
                $prepared_statement->bind_param("s", $_SESSION['email']);
                $prepared_statement->execute();
                $prepared_statement->bind_result($shopping_cart_num);
                $prepared_statement->fetch();
                $prepared_statement->close();
                xss($shopping_cart_num);
            ?>
        </div>
      </a>
      <a href="profile.php">
        <img class="logged-in-img" src="assets/img/users/<?php xss($_SESSION['user_image_path']); ?>" alt="Profile picture of <?php xss($_SESSION['username']); ?>">
      </a>
    </div>
<?php
}
else
{ ?>
    <div class="login">
        <i class="fa fa-unlock" aria-hidden="true"></i>
        <a class="text-black nav-item-bold" href="login.php">LOGIN</a>
    </div>
    <div class="register">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        <a class="text-black nav-item-bold" href="login.php">REGISTER</a>
    </div>
<?php
} ?>
