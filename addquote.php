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
    $text_quote = trim($_POST['quote-text']);
    if (strlen($text_quote) <= 8 || (strlen($text_quote) >= 512)) {
        $quote_OK = false;
        $_SESSION['e_quote_text'] = "The text of the quote cannot be less than 8 and more than 512 characters long";
    }

    // Checking category of quote 
    if (!isset($_POST['select-category'])) {
        $quote_OK = false;
        $_SESSION['e_category_quote'] = "You have not selected a category";
    }

    // Checking author of quote 
    if (!isset($_POST['select-author'])) {
        $quote_OK = false;
        $_SESSION['e_author_quote'] = "You didn't choose an author";
    }

    // Checking img of quote
    if (empty($_FILES['img-quote']['name'])) {
        $quote_OK = false;
        $_SESSION['e_img_quote'] = "You didn't select a photo";
    } else if ($_FILES['img-quote']['size'] > 2097152) {
        $quote_OK = false;
        $_SESSION['e_img_quote'] = "The size of the photo is greater than 2 MB. Please choose another photo smaller than 2MB";
    }

    // Checking extension of file
    if ($_FILES['img-quote']['name']) {
        $allowed = array('gif', 'png', 'jpg', 'jpeg');
        $filename = $_FILES['img-quote']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            $quote_OK = false;
            $_SESSION['e_img_quote'] = "The selected file extension is not allowed on this site. Allowed extensions are jpg, jpeg, png and gif";
        }
    }

    //Remember data
    $_SESSION['fr_quote_text'] = $text_quote;

    require_once "connect.php";
    try {
        $link = new mysqli($db_server, $db_login, $db_password, $db_name);
        if ($link->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

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

            // Img exist
            $path_quote_img = "img/quotes/" . $_SESSION['login'] . "/" . $_FILES['img-quote']['name'];
            $result = $link->query("SELECT id FROM quotes WHERE img_quote='$path_quote_img'");
            if (!$result) {
                throw new Exception($link->error);
            }
            $how_many_img = $result->num_rows;
            if ($how_many_img > 0) {
                $quote_OK = false;
                $_SESSION['e_img_quote'] = "The name of the photo you selected already exists in our database. Make sure you don't want to add the same photo or rename the photo";
            }

            if ($quote_OK == true) {
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

                // Adding img of quote
                $image = $_FILES['img-quote']['name'];
                if (!is_dir("img/quotes/$login")) {
                    mkdir("img/quotes/$login", 0777);
                }
                $target = "img/quotes/" . $login . "/" . basename($image);
                // $link->query("INSERT INTO quotes (img_quote) VALUES ('$target')");
                move_uploaded_file($_FILES['img-quote']['tmp_name'], $target);

                $img_quote = $target;

                if ($link->query("INSERT INTO quotes(text_quote, author_id, user_id, categories_id, img_quote) VALUES('$text_quote_converted', '$author_id', '$login_id', '$category_id', '$img_quote')")) {
                    $_SESSION['successful_quote_add'] = true;
                    $_SESSION['text_quote'] = $text_quote_converted;
                    $_SESSION['author_of_quote'] = $author_id;
                    $_SESSION['category_quote'] = $category_id;
                    header('Location: quoteadded.php');
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
    <link rel="stylesheet" href="dist/select.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="dist/select.min.js"></script>
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
    <section id="home">
        <div class="wrapper">
            <img src="img/logo.svg" />
            <h3>„The secret of change is to focus all of your energy not on fighting the old, but on building the new”<span class="author gradient"> - Socrates</span></h3>
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
                <form method="POST" enctype="multipart/form-data">
                    <p><img src="img/logo.svg" /></p>
                    <div class="quote-box">
                        <textarea name="quote-text" maxlength="512" placeholder="Text of the quote" required><?php if (isset($_SESSION['fr_quote_text'])) {
                                                                                                                    echo $_SESSION['fr_quote_text'];
                                                                                                                    unset($_SESSION['fr_quote_text']);
                                                                                                                } ?></textarea>
                        <label for="quote-text" class="label-text"></label>
                    </div>
                    <p><img src="img/logo.svg" /></p>
                    <h4 class="e-quote-text">
                        <?php
                        if (isset($_SESSION['e_quote_text'])) {
                            echo $_SESSION['e_quote_text'];
                            unset($_SESSION['e_quote_text']);
                        }
                        ?>
                    </h4>
                    <div class="wrapper-category">
                        <div class="box-category">
                            <span>Category: </span>
                            <select name="select-category" id="category-id">
                                <option value="" disabled selected hidden>Enter the category</option>
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
                        <h4>
                            <?php
                            if (isset($_SESSION['e_category_quote'])) {
                                echo $_SESSION['e_category_quote'];
                                unset($_SESSION['e_category_quote']);
                            }
                            ?>
                        </h4>
                    </div>
                    <div class="author-wrapper">
                        <div class="box-author">
                            <span>Author of the quote: </span>
                            <select name="select-author" id="author-id">
                                <option value="" disabled selected hidden>Enter the author</option>
                                <?php
                                require_once "connect.php";

                                try {
                                    $link = new mysqli($db_server, $db_login, $db_password, $db_name);
                                    if ($link->connect_errno != 0) {
                                        throw new Exception(mysqli_connect_errno());
                                    } else {
                                        $result = $link->query("SELECT * FROM authors ORDER BY name");
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
                        <h4>
                            <?php
                            if (isset($_SESSION['e_author_quote'])) {
                                echo $_SESSION['e_author_quote'];
                                unset($_SESSION['e_author_quote']);
                            }
                            ?>
                        </h4>
                        <p class="author-add">If you can't find the author, you can add him, but make sure that he hasn't already been added.</p>
                        <a href="addauthor.php">Add author</a>
                    </div>
                    <div class="img-wrapper">
                        <div class="box-quote-img">
                            <span>Img of the quote: </span>
                            <input type="file" name="img-quote" id="img-quote" accept="image/png, image/jpeg" />
                            <label for="img-quote">
                                <span id="text-img-quote">Choose The Img</span>
                                <i class="fas fa-cloud-upload-alt"></i>
                            </label>
                        </div>
                        <h4 id="error-img">
                            <?php
                            if (isset($_SESSION['e_img_quote'])) {
                                echo $_SESSION['e_img_quote'];
                                unset($_SESSION['e_img_quote']);
                            }
                            ?>
                        </h4>
                    </div>
                    <input type="submit" value="Add quote" name="add-quote" />
                </form>
            </div>
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
    <script>
        // Category
        var mySelect = new Select('#category-id', {
            // auto show the live filter
            filtered: 'auto',
            // auto show the live filter when the options >= 8
            filter_threshold: 5,
            // custom placeholder
            filter_placeholder: 'Enter The Category'
        });
        // Author
        var mySelect = new Select('#author-id', {
            filtered: 'auto',
            filter_threshold: 5,
            filter_placeholder: 'Enter The Author'

        });

        // IMG QUOTE ADD
        const imgInput = document.querySelector("#img-quote");
        imgInput.addEventListener("change", function() {
            let errorSize = document.querySelector("#error-img");
            const imgText = document.querySelector("#text-img-quote");
            if (imgInput.value == "") {
                imgText.textContent = "Choose The Img";
                errorSize.textContent = `You have not selected a photo`;
            } else {
                let text = imgInput.value.split('\\');
                let sizeofIMGString = `[${((imgInput.files[0].size)/1024/1024).toFixed(2)} MB]`;
                let sizeofIMGFloat = ((imgInput.files[0].size) / 1024 / 1024).toFixed(2);
                text = text[text.length - 1] + " " + sizeofIMGString;
                imgText.textContent = text;
                if (sizeofIMGFloat > 2) {
                    errorSize.textContent = `The size of the photo is greater than 2 MB. Please choose another photo smaller than 2MB`;
                } else {
                    errorSize.textContent = ``;
                }
            }
        })
    </script>
    <script src="js/searchbar.js"></script>
</body>

</html>