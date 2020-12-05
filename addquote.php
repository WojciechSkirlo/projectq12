<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['quote-text'])) {
    // Validation
    $quote_OK = true;

    // Checking text of quote
    $text_quote = $_POST['quote-text'];
    if (strlen($text_quote) <= 8 || (strlen($text_quote) >= 512)) {
        $quote_OK = false;
        $_SESSION['e_quote_text'] = "The text of the quote cannot be less than 8 and more than 512 characters long";
    }


    require_once "connect.php";
    try {
        $link = new mysqli($db_server, $db_login, $db_password, $db_name);
        if ($link->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            $text_quote = $_POST['quote-text'];
            $author_id = $_POST['select-author'];
            $category_id = $_POST['select-category'];
            $login = $_SESSION['login'];
            if ($result = $link->query("SELECT * FROM users WHERE user='$login'")) {
                $row = $result->num_rows;
                if ($row > 0) {
                    while ($cell = $result->fetch_assoc()) {
                        $login_id = $cell['id'];
                    }
                }
            }

            // Check text_quote
            $text_quote_converted = htmlentities($text_quote, ENT_QUOTES);

            // Quote exist
            $result = $link->query("SELECT id FROM quotes WHERE text_quote='$text_quote_converted'");
            if (!$result) {
                throw new Exception($link->error);
            }
            $how_many_quotes = $result->num_rows;
            if ($how_many_quotes > 0) {
                $quote_OK = false;
                $_SESSION['e_quote_text'] = "The quote already exists in our database";
            }

            if ($quote_OK == true) {
                if ($link->query("INSERT INTO quotes(text_quote, author_id, user_id, categories_id) VALUES('$text_quote_converted', '$author_id', '$login_id', '$category_id')")) {
                    $_SESSION['successful_quote_add'] = true;
                    header('Location: quoteadded.php');
                    $_SESSION['text_quote'] = $text_quote_converted;
                    $_SESSION['author_of_quote'] = $author_id;
                    $_SESSION['category_quote'] = $category_id;
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
    <title>ProjectQ12 | Add quote</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="Shortcut icon" href="img/logo.svg" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>

    <!-- Main nav -->
    <nav id="main-nav">
        <div class="nav-up">
            <div class="info">
                <a href="addquote.php">
                    <div class="info-wrapper">
                        <i class="fas fa-quote-right"></i>
                        <span>Add quote</span>
                    </div>
                </a>
            </div>
            <a href="home.php">
                <div class="logo">
                    <img src="img/logo.svg" />
                    <span>PROJECTQ12</span>
                </div>
            </a>
            <div class="login">
                <div class="login-info">
                    <?php
                    echo '<div class="text-info">';
                    echo "<p><b>Login:</b> " . $_SESSION['login'] . "</p>";
                    echo "<p><b>E-mail:</b> " . $_SESSION['email'] . "</p>";
                    echo "<p><b>Role:</b> " . $_SESSION['role'] . "</p>";
                    echo '[<a href="logout.php">Log out!</a>]';
                    echo '</div>';
                    ?>
                </div>
                <div class="login-wrapper" id="user-account">
                    <i class="fas fa-user"></i>
                    <span>My account</span>
                </div>
            </div>
        </div>
        <div class="nav-down">
            <div class="wrapper">
                <a href="home.php">home</a>
                <a href="#">the latest</a>
                <a href="#">love</a>
                <a href="#">woman</a>
                <a href="#">man</a>
                <a href="#">god</a>
                <a href="#">sad</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </nav>

    <div id="video">
        <video id="videoBG" autoplay muted loop>
            <source src="video/bgvideo3.mp4" type="video/mp4">
        </video>
        <div id="quotation">
            <h3>Quote of the day</h3>
            <img src="img/logo-red.svg" />
            <h2>„If you tell the truth, you don't have to remember anything.”<span class="author"> - Mark Twain</span></h2>
        </div>
        <div class="bg-video"></div>
    </div>

    <!-- Main section -->
    <section id="home">
        <div class="wrapper">
            <img src="img/logo.svg" />
            <h3>„The secret of change is to focus all of your energy not on fighting the old, but on building the new”<span class="author"> - Socrates</span></h3>
        </div>
    </section>
    <section id="addquote">
        <div class="left-wrapper">
            <div class="box">
                <h3>Rules</h3>
            </div>
        </div>
        <div class="right-wrapper">
            <div class="box">
                <h3>So.. Let's add some quote <img src="img/logo-red.svg" /></h3>
                <form method="POST">
                    <p><img src="img/logo.svg" /></p>
                    <div class="quote-box">
                        <textarea name="quote-text" maxlength="512" placeholder="Text of the quote" required></textarea>
                        <label for="quote-text" class="label-text"></label>
                    </div>
                    <h4>
                        <?php
                        if (isset($_SESSION['e_quote_text'])) {
                            echo $_SESSION['e_quote_text'];
                            unset($_SESSION['e_quote_text']);
                        }
                        ?>
                    </h4>
                    <p><img src="img/logo.svg" /></p>
                    <div class="wrapper-category">
                        <div class="box-category">
                            <span>Category: </span>
                            <select name="select-category" required>
                                <option value="" selected>Select the category</option>
                                <?php
                                require_once "connect.php";

                                try {
                                    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                                    if ($link->connect_errno != 0) {
                                        throw new Exception(mysqli_connect_errno());
                                    } else {
                                        $result = $link->query("SELECT * FROM categories");
                                        if (!$result) {
                                            throw new Exception($link->error);
                                        }
                                        $how_many_categories = $result->num_rows;
                                        if ($how_many_categories > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                                            }
                                        }

                                        $link->close();
                                    }
                                } catch (Exception $e) {
                                    echo "Server error! Sorry :/";
                                    echo "<br/> Information for the developer: " . $e;
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="author-wrapper">
                        <div class="box-author">
                            <span>Author of the quote: </span>
                            <select name="select-author" required>
                                <option value="" selected>Select the author</option>
                                <?php
                                require_once "connect.php";

                                try {
                                    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                                    if ($link->connect_errno != 0) {
                                        throw new Exception(mysqli_connect_errno());
                                    } else {
                                        $result = $link->query("SELECT * FROM authors");
                                        if (!$result) {
                                            throw new Exception($link->error);
                                        }
                                        $how_many_authors = $result->num_rows;
                                        if ($how_many_authors > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value=" . $row['id'] . ">" . $row['name'] . " " . $row['surname'] . "</option>";
                                            }
                                        }

                                        $link->close();
                                    }
                                } catch (Exception $e) {
                                    echo "Server error! Sorry :/";
                                    echo "<br/> Information for the developer: " . $e;
                                }
                                ?>
                            </select>
                        </div>
                        <p>If you can't find the author, you can add him, but make sure that he hasn't already been added.</p>
                        <div class="add-author">Add author</div>
                    </div>
                    <input type="submit" value="Add quote" name="add-quote" />
                </form>
            </div>
        </div>
    </section>
    <section id="quote-img">
        <div class="quote-box">
            <img src="img/quotes/james-scott-01htj6kYvIo-unsplash.jpg" />
            <div class="background">
                <div class="category"></div>
                <div class="text">
                    <div class="category">
                        <h3>Category: <a href="#">God</a></h3>
                        <h3>Author: <a href="#">F. Sionil Jose</a></h3>
                    </div>
                    <h3>„When I wake up every morning, I thank God for the new day.”</h3>
                </div>
            </div>
        </div>
        <div class="quote-box">
            <img src="img/quotes/pexels-mathias-pr-reding-5331983.jpg" />
            <div class="background">
                <div class="text">
                    <div class="category">
                        <h3>Category: <a href="#">Man</a></h3>
                        <h3>Author: <a href="#">F. Sionil Jose</a></h3>
                    </div>
                    <h3>„When I wake up every morning, I thank God for the new day.”</h3>
                </div>
            </div>
        </div>
        <div class="quote-box">
            <img src="img/quotes/pexels-francesca-zama-5870591.jpg" />
            <div class="background">
                <div class="text">
                    <div class="category">
                        <h3>Category: <a href="#">Woman</a></h3>
                        <h3>Author: <a href="#">Imam Ali</a></h3>
                    </div>
                    <h3>„Beautiful people are not always good but good people are always beautiful”</h3>
                </div>
            </div>
        </div>
    </section>
    <footer id="home-footer">
        <div class="wrapper">
            <div class="up">
                <div class="box">
                    <a href="#">
                        <div class="logo">
                            <img src="img/logo.svg" />
                        </div>
                    </a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer in tristique nulla. Suspendisse mattis, dolor ut luctus convallis, arcu nibh vulputate risus, in sagittis risus erat ac sem.</p>
                </div>
                <div class="box"></div>
                <div class="box"></div>
                <div class="box"></div>
            </div>
            <div class="down">
                <p>All right reserved.</p>
                <p>Created by: <a href="http://woytek-portfolio.pl/" target="_blank">Woytek</a></p>
            </div>
        </div>
    </footer>
    <div class="scroll-up">
        <a href="#">
            <i class="fas fa-sort-up"></i>
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/cferdinandi/smooth-scroll@15.0.0/dist/smooth-scroll.polyfills.min.js"></script>
    <script src="js/home.js">
    </script>
</body>

</html>