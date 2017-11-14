<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a href="index.php">
        <img class="nav-logo" src="assets/img/logo-black.png" alt="Official Logo of Engage Games">
    </a>
    <div class="container-fluid">
        <div class="row nav-center">
            <div class="col-sm-12 nav-center">
                <form class="navbar-form align-middle" action="search.php" method="get" role="search">
                <div class="input-group nav-search">
                    <input type="text" class="form-control" placeholder="Search" name="q">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="navbar-collapse navbar-toggleable-md collapse dual-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item first">
                <a class="nav-link text-black nav-item-bold" href="index.php">HOME</a>
            </li>
            <li class="nav-item second">
                <a class="nav-link text-black nav-item-bold" href="catalogue.php">CATALOGUE</a>
            </li>
            <li class="nav-item third">
                <a class="nav-link text-black nav-item-bold" target="_blank" href="https://github.com/SzymonB7/EngageGames">ABOUT</a>
            </li>

            <hr class="navbar-underline">
        </ul>
        <hr class="vertical-hr">
        <?php

        //If file is being called directly exit
        if(basename(__FILE__) == basename($_SERVER['PHP_SELF']))
        {
            header("Location: ../index.php");
            exit();
        }

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
              <a title="profile" href="profile.php">
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
    </div>
</nav>
<?php
