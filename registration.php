<?php
session_start();
if (isset($_POST['email'])) {
    //Validation
    $validation_OK = true;

    //Checking login
    $login = $_POST['login'];
    if (strlen($login) < 3 || (strlen($login) > 25)) {
        $validation_OK = false;
        $_SESSION['e_login'] = "Login must be between 3 and 25 characters long!";
    }
    if (ctype_alnum($login) == false) {
        $validation_OK = false;
        $_SESSION['e_login'] = "Login can only consist of letters and numbers";
    }

    //Checking email
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $validation_OK = false;
        $_SESSION['e_email'] = "Please enter a valid e-mail address";
    }

    //Checking password
    $password = $_POST['password'];
    $repeatpassword = $_POST['repeat-password'];

    if ((strlen($password) < 8) || (strlen($password) > 30)) {
        $validation_OK = false;
        $_SESSION['e_password'] = "The password must be 8 to 30 characters long";
    }

    if ($password != $repeatpassword) {
        $validation_OK = false;
        $_SESSION['e_password'] = "The passwords provided do not match";
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    //Remember data
    $_SESSION['fr_login'] = $login;
    $_SESSION['fr_email'] = $email;
    $_SESSION['fr_password'] = $password;
    $_SESSION['fr_repeat_password'] = $repeatpassword;

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    try {
        $link = new mysqli($db_server, $db_login, $db_password, $db_name);
        if ($link->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            //Email exist
            $result = $link->query("SELECT id FROM users WHERE email='$email'");
            if (!$result) {
                throw new Exception($link->error);
            }
            $how_many_emails = $result->num_rows;
            if ($how_many_emails > 0) {
                $validation_OK = false;
                $_SESSION['e_email'] = "There is already an account assigned to this email";
            }

            //CLogin exist
            $result = $link->query("SELECT id FROM users WHERE user='$login'");
            if (!$result) {
                throw new Exception($link->error);
            }
            $how_many_logins = $result->num_rows;
            if ($how_many_logins > 0) {
                $validation_OK = false;
                $_SESSION['e_login'] = "There is already a user with this login";
            }

            if ($validation_OK == true) {
                if ($link->query("INSERT INTO users VALUES(NULL, '$login','$email', '$password_hash')")) {
                    $_SESSION['successful_registration'] = true;
                    header('Location: successful.php');
                } else {
                    throw new Exception($link->error);
                }
            }

            $link->close();
        }
    } catch (Exception $e) {
        echo "Server error! Sorry :/";
        echo "<br/> Information for the developer: " . $e;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ProjectQ12 | Registration</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <main>
        <!-- Aside section -->
        <section id="left-container">
            <div class="sign-up-link">
                <h2>You have account already?</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla blandit, turpis sit amet vestibulum suscipit, urna nunc interdum felis, pulvinar laoreet lacus velit eu velit.</p>
                <div class="btn-sign-up">
                    <a href="index.php" class="btn">Sign in</a>
                </div>
            </div>
        </section>

        <!-- Main section -->
        <section id="right-container">
            <div class="wrapper">
                <div class="sign-in">
                    <h2><span class="gradient">Sign</span> <span class="line">up</span></h2>
                    <form method="POST" class="sign-in-form">

                        <!-- Login input -->
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" name="login" placeholder="Login" value="<?php
                                                                                        if (isset($_SESSION['fr_login'])) {
                                                                                            echo $_SESSION['fr_login'];
                                                                                            unset($_SESSION['fr_login']);
                                                                                        }
                                                                                        ?>" />
                            <div class="box-info">
                                <?php
                                if (isset($_SESSION['e_login'])) {
                                    echo '<i class="fas fa-times error"></i>';
                                }
                                ?>
                            </div>
                            <?php
                            if (isset($_SESSION['e_login'])) {
                                echo '<div class="info-error">';
                                echo $_SESSION['e_login'];
                                unset($_SESSION['e_login']);
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <!-- Email input -->
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="text" name="email" placeholder="E-mail" value="<?php
                                                                                        if (isset($_SESSION['fr_email'])) {
                                                                                            echo $_SESSION['fr_email'];
                                                                                            unset($_SESSION['fr_email']);
                                                                                        }
                                                                                        ?>" />
                            <div class="box-info">
                                <?php
                                if (isset($_SESSION['e_email'])) {
                                    echo '<i class="fas fa-times error"></i>';
                                }
                                ?>
                            </div>
                            <?php
                            if (isset($_SESSION['e_email'])) {
                                echo '<div class="info-error">';
                                echo $_SESSION['e_email'];
                                unset($_SESSION['e_email']);
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <!-- Password input -->
                        <div class="input-field">
                            <i class="fas fa-key"></i>
                            <input type="password" name="password" placeholder="Password" class="password" />
                            <div class="box-info">
                                <i class="far fa-eye"></i>
                                <i class="far fa-eye-slash"></i>
                                <?php
                                if (isset($_SESSION['e_password'])) {
                                    echo '<i class="fas fa-times error"></i>';
                                }
                                ?>
                            </div>
                            <?php
                            if (isset($_SESSION['e_password'])) {
                                echo '<div class="info-error">';
                                echo $_SESSION['e_password'];
                                unset($_SESSION['e_password']);
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <!-- Repeat password input -->
                        <div class="input-field">
                            <i class="fas fa-redo-alt"></i>
                            <input type="password" name="repeat-password" placeholder="Repeat password" class="password" />
                            <div class="box-info">
                                <i class="far fa-eye"></i>
                                <i class="far fa-eye-slash"></i>
                            </div>
                        </div>
                        <input type="submit" value="sign-up" class="btn" />
                    </form>
                </div>
                <div id="img-up">
                    <svg width="547" height="674" viewBox="0 0 547 674" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="sign-up">
                            <g id="phone-check">
                                <path id="Path 607" d="M333 158.431H329.337V58.08C329.337 42.6762 323.218 27.9034 312.326 17.0112C301.434 6.11913 286.661 0 271.257 0H58.651C43.2472 0 28.4744 6.11913 17.5822 17.0112C6.69013 27.9034 0.571014 42.6762 0.571014 58.08V608.61C0.571014 624.014 6.69013 638.787 17.5822 649.679C28.4744 660.571 43.2472 666.69 58.651 666.69H271.257C286.661 666.69 301.434 660.571 312.326 649.679C323.218 638.787 329.337 624.014 329.337 608.61V229.862H333V158.431Z" fill="#3F3D56" />
                                <path id="Path 608" d="M316.975 58.486V608.205C316.975 619.709 312.405 630.741 304.27 638.875C296.136 647.01 285.104 651.58 273.6 651.58H59.974C48.4707 651.579 37.4386 647.01 29.3045 638.875C21.1704 630.741 16.6005 619.709 16.6 608.206V58.486C16.6003 46.9823 21.1702 35.9499 29.3045 27.8155C37.4389 19.6812 48.4713 15.1113 59.975 15.111H85.891C84.6174 18.2402 84.1324 21.6344 84.4785 24.9951C84.8246 28.3558 85.9912 31.5799 87.8759 34.3839C89.7605 37.1878 92.3053 39.4857 95.2864 41.0753C98.2675 42.6649 101.594 43.4975 104.972 43.5H226.772C230.151 43.4977 233.477 42.6652 236.458 41.0756C239.439 39.4861 241.984 37.1882 243.869 34.3843C245.754 31.5803 246.92 28.3561 247.267 24.9954C247.613 21.6346 247.128 18.2403 245.854 15.111H273.6C285.104 15.1113 296.136 19.6812 304.27 27.8155C312.405 35.9499 316.975 46.9823 316.975 58.486V58.486Z" fill="white" />
                                <path id="Path 609" d="M115.286 474.693H110.793L112.293 386.937H113.793L115.286 474.693Z" fill="#E6E6E6" />
                                <path id="Path 610" d="M119.029 331.687L126.281 320.48C124.13 314.539 121.646 308.725 118.841 303.064L114.152 306.852L117.865 301.114C114.326 294.149 111.542 289.76 111.542 289.76C111.542 289.76 97 312.677 92.139 336.945L101.445 351.327L91.145 343.006C90.7781 345.833 90.5894 348.68 90.58 351.53C90.58 380.269 99.966 403.566 111.544 403.566C123.122 403.566 132.508 380.266 132.508 351.53C132.508 342.621 130.492 333.3 127.708 324.677L119.029 331.687Z" fill="#CCCCCC" />
                                <path id="Path 611" d="M232.245 474.693H227.752L229.252 386.937H230.752L232.245 474.693Z" fill="#E6E6E6" />
                                <path id="Path 612" d="M235.988 331.687L243.24 320.48C241.089 314.539 238.605 308.725 235.8 303.064L231.111 306.852L234.824 301.114C231.285 294.149 228.501 289.76 228.501 289.76C228.501 289.76 213.957 312.679 209.101 336.945L218.4 351.325L208.1 343.004C207.733 345.831 207.544 348.678 207.535 351.528C207.535 380.267 216.921 403.564 228.499 403.564C240.077 403.564 249.463 380.264 249.463 351.528C249.463 342.619 247.447 333.298 244.663 324.675L235.988 331.687Z" fill="#CCCCCC" />
                                <path id="Ellipse 94" d="M111.867 183.798C134.665 183.798 153.147 165.316 153.147 142.518C153.147 119.72 134.665 101.238 111.867 101.238C89.0687 101.238 70.587 119.72 70.587 142.518C70.587 165.316 89.0687 183.798 111.867 183.798Z" fill="#E6E6E6" />
                                <path id="Path 613" d="M169.043 461.916H159.78L162.868 280.971H165.955L169.043 461.916Z" fill="#E6E6E6" />
                                <path id="Path 614" d="M176.762 167.051L191.715 143.945C187.281 131.696 182.159 119.706 176.374 108.034L166.705 115.843L174.361 104.011C167.061 89.649 161.324 80.6 161.324 80.6C161.324 80.6 131.336 127.856 121.317 177.89L140.5 207.545L119.258 190.388C118.502 196.216 118.112 202.087 118.093 207.964C118.093 267.221 137.446 315.258 161.319 315.258C185.192 315.258 204.545 267.221 204.545 207.964C204.545 189.594 200.389 170.375 194.656 152.596L176.762 167.051Z" fill="#CCCCCC" />
                                <path id="Path 615" d="M316.975 487.998V611.006C316.975 635.955 297.556 656.18 273.6 656.181H59.974C36.019 656.181 16.599 635.956 16.598 611.006V499.99C53.636 454.708 107.187 427.645 164.26 425.366C221.333 423.087 276.717 445.801 316.975 487.998Z" fill="#E6E6E6" />
                                <g id="check">
                                    <path id="Ellipse 95" opacity="0.2" d="M167.02 609.338C201.929 609.338 230.228 581.039 230.228 546.13C230.228 511.221 201.929 482.922 167.02 482.922C132.111 482.922 103.812 511.221 103.812 546.13C103.812 581.039 132.111 609.338 167.02 609.338Z" fill="#EED996" />
                                    <path id="Ellipse 96" d="M167.02 597.025C195.129 597.025 217.915 574.239 217.915 546.13C217.915 518.021 195.129 495.235 167.02 495.235C138.911 495.235 116.125 518.021 116.125 546.13C116.125 574.239 138.911 597.025 167.02 597.025Z" fill="url(#paint0_linear)" />
                                    <path id="Path 616" d="M161.084 567.89L143.147 544.829L153.578 536.716L162.07 547.635L190.759 517.351L200.353 526.439L161.084 567.89Z" fill="white" />
                                </g>
                            </g>
                            <g id="Group 24">
                                <path id="Ellipse 97" d="M437.486 466.969C455.711 466.969 470.486 452.194 470.486 433.969C470.486 415.744 455.711 400.969 437.486 400.969C419.261 400.969 404.486 415.744 404.486 433.969C404.486 452.194 419.261 466.969 437.486 466.969Z" fill="#2F2E41" />
                                <path id="Path 624" d="M450.859 619.226L453.401 631.219L500.871 627.119L497.12 609.419L450.859 619.226Z" fill="#FFB8B8" />
                                <path id="Path 625" d="M447.163 656.707L439.175 619.013L453.738 615.927L458.64 639.057C459.046 640.969 459.07 642.943 458.713 644.865C458.355 646.787 457.623 648.62 456.557 650.259C455.491 651.898 454.113 653.311 452.501 654.417C450.889 655.523 449.075 656.301 447.163 656.707Z" fill="#2F2E41" />
                                <path id="Path 626" d="M421.12 618.586L415.777 629.621L370.677 614.264L378.563 597.979L421.12 618.586Z" fill="#FFB8B8" />
                                <path id="Path 627" d="M408.813 635.985L419.117 614.704L432.516 621.192L415.724 655.871C412.171 654.15 409.446 651.089 408.15 647.359C406.854 643.63 407.092 639.539 408.813 635.985V635.985Z" fill="#2F2E41" />
                                <path id="Path 628" d="M332.259 596.793C333.803 597.08 335.391 597.026 336.912 596.633C338.432 596.24 339.848 595.519 341.06 594.52C342.272 593.521 343.25 592.268 343.925 590.85C344.6 589.432 344.956 587.883 344.968 586.313L419.342 517.742L400.872 503.437L333.535 575.379C330.894 575.533 328.402 576.65 326.531 578.519C324.659 580.388 323.538 582.879 323.381 585.519C323.224 588.159 324.041 590.765 325.678 592.843C327.314 594.921 329.656 596.326 332.26 596.792L332.259 596.793Z" fill="#FFB8B8" />
                                <path id="Ellipse 98" d="M433.344 468.459C446.909 468.459 457.905 457.463 457.905 443.898C457.905 430.333 446.909 419.337 433.344 419.337C419.779 419.337 408.783 430.333 408.783 443.898C408.783 457.463 419.779 468.459 433.344 468.459Z" fill="#FFB8B8" />
                                <path id="Path 629" d="M455.386 588.272C438.157 588.272 417.408 584.644 404.607 569.795L404.319 569.461L404.619 569.133C404.719 569.026 414.133 558.264 404.73 539.072L391.8 543.049L378.93 526.06L386.06 504.671L415.237 481.171C419.417 477.821 424.506 475.804 429.846 475.379C439.51 474.445 448.925 471.764 457.631 467.466C461.615 465.53 466.003 464.57 470.431 464.666L471.005 464.682C472.352 464.72 473.677 465.031 474.9 465.596C476.123 466.161 477.218 466.969 478.12 467.97C479.021 468.972 479.71 470.146 480.144 471.421C480.578 472.696 480.749 474.047 480.646 475.39C478.667 501.02 475.174 562.933 485.438 584.251L485.703 584.802L485.111 584.952C475.355 587.147 465.388 588.26 455.388 588.271L455.386 588.272Z" fill="#EED996" />
                                <path id="Path 630" d="M407.986 569.469C407.986 569.469 342.986 563.469 335.986 582.469C328.986 601.469 336.986 610.469 348.986 614.469C360.986 618.469 389.986 623.469 389.986 623.469L402.986 607.469L436.986 609.469C436.986 609.469 474.871 631.442 485.346 654.943C487.594 660.028 491.177 664.41 495.716 667.621C500.255 670.832 505.579 672.753 511.123 673.179C519.523 673.763 526.986 670.592 526.986 657.467C526.986 627.467 484.986 584.467 484.986 584.467L407.986 569.469Z" fill="#2F2E41" />
                                <path id="Path 631" d="M358.486 598.969C358.486 598.969 375.486 593.969 402.486 606.969Z" fill="#2F2E41" />
                                <path id="Path 632" d="M408.843 428.234C418.435 434.089 429.249 437.652 440.443 438.646L437.112 434.655C439.537 435.551 442.089 436.057 444.673 436.155C445.967 436.193 447.251 435.926 448.423 435.378C449.596 434.829 450.623 434.013 451.423 432.996C452.077 431.94 452.465 430.741 452.555 429.502C452.645 428.263 452.433 427.021 451.939 425.881C450.924 423.608 449.344 421.632 447.35 420.142C443.815 417.346 439.643 415.467 435.206 414.674C430.768 413.881 426.204 414.198 421.919 415.597C418.978 416.475 416.344 418.163 414.319 420.469C413.324 421.63 412.633 423.02 412.308 424.515C411.983 426.009 412.034 427.56 412.456 429.03" fill="#2F2E41" />
                                <path id="Path 633" d="M437.309 408.603C441.673 398.469 448.205 389.416 456.446 382.079C461.738 377.379 467.919 373.336 474.892 372.116C481.865 370.896 489.725 372.986 494.003 378.627C497.503 383.24 498.155 389.42 497.77 395.196C497.385 400.972 496.094 406.696 496.217 412.48C496.343 418.386 497.942 424.166 500.869 429.297C503.796 434.427 507.958 438.746 512.977 441.86C517.996 444.975 523.714 446.786 529.61 447.13C535.507 447.474 541.397 446.34 546.744 443.831C540.722 447.16 536.03 452.431 530.444 456.439C524.858 460.447 517.481 463.199 511.132 460.549C504.414 457.749 501.332 450.13 498.926 443.259L488.194 412.619C486.37 407.41 484.455 402.047 480.732 397.974C477.009 393.901 470.967 391.417 465.843 393.465C461.959 395.017 459.43 398.723 457.213 402.265C454.996 405.807 452.656 409.585 448.913 411.444C445.17 413.303 439.613 412.159 438.39 408.159" fill="#2F2E41" />
                                <path id="Path 636" d="M445.736 588.358C445.312 588.358 444.889 588.335 444.468 588.288C441.673 587.974 439.105 586.6 437.291 584.451C435.477 582.301 434.556 579.539 434.716 576.731C434.876 573.923 436.106 571.283 438.152 569.353C440.199 567.424 442.906 566.352 445.719 566.357C446.21 566.36 446.699 566.396 447.185 566.466L480.763 530.721L477.258 519.036L494.858 511.144L499.792 523.204C501.446 527.289 501.739 531.799 500.627 536.064C499.516 540.329 497.058 544.121 493.62 546.879L456.659 576.279C456.698 576.637 456.718 576.998 456.719 577.358C456.718 578.905 456.392 580.435 455.76 581.848C455.129 583.261 454.207 584.525 453.055 585.558C451.048 587.369 448.439 588.367 445.736 588.358V588.358Z" fill="#FFB8B8" />
                                <path id="Path 637" d="M471.986 469.469C473.15 468.952 474.407 468.675 475.681 468.655C476.955 468.636 478.219 468.874 479.399 469.355C480.578 469.837 481.648 470.552 482.545 471.458C483.441 472.363 484.145 473.441 484.614 474.625L501.986 518.469L474.986 536.469L471.986 469.469Z" fill="#EED996" />
                            </g>
                        </g>
                        <defs>
                            <linearGradient id="paint0_linear" x1="167.02" y1="495.235" x2="167.02" y2="597.025" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#EED996" />
                                <stop offset="1" stop-color="#FDCB6E" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>
            <h4>Please register and then log in to start using the website.</h4>
        </section>
    </main>
    <footer>
        <p>All right reserved.</p>
        <p>Created by: <a href="http://woytek-portfolio.pl/" target="_blank">Woytek</a></p>
    </footer>

    <!-- Effect after click button -->
    <script src="js/main.js"></script>
</body>

</html>