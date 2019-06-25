<?php

/**************************** GENERATE VIEW ****************************************/
    #Nav of homepage is differ from other page
    $navBgColor = ($_SERVER['SCRIPT_NAME'] !== "/php_project/index.php")?
    "<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar' style='background-color:#24355C;'>":
    "<nav class='navbar navbar-expand-lg navbar-dark fixed-top scrolling-navbar'>";

    #Store html element that contain maintenance function
    $maintenance = "";

    #Assign function to user base on their rolw
    if(isset($_SESSION['role']))
    {
        #Store temporary user information
        $user = NULL; 

        switch($_SESSION['role'])
        {
            case 1:
                $user = $_SESSION['superAdmin'];

                #Super admin's dropdown 
                $dropdown ='
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="my_account.php">My account</a>
                    <a class="dropdown-item" href="sign_out.php">Log out</a>
                </div>';

                #Super admin's function
                $maintenance = "
                <li class='nav-item'>
                    <a class='nav-link' href='maintenance.php'>Maintenance</a>
                </li>
                ";

            break;

            case 2:
                $user = $_SESSION['admin'];

                #admin's dropdown 
                $dropdown ='
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="my_account.php">My account</a>
                    <a class="dropdown-item" href="my_page.php">My page</a>
                    <a class="dropdown-item" href="sign_out.php">Log out</a>
                </div>';
            break;

            case 3:
                $user = $_SESSION['user'];  

                #normal user's dropdown
                $dropdown ='
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="my_account.php">My account</a>
                    <a class="dropdown-item" href="sign_out.php">Log out</a>
                </div>';
            break;
        }

    #The content of user navigation bar  
    $navContent =
    <<< USER
        <ul class='navbar-nav nav-flex-icons'>
            <li class='nav-item'>
                <a class='nav-link'>
                    <form class='form-inline' method='post' action='institute.php'>
                        <input class='form-control mr-sm-2' type='text' name='searchInstitute' placeholder='Search Institute'>
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
        #The content of visitor's navigation 
        $navContent = 
        <<< VISITOR
            <ul class='navbar-nav nav-flex-icons'>
                <li class='nav-item'>
                    <a class='nav-link'>
                        <form class='form-inline' method='post' action='institute.php'>
                            <input class='form-control mr-sm-2' type='text' name='searchInstitute' placeholder='Search College'>
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

#Generate navigation bar
$nav = 
    <<< NAV
        $navBgColor
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
                        $maintenance
                    </ul>
                    $navContent
                </div>
            </div>
        </nav>
NAV;

/************************** VIEW *****************************/
    echo $nav;

?>
