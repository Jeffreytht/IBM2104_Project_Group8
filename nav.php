<?php
    if($_SERVER['SCRIPT_NAME'] !== "/php_project/index.php")
        echo"<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar' style='background-color:#24355C;'>";
    else
        echo"<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar'>";

        echo"<div class='container'>";
            echo"<a class='navbar-brand' href='index.php'>GODs</a>";
            echo"<button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#basicExampleNav'aria-controls='basicExampleNav' aria-expanded='false' aria-label='Toggle navigation'>";
                echo"<span class='navbar-toggler-icon'></span>";
            echo"</button>";
            echo"<div class='collapse navbar-collapse' id='basicExampleNav'>";
                echo"<ul class='navbar-nav mr-auto smooth-scroll'>";
                    echo"<li class='nav-item'>";
                        echo"<a class='nav-link' href='index.php'>Home</a>";
                    echo"</li>";
                    echo"<li class='nav-item'>";
                        echo"<a class='nav-link' href='college.php'>Colleges</a>";
                    echo"</li>";
                echo"</ul>";
                echo"<ul class='navbar-nav nav-flex-icons'>";
                    echo"<li class='nav-item'>";
                        echo"<a class='nav-link'>";
                            echo"<form class='form-inline' action='/action_page.php'>";
                                echo"<input class='form-control mr-sm-2' type='text' placeholder='Search College'>";
                                echo"<button type='submit' id='searchButton' class = 'btn btn-success btn-rounded'>Search<i class='fas fa-search pl-2'></i></button>";
                            echo"</form>";
                        echo"</a>";
                    echo"</li>";
                    echo"<li class='nav-item'>";
                        echo"<a class='nav-link' href='sign_in.php'><i class='fas fa-sign-in-alt fa-2x mt-3'></i></a>";
                    echo"</li>";
                    echo"<li class='nav-item'>";
                        echo"<a class='nav-link'><i class='fas fa-sign-out-alt fa-2x mt-3'></i></a>";
                    echo"</li>";
                echo"</ul>";
            echo"</div>";
        echo"</div>";
    echo"</nav>";
?>
