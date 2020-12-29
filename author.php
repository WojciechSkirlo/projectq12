<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit();
}

require_once "connect.php";
try {
    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
    if ($link->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        $author_id = $_GET['id_author'];
        $result = $link->query("SELECT * FROM authors WHERE authors.id='$author_id'");
        if (!$result) {
            throw new Exception($link->error);
        }
        $how_many = $result->num_rows;
        if ($how_many > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['author_whole_name'] = ucwords($row['name'] . " " . $row['surname']);
                $whole_name = $_SESSION['author_whole_name'];
                $_SESSION['author_name'] = ucwords($row['name']);
                $_SESSION['author_surname'] = ucwords($row['surname']);

                $bornDateGood = $row['date_birth'];
                $bornDate = $row['date_birth'];
                $convertDate = strtotime($bornDate);
                $bornDate = date('jS F, Y', $convertDate);
                $_SESSION['author_date_born'] = $bornDate;

                $deathDateGood = $row['date_death'];
                $deathDate = $row['date_death'];
                $convertDate = strtotime($deathDate);
                $deathDate = date('jS F, Y', $convertDate);

                $_SESSION['author_date_death'] = $deathDate;
                $_SESSION['author_img'] = $row['img_author'];
            }
        }

        $result = $link->query("SELECT COUNT(quotes.user_id) as 'quantity', CONCAT(COALESCE(authors.name,''),' ',COALESCE(authors.surname,'')) AS 'whole_name' FROM quotes INNER JOIN authors ON quotes.author_id=authors.id GROUP BY authors.name HAVING whole_name='$whole_name'");
        if (!$result) {
            throw new Exception($link->error);
        }
        $how_many = $result->num_rows;
        if ($how_many > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['quantity_of_quotes'] = $row['quantity'];
            }
        }
        $link->close();
    }
} catch (Exception $e) {
    echo "Server error! Sorry :/";
    echo "<br/> Information for the developer: " . $e;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>
        <?php echo "ProjectQ12 | Author - " . $_SESSION['author_whole_name'] ?>
    </title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="Shortcut icon" href="img/logo.svg" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
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
            <div class="empty-box"></div>
            <div class="wrapper">
                <a href="home.php">home</a>
                <!-- <a href="#">the latest</a> -->
                <a href="category.php?id_category=1" class="
                <?php
                if ($categories_id == 1) {
                    echo "active";
                }
                ?>
                ">love</a>
                <a href="category.php?id_category=2" class="
                <?php
                if ($categories_id == 2) {
                    echo "active";
                }
                ?>
                ">life</a>
                <a href="category.php?id_category=3" class="
                <?php
                if ($categories_id == 3) {
                    echo "active";
                }
                ?>
                ">woman</a>
                <a href="category.php?id_category=4" class="
                <?php
                if ($categories_id == 4) {
                    echo "active";
                }
                ?>
                ">man</a>
                <a href="category.php?id_category=5" class="
                <?php
                if ($categories_id == 5) {
                    echo "active";
                }
                ?>
                ">god</a>
                <a href="category.php?id_category=6" class="
                <?php
                if ($categories_id == 6) {
                    echo "active";
                }
                ?>
                ">sad</a>
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
                            <input type="text" name="searchresults" maxlength="200" placeholder="Search" />
                            <button name="btn-search"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
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
    <section id="home-category">
        <div class="wrapper">
            <h3 class="category">Author<span class="author gradient"> -
                    <?php
                    echo $_SESSION['author_whole_name'];
                    ?>
                </span>
            </h3>
            <img src="img/logo.svg" />
        </div>
    </section>
    <!-- Section category quotes -->
    <section id="category-quotes">
        <div class="wrapper">
            <div class="box-info">
                <div class="box-left">
                    <?php
                    echo "<h3>Name: <span class='gradient'>" . $_SESSION['author_name'] . "</span></h3>";
                    if (!empty($_SESSION['author_surname'])) {
                        echo "<h3>Surname: <span class='gradient'>" . $_SESSION['author_surname'] . "</span></h3>";
                    }
                    if (!empty($bornDateGood)) {
                        echo "<h4>Date of born: <span class='gradient'>" . $_SESSION['author_date_born'] . "</span></h4>";
                    }
                    if (!empty($deathDateGood)) {
                        echo "<h4>Date of death: <span class='gradient'>" . $_SESSION['author_date_death'] . "</span></h4>";
                    }
                    echo "<h3>Number of quotes on website:<span class='numbers'>" . $_SESSION['quantity_of_quotes'] . "</span></h3>";
                    ?>
                </div>
                <div class="box-right">
                    <?php
                    echo '<img src="' . $_SESSION['author_img'] . '"/>';
                    ?>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <?php
            require_once "connect.php";
            try {
                $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                if ($link->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $results_per_page = 7;

                    $result = $link->query("SELECT id FROM quotes WHERE author_id='$author_id'");
                    $how_many_quote = $result->num_rows;

                    $number_of_pages = ceil($how_many_quote / $results_per_page);

                    if (!isset($_GET['page'])) {
                        $page = 1;
                    } else {
                        $page = $_GET['page'];
                    }
                    $this_page_first_result = ($page - 1) * $results_per_page;

                    $result = $link->query("SELECT quotes.*, authors.*, categories.name AS 'name_category', users.* FROM quotes INNER JOIN categories ON quotes.categories_id=categories.id INNER JOIN authors ON quotes.author_id=authors.id INNER JOIN users ON quotes.user_id=users.id WHERE quotes.author_id='$author_id' ORDER BY quotes.creation_date DESC LIMIT " . $this_page_first_result . ', ' . $results_per_page);
                    if (!$result) {
                        throw new Exception($link->error);
                    }
                    $how_many = $result->num_rows;
                    if ($how_many > 0) {
                        echo '<div class="wrapper-box">';
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="box">';
                            echo '<div class="img-box">';
                            echo '<img src="' . $row['img_quote'] . '" />';
                            echo '</div>';
                            echo '<div class="background-box"></div>';
                            echo '<div class="text-box">';
                            if (strlen($row['text_quote']) < 50) {
                                echo '<h2>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['name'] . " " . $row['surname'] . '<span></h2>';
                            } else if (strlen($row['text_quote']) < 100) {
                                echo '<h3>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['name'] . " " . $row['surname'] . '<span></h3>';
                            } else if (strlen($row['text_quote']) < 225) {
                                echo '<h4>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['name'] . " " . $row['surname'] . '<span></h4>';
                            } else {
                                echo '<h5>„' . $row['text_quote'] . '” - <span class="gradient">' . $row['name'] . " " . $row['surname'] . '<span></h5>';
                            }
                            echo '</div>';
                            echo '<div class="add-box">';
                            echo '<span class="info">Added by: <a href="#">' . $row['user'] . '</a></span>';
                            echo '<span class="info">Category: <a href=category.php?id_category=' . $row['categories_id'] . '>' . $row['name_category'] . '</a></span>';
                            echo '<span class="info">Author: <a href=author.php?id_author=' . $row['author_id'] . '>' . $row['name'] . " " . $row['surname'] . '</a></span>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
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
    <?php
    if ($number_of_pages > 1) {
        echo '<div id="pagination">';
        for ($page = 1; $page <= $number_of_pages; $page++) {
            if ($number_of_pages != 1) {
                echo '<div class="pagination-page"><a href="author.php?id_author=' . $author_id . '&page=' . $page . '" class="';
                if ($page == $_GET['page']) {
                    echo "active";
                }
                echo '">' . $page . '</a></div>';
            }
        }
        echo '</div>';
    }
    ?>
    <section id="authors">
        <div class="wrapper">
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
            <div class="box"></div>
        </div>
    </section>
    <section id="quote-img">
        <div class="quote-box">
            <?php
            require_once "connect.php";

            try {
                $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                if ($link->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $result = $link->query("SELECT quotes.*, categories.name AS 'category_name', authors.*, authors.id AS 'author_id' FROM quotes INNER JOIN authors ON quotes.author_id=authors.id INNER JOIN categories ON quotes.categories_id=categories.id WHERE quotes.categories_id=2 ORDER BY RAND() LIMIT 1");
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
                                echo '<h4>„' . $row['text_quote'] . '”</h4>';
                            } else {
                                echo '<h3>„' . $row['text_quote'] . '”</h3>';
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
                    $result = $link->query("SELECT quotes.*, categories.name AS 'category_name', authors.*, authors.id AS 'author_id' FROM quotes INNER JOIN authors ON quotes.author_id=authors.id INNER JOIN categories ON quotes.categories_id=categories.id WHERE quotes.categories_id=1 ORDER BY RAND() LIMIT 1");
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
                                echo '<h4>„' . $row['text_quote'] . '”</h4>';
                            } else {
                                echo '<h3>„' . $row['text_quote'] . '”</h3>';
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
                    $result = $link->query("SELECT quotes.*, categories.name AS 'category_name', authors.*, authors.id AS 'author_id' FROM quotes INNER JOIN authors ON quotes.author_id=authors.id INNER JOIN categories ON quotes.categories_id=categories.id WHERE quotes.categories_id=6 ORDER BY RAND() LIMIT 1");
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
                                echo '<h4>„' . $row['text_quote'] . '”</h4>';
                            } else {
                                echo '<h3>„' . $row['text_quote'] . '”</h3>';
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
                            <img src="img/logo.svg" />
                        </div>
                    </a>
                    <h3>Social media</h3>
                    <div class="social-wrapper">
                        <a href="" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="" target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <div class="box">
                    <h3>Quick menu</h3>
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
                    <h3>Contact</h3>
                    <a href="tel:+48332222223">TEL:. +48 332 222 223</a>
                    <a href="mailto:projectq12@gmail.com">EMAIL:. projectq12@gmail.com</a>
                </div>
            </div>
            <div class="down">
                <p>All right reserved by <a href="home.php">ProjectQ12</a></p>
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
    <script src="js/home.js"></script>
    <script src="js/searchbar.js"></script>
</body>

</html>