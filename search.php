<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit();
}

if (!isset($_POST['searchresults'])) {
    header('Location: home.php');
    exit();
}

if (isset($_POST['btn-search'])) {
    // Wrote phrase by user
    $search = trim($_POST['searchresults']);
    $search = htmlentities($search, ENT_QUOTES, "utf-8");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>
        <?php echo "ProjectQ12 | Results for " . $search ?>
    </title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
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
                <a href="addauthor.php">
                    <div class="info-wrapper">
                        <i class="fas fa-user-tie"></i>
                        <span>Add author</span>
                    </div>
                </a>
            </div>
            <a href="home.php">
                <div class="logo">
                    <img src="img/logo.svg" alt="logo projectq12" />
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
            <div class="nav-down-mobile">
                <div class="wrapper" id="nav-hamburger-open">
                    <div class="belt" id="belt1"></div>
                    <div class="belt" id="belt2"></div>
                    <div class="belt" id="belt3"></div>
                </div>
            </div>
        </div>
        <div class="nav-down">
            <div class="empty-box"></div>
            <div class="wrapper">
                <a href="home.php">home</a>
                <!-- <a href="#">the latest</a> -->
                <a href="category.php?id_category=1">love</a>
                <a href="category.php?id_category=2">life</a>
                <a href="category.php?id_category=3">woman</a>
                <a href="category.php?id_category=4">man</a>
                <a href="category.php?id_category=5">god</a>
                <a href="category.php?id_category=6">sad</a>
                <a href="#">Contact</a>
            </div>
            <div class="search-bar">
                <i class="fas fa-search" id="open-search-bar"></i>
            </div>
            <div class="search" id="search-bar-box" style="display: none">
                <div class="search-wrapper">
                    <div class="search-wrapper-up">
                        <i class="fas fa-times" id="close-search-bar"></i>
                    </div>
                    <div class="search-wrapper-down">
                        <form action="search.php" method="POST">
                            <input type="text" name="searchresults" maxlength="200" placeholder="Search" required />
                            <button name="btn-search"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile menu -->
    <nav id="mobile-nav">
        <div class="nav-left"></div>
        <div class="nav-right">
            <div class="nav-up">
                <div class="logged-as">
                    <?php
                    echo "<p><b>Logged as:</b> " . $_SESSION['login'] . "</p>";
                    ?>
                </div>
                <div class="nav-down-mobile">
                    <div class="wrapper" id="nav-hamburger-close">
                        <div class="belt rotate-up" id="belt1"></div>
                        <div class="belt disappear" id="belt2"></div>
                        <div class="belt rotate-down" id="belt3"></div>
                    </div>
                </div>
            </div>
            <div class="search-bar">
                <form action="search.php" method="POST">
                    <input type="text" name="searchresults" maxlength="200" placeholder="Search" required />
                    <button name="btn-search"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="menu">
                <a href="home.php" class="active">home</a>
                <!-- <a href="#">the latest</a> -->
                <a href="category.php?id_category=1">love</a>
                <a href="category.php?id_category=2">life</a>
                <a href="category.php?id_category=3">woman</a>
                <a href="category.php?id_category=4">man</a>
                <a href="category.php?id_category=5">god</a>
                <a href="category.php?id_category=6">sad</a>
                <a href="#">Contact</a>
            </div>
            <div class="down">
                <div class="info">
                    <a href="addquote.php">
                        <div class="info-wrapper">
                            <i class="fas fa-quote-right"></i>
                            <span>Add quote</span>
                        </div>
                    </a>
                </div>
                <div class="info">
                    <a href="addauthor.php">
                        <div class="info-wrapper">
                            <i class="fas fa-user-tie"></i>
                            <span>Add author</span>
                        </div>
                    </a>
                </div>
                <?php
                echo '<div class="text-info">';
                // echo "<p><b>Login:</b> " . $_SESSION['login'] . "</p>";
                // echo "<p><b>E-mail:</b> " . $_SESSION['email'] . "</p>";
                echo '<a href="logout.php">Log out</a>';
                echo '</div>';
                ?>
            </div>
        </div>
    </nav>
    <div id="video">
        <video id="videoBG" autoplay muted loop>
            <source src="video/bgvideo3.mp4" type="video/mp4">
        </video>
        <div id="quotation">
            <h3>Quote of the day</h3>
            <img src="img/logo-red.svg" alt="logo red projectq12" />
            <h2>„If you tell the truth, you don't have to remember anything.”<span class="author"> - Mark Twain</span></h2>
        </div>
        <div class="bg-video"></div>
        <div class="arrow-down-box">
            <i class="fas fa-angle-double-down"></i>
        </div>
    </div>

    <div id="darker-screen"></div>

    <section id="searched-results">
        <div class="wrapper">
            <h3>Searched results for the phrase
                <span class="author gradient"> -
                    <?php
                    if (isset($_POST['searchresults'])) {
                        echo $search;
                    }
                    ?>
                </span>
            </h3>
        </div>
    </section>
    <section id="category-quotes">

        <!-- All results  -->
        <div class="wrapper">
            <?php
            if (isset($_POST['btn-search'])) {
                require_once "connect.php";
                try {
                    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                    if ($link->connect_errno != 0) {
                        throw new Exception(mysqli_connect_errno());
                    } else {

                        // All results
                        $allresults = 0;

                        // Result 1
                        $result = $link->query("SELECT authors.*, authors.name AS 'author_name', authors.id AS 'author_id', categories.*, categories.name AS 'name_category', quotes.*, users.* FROM quotes INNER JOIN authors ON authors.id=quotes.author_id INNER JOIN categories ON categories.id=quotes.categories_id INNER JOIN users ON users.id=quotes.user_id WHERE categories.name LIKE '%$search%' OR quotes.text_quote LIKE '%$search%' OR quotes.creation_date LIKE '%$search%'");
                        if (!$result) {
                            throw new Exception($link->error);
                        }
                        $how_many = $result->num_rows;
                        $allresults += $how_many;

                        // Result 2
                        $result = $link->query("SELECT authors.* FROM authors WHERE authors.name LIKE '%$search%' OR authors.surname LIKE '%$search%' OR authors.date_birth LIKE '%$search%' OR authors.date_death LIKE '%$search%'");
                        if (!$result) {
                            throw new Exception($link->error);
                        }
                        $how_many = $result->num_rows;
                        $allresults += $how_many;

                        // Result 3
                        $result = $link->query("SELECT users.* FROM users WHERE users.user LIKE '%$search%' OR users.creation_date LIKE '%$search%' OR users.role LIKE '%$search%'");
                        if (!$result) {
                            throw new Exception($link->error);
                        }
                        $how_many = $result->num_rows;
                        $allresults += $how_many;

                        if ($allresults > 0) {
                            if ($allresults > 1) {
                                echo '<h3 class="info">We found ' . $allresults . ' results</h3>';
                            } else {
                                echo '<h3 class="info">We found ' . $allresults . ' result</h3>';
                            }
                        } else {
                            echo "<h3 class='info'>There are no results matching your search :/</h3>";
                        }
                        $link->close();
                    }
                } catch (Exception $e) {
                    echo "Server error! Sorry :/";
                    echo "<br /> Information for the developer: " . $e;
                }
            }
            ?>
        </div>

        <!-- Found quotes -->
        <div class="wrapper">
            <?php
            if (isset($_POST['btn-search'])) {
                require_once "connect.php";
                try {
                    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                    if ($link->connect_errno != 0) {
                        throw new Exception(mysqli_connect_errno());
                    } else {
                        $result = $link->query("SELECT authors.*, authors.name AS 'author_name', authors.id AS 'author_id', categories.*, categories.name AS 'name_category', quotes.*, users.*, quotes.id AS 'quotes_id' FROM quotes INNER JOIN authors ON authors.id=quotes.author_id INNER JOIN categories ON categories.id=quotes.categories_id INNER JOIN users ON users.id=quotes.user_id WHERE categories.name LIKE '%$search%' OR quotes.text_quote LIKE '%$search%' OR quotes.creation_date LIKE '%$search%'");
                        if (!$result) {
                            throw new Exception($link->error);
                        }
                        $how_many = $result->num_rows;
                        if ($how_many > 0) {
                            if ($how_many > 1) {
                                echo '<h3 class="info">quotes</h3>';
                            } else {
                                echo '<h3 class="info">quote</h3>';
                            }
                            echo '<div class="wrapper-box">';
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="box">';
                                echo '<div class="img-box">';
                                echo '<img src="' . $row['img_quote'] . '" />';
                                echo '</div>';
                                echo '<div class="background-box"></div>';
                                echo '<a href="quote.php?quote_id=' . $row['quotes_id'] . '"><div class="text-box">';
                                if (strlen($row['text_quote']) < 50) {
                                    echo '<h2>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['author_name'] . " " . $row['surname'] . '<span></h2>';
                                } else if (strlen($row['text_quote']) < 100) {
                                    echo '<h3>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['author_name'] . " " . $row['surname'] . '<span></h3>';
                                } else if (strlen($row['text_quote']) < 225) {
                                    echo '<h4>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['author_name'] . " " . $row['surname'] . '<span></h4>';
                                } else {
                                    echo '<h5>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['author_name'] . " " . $row['surname'] . '<span></h5>';
                                }
                                echo '</div></a>';
                                echo '<div class="add-box">';
                                echo '<span class="info">Added by: <a href=user.php?id_user=' . $row['user_id'] . '>' . $row['user'] . '</a></span>';
                                echo '<span class="info">Category: <a href=category.php?id_category=' . $row['categories_id'] . '>' . $row['name_category'] . '</a></span>';
                                echo '<span class="info">Author: <a href=author.php?id_author=' . $row['author_id'] . '>' . $row['author_name'] . " " . $row['surname'] . '</a></span>';
                                echo '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        $link->close();
                    }
                } catch (Exception $e) {
                    echo "Server error! Sorry :/";
                    echo "<br /> Information for the developer: " . $e;
                }
            }
            ?>
        </div>

        <!-- Found authors  -->
        <div class="wrapper">
            <?php
            if (isset($_POST['btn-search'])) {
                require_once "connect.php";
                try {
                    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                    if ($link->connect_errno != 0) {
                        throw new Exception(mysqli_connect_errno());
                    } else {

                        $result = $link->query("SELECT authors.* FROM authors WHERE authors.name LIKE '%$search%' OR authors.surname LIKE '%$search%' OR authors.date_birth LIKE '%$search%' OR authors.date_death LIKE '%$search%'");
                        if (!$result) {
                            throw new Exception($link->error);
                        }
                        $how_many = $result->num_rows;
                        if ($how_many > 0) {
                            if ($how_many > 1) {
                                echo '<h3 class="info">authors</h3>';
                            } else {
                                echo '<h3 class="info">author</h3>';
                            }
                            echo '<div class="wrapper-box-authors">';
                            while ($row = $result->fetch_assoc()) {
                                echo '<a href="author.php?id_author=' . $row['id'] . '" class="author">';
                                echo '<div class="box">';
                                echo '<div class="img-box">';
                                echo '<img src="' . $row['img_author'] . '" alt="' . $row['name'] . " " . $row['surname'] . '" class="thumbnail"/>';
                                echo '</div>';
                                echo '<div class="background-box"></div>';
                                echo '<div class="text-box">';
                                echo '<h2><span class="gradient">' . $row['name'] . " " . $row['surname'] . '<span></h2>';
                                echo '</div>';
                                echo '</div>';
                                echo '</a>';
                            }
                            echo '</div>';
                        }
                        $link->close();
                    }
                } catch (Exception $e) {
                    echo "Server error! Sorry :/";
                    echo "<br /> Information for the developer: " . $e;
                }
            }
            ?>
        </div>

        <!-- Found users -->
        <div class="wrapper">
            <?php
            if (isset($_POST['btn-search'])) {
                require_once "connect.php";
                try {
                    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                    if ($link->connect_errno != 0) {
                        throw new Exception(mysqli_connect_errno());
                    } else {

                        $result = $link->query("SELECT users.* FROM users WHERE users.user LIKE '%$search%' OR users.creation_date LIKE '%$search%' OR users.role LIKE '%$search%'");
                        if (!$result) {
                            throw new Exception($link->error);
                        }
                        $how_many = $result->num_rows;
                        if ($how_many > 0) {
                            if ($how_many > 1) {
                                echo '<h3 class="info">users</h3>';
                            } else {
                                echo '<h3 class="info">user</h3>';
                            }
                            echo '<div class="wrapper-box-user">';
                            while ($row = $result->fetch_assoc()) {
                                echo '<a href="user.php?id_user=' . $row['id'] . '">';
                                echo '<div class="box-user">';
                                echo '<img src="' . $row['img_user'] . '" alt="user: ' . $row['user'] . '"/>';
                                echo '<h3>' . $row['user'] . '</h3>';
                                echo '</div>';
                                echo '</a>';
                            }
                            echo '</div>';
                        }
                        $link->close();
                    }
                } catch (Exception $e) {
                    echo "Server error! Sorry :/";
                    echo "<br /> Information for the developer: " . $e;
                }
            }
            ?>
        </div>
    </section>

    <section id="break-margin"></section>

    <section id="quote-img">
        <div class="quote-box">
            <?php
            require_once "connect.php";

            try {
                $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                if ($link->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $result = $link->query("SELECT quotes.*, quotes.id AS 'quote_id', categories.name AS 'category_name', authors.*, authors.id AS 'author_id' FROM quotes INNER JOIN authors ON quotes.author_id=authors.id INNER JOIN categories ON quotes.categories_id=categories.id WHERE quotes.categories_id=5 ORDER BY RAND() LIMIT 1");
                    if (!$result) {
                        throw new Exception($link->error);
                    }
                    $how_many_rand_quotes = $result->num_rows;
                    if ($how_many_rand_quotes > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<img src="' . $row['img_quote'] . '" />';
                            echo '<div class="background">';
                            echo '<div class="text">';
                            echo '<div class="category">';
                            echo '<h3>Category: <a href="category.php?id_category=' . $row['categories_id'] . '">' . $row['category_name'] . '</a></h3>';
                            echo '<h3>Author: <a href="author.php?id_author=' . $row['author_id'] . '">' . $row['name'] . " " . $row['surname'] . '</a></h3>';
                            echo '</div>';
                            if (strlen($row['text_quote']) > 400) {
                                echo '<blockquote>';
                                echo '<h4>„<a href="quote.php?quote_id=' . $row['quote_id'] . '">' . $row['text_quote'] . '”</a></h4>';
                                echo '</blockquote>';
                            } else {
                                echo '<blockquote>';
                                echo '<h3><a href="quote.php?quote_id=' . $row['quote_id'] . '">„' . $row['text_quote'] . '”</a></h3>';
                                echo '</blockquote>';
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    $link->close();
                }
            } catch (Exception $e) {
                echo "Server error! Sorry :/";
                echo "<br/> Information for the developer: " . $e;
            }

            ?>
        </div>
        <div class="quote-box">
            <?php
            require_once "connect.php";

            try {
                $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                if ($link->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $result = $link->query("SELECT quotes.*, quotes.id AS 'quote_id', categories.name AS 'category_name', authors.*, authors.id AS 'author_id' FROM quotes INNER JOIN authors ON quotes.author_id=authors.id INNER JOIN categories ON quotes.categories_id=categories.id WHERE quotes.categories_id=4 ORDER BY RAND() LIMIT 1");
                    if (!$result) {
                        throw new Exception($link->error);
                    }
                    $how_many_rand_quotes = $result->num_rows;
                    if ($how_many_rand_quotes > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<img src="' . $row['img_quote'] . '" />';
                            echo '<div class="background">';
                            echo '<div class="text">';
                            echo '<div class="category">';
                            echo '<h3>Category: <a href="category.php?id_category=' . $row['categories_id'] . '">' . $row['category_name'] . '</a></h3>';
                            echo '<h3>Author: <a href="author.php?id_author=' . $row['author_id'] . '">' . $row['name'] . " " . $row['surname'] . '</a></h3>';
                            echo '</div>';
                            if (strlen($row['text_quote']) > 400) {
                                echo '<blockquote>';
                                echo '<h4>„<a href="quote.php?quote_id=' . $row['quote_id'] . '">' . $row['text_quote'] . '”</a></h4>';
                                echo '</blockquote>';
                            } else {
                                echo '<blockquote>';
                                echo '<h3><a href="quote.php?quote_id=' . $row['quote_id'] . '">„' . $row['text_quote'] . '”</a></h3>';
                                echo '</blockquote>';
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    $link->close();
                }
            } catch (Exception $e) {
                echo "Server error! Sorry :/";
                echo "<br/> Information for the developer: " . $e;
            }
            ?>
        </div>
        <div class="quote-box">
            <?php
            require_once "connect.php";

            try {
                $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                if ($link->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $result = $link->query("SELECT quotes.*, quotes.id AS 'quote_id', categories.name AS 'category_name', authors.*, authors.id AS 'author_id' FROM quotes INNER JOIN authors ON quotes.author_id=authors.id INNER JOIN categories ON quotes.categories_id=categories.id WHERE quotes.categories_id=3 ORDER BY RAND() LIMIT 1");
                    if (!$result) {
                        throw new Exception($link->error);
                    }
                    $how_many_rand_quotes = $result->num_rows;
                    if ($how_many_rand_quotes > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<img src="' . $row['img_quote'] . '" />';
                            echo '<div class="background">';
                            echo '<div class="text">';
                            echo '<div class="category">';
                            echo '<h3>Category: <a href="category.php?id_category=' . $row['categories_id'] . '">' . $row['category_name'] . '</a></h3>';
                            echo '<h3>Author: <a href="author.php?id_author=' . $row['author_id'] . '">' . $row['name'] . " " . $row['surname'] . '</a></h3>';
                            echo '</div>';
                            if (strlen($row['text_quote']) > 400) {
                                echo '<blockquote>';
                                echo '<h4>„<a href="quote.php?quote_id=' . $row['quote_id'] . '">' . $row['text_quote'] . '”</a></h4>';
                                echo '</blockquote>';
                            } else {
                                echo '<blockquote>';
                                echo '<h3><a href="quote.php?quote_id=' . $row['quote_id'] . '">„' . $row['text_quote'] . '”</a></h3>';
                                echo '</blockquote>';
                            }
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    $link->close();
                }
            } catch (Exception $e) {
                echo "Server error! Sorry :/";
                echo "<br/> Information for the developer: " . $e;
            }
            ?>
        </div>
    </section>
    <footer id="home-footer">
        <div class="wrapper">
            <div class="up">
                <div class="box">
                    <a href="home.php">
                        <div class="logo">
                            <img src="img/logo.svg" alt="logo projectq12" />
                        </div>
                    </a>
                    <h3 class='social grey'>Social media</h3>
                    <div class="social-wrapper">
                        <a href="#" target="_blank" rel="noopener">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" target="_blank" rel="noopener">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" target="_blank" rel="noopener">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <div class="box">
                    <h3 class="social grey">Quick menu</h3>
                    <a href="home.php" class="active">home</a>
                    <!-- <a href="#">the latest</a> -->
                    <a href="category.php?id_category=1">love</a>
                    <a href="category.php?id_category=2">life</a>
                    <a href="category.php?id_category=3">woman</a>
                    <a href="category.php?id_category=4">man</a>
                    <a href="category.php?id_category=5">god</a>
                    <a href="category.php?id_category=6">sad</a>
                    <a href="#">Contact</a>
                </div>
                <div class="box">
                    <h3 class="social grey">Contact</h3>
                    <a href="tel:+48332222223">TEL:. +48 332 222 223</a>
                    <a href="mailto:projectq12@gmail.com">EMAIL:. projectq12@gmail.com</a>
                </div>
            </div>
            <div class="down">
                <p>All right reserved by <a href="home.php">ProjectQ12</a></p>
                <p>Created by: <a href="http://woytek-portfolio.pl/" target="_blank" rel="noopener">Woytek</a></p>
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
    <script src="js/searchbar.js"></script>
</body>

</html>