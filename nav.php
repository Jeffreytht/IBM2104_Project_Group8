<?php

session_start();
$style = ($_SERVER['SCRIPT_NAME'] !== "/php_project/index.php")?"<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar' style='background-color:#24355C;'>":"<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar'>";

if(isset($_SESSION['user']))
$user =
<<< USER
    <ul class='navbar-nav nav-flex-icons'>
        <li class='nav-item'>
            <a class='nav-link'>
                <form class='form-inline' action='/action_page.php'>
                    <input class='form-control mr-sm-2' type='text' placeholder='Search College'>
                    <button type='submit' id='searchButton' class ='btn btn-success btn-rounded'>Search<i class='fas fa-search pl-2'></i></button>
                </form>
            </a>
        </li>
    </ul>
    <ul class='navbar-nav smooth-scroll ml-3'>
        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' data-toggle="dropdown" href='#'><i class="fas fa-user pr-2"></i>$_SESSION[user]</a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#">My account</a>
                <a class="dropdown-item" href="sign_in.php">Log out</a>
            </div>
        </li>
    </ul>
USER;

else
$user = 
<<< VISITOR
    <ul class='navbar-nav nav-flex-icons'>
        <li class='nav-item'>
            <a class='nav-link'>
                <form class='form-inline' action='/action_page.php'>
                    <input class='form-control mr-sm-2' type='text' placeholder='Search College'>
                    <button type='submit' id='searchButton' class ='btn btn-success btn-rounded'>Search<i class='fas fa-search pl-2'></i></button>
                </form>
            </a>
        </li>
    </ul>
    <ul class='navbar-nav smooth-scroll ml-3'>
        <li class='nav-item'>
            <a class='nav-link' href='sign_in.php'><i class='fas fa-sign-in-alt pr-2'></i>Sign In</a>
        </li>
    </ul>
VISITOR;

$nav = 
<<< NAV
    $style
    <div class='container'>
            <a class='navbar-brand' href='index.php'>GODs</a>
            <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#basicExampleNav'aria-controls='basicExampleNav' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='basicExampleNav'>
                <ul class='navbar-nav mr-auto smooth-scroll'>
                    <li class='nav-item'>
                        <a class='nav-link' href='index.php'>Home</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='news.php'>News</a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='college.php'>Colleges</a>
                    </li>
                </ul>
                $user
            </div>
        </div>
    </nav>
NAV;

/*PRINT HTML PAGE*/
echo $nav;

?>
