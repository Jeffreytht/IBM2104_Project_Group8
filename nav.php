<?php
$style = ($_SERVER['SCRIPT_NAME'] !== "/php_project/index.php")?"<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar' style='background-color:#24355C;'>":"<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar'>";

$userManagement = "";

if(isset($_SESSION['user'])||isset($_SESSION['admin'])||isset($_SESSION['superAdmin']))
{
    $user; 

    if(isset($_SESSION['user']))
    {
        $user = $_SESSION['user'];  
        $dropdown = $user->getRoleId();
    }
    else if(isset($_SESSION['admin']))
    {
        global $user, $dropdown; 
        $user = $_SESSION['admin'];
        $dropdown = $user->getRoleId();
    }
    else
    {
        global $user, $dropdown;
        $user = $_SESSION['superAdmin'];
        $dropdown = $user->getRoleId();
    }

    switch($user->getRoleId())
    {
        case 1:
            $dropdown ='
            <div class="dropdown-menu">
                <a class="dropdown-item" href="my_account.php">My account</a>
                <a class="dropdown-item" href="sign_out.php">Log out</a>
            </div>';

            $userManagement = "
            <li class='nav-item'>
                <a class='nav-link' href='Maintenance.php'>User Maintenance</a>
            </li>
            ";
            break;

        case 2:
            $conn = new mysqli("localhost","root","","college_portal");
            $sql = "CALL SelectInstituteDetails(SELECT institute_id FROM institute_user WHERE user_id = {$_SESSION['admin']->getUserID()})";
                

            $dropdown ='
            <div class="dropdown-menu">
                <a class="dropdown-item" href="my_account.php">My account</a>
                <a class="dropdown-item" href="my_page.php">My page</a>
                <a class="dropdown-item" href="sign_out.php">Log out</a>
            </div>';
            break;

        case 3:
            $dropdown ='
            <div class="dropdown-menu">
                <a class="dropdown-item" href="my_account.php">My account</a>
                <a class="dropdown-item" href="sign_out.php">Log out</a>
            </div>';
            break;
    }

$userPage =
<<< USER
    <ul class='navbar-nav nav-flex-icons'>
        <li class='nav-item'>
            <a class='nav-link'>
                <form class='form-inline' action='/action_page.php'>
                    <input class='form-control mr-sm-2' type='text' placeholder='Search Institute'>
                    <button type='submit' id='searchButton' class ='btn btn-success btn-rounded'>Search<i class='fas fa-search pl-2'></i></button>
                </form>
            </a>
        </li>
    </ul>
    <ul class='navbar-nav smooth-scroll ml-3'>
        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' data-toggle="dropdown" href='#'><i class="fas fa-user pr-2"></i>{$user->getUsername()}</a>
            $dropdown
        </li>
    </ul>
USER;
}

else
$userPage = 
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
                        <a class='nav-link' href='institute.php'>Institute</a>
                    </li>
                    $userManagement
                </ul>
                $userPage
            </div>
        </div>
    </nav>
NAV;

/*PRINT HTML PAGE*/
echo $nav;

?>
