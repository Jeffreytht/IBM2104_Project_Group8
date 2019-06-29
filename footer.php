<?php
$date = date('Y');
echo <<< FOOTER
    <footer class='page-footer font-small unique-color-dark'>
        <div class='container pt-2 mb-0 text-center text-md-left'>
            <div class='row'>
<!--GOD-->
                <div class='col-md-3 col-lg-4 col-xl-3 mb-4 mt-3'>
                    <h6 class='text-uppercase font-weight-bold'>
                        <strong>GODs</strong>
                    </h6>
                    <hr class='deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto' style='width: 60px;'>
                    <p>Explore top-ranking universities catered to your needs, in one place. Key in details ONCE to apply to multiple institutions.Sift through numerous offers and find the best one for you & your future!</p>
                </div>
<!--END GOD-->
<!--USEFUL LINK-->
                <div class='col-md-3 col-lg-2 col-xl-2 mx-auto mb-4 mt-3'>
                    <h6 class='text-uppercase font-weight-bold'>
                        <strong>Useful links</strong>
                    </h6>
                    <hr class='deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto' style='width: 60px;'>
                    <p>
                        <a href='my_account.php'>Your Account</a>
                    </p>
                    <p>
                        <a href='institute.php'>Colleges</a>
                    </p>
                    <p>
                        <a href='#!'>Help</a>
                    </p>
                </div>
<!--END USEFUL LINK-->
<!--CONTACT-->
                <div class='col-md-4 col-lg-3 col-xl-3 mt-3'>
                    <h6 class='text-uppercase font-weight-bold'>
                        <strong>Contact</strong>
                    </h6>
                    <hr class='deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto' style='width: 60px;'>
                    <p>
                        <i class='fa fa-envelope mr-3'></i>
                        <a href="mailto:GODs@gmail.com">GODs@gmail.com</a>
                    </p>
                    <p>
                        <i class='fa fa-phone mr-3'></i>
                        <a href="tel:+0123456788">+ 01 234 567 88</a>
                    </p>
                    <p>
                        <i class='fa fa-print mr-3'></i>
                        <a href="fax:+0123456789">+ 01 234 567 89</a>
                    </p>
                </div>
<!--END CONTACT-->
            </div>
        </div>
        <div class='footer-copyright text-center py-2'>© $date Copyright:
            <a href='index.php'> GODs</a>
        </div>
    </footer>
FOOTER;
?>
