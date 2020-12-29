<?php
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['name-of-author'])) {
    // Validation
    $author_OK = true;

    // Checking name of author
    $name_of_author = trim($_POST['name-of-author']);
    if (strlen($name_of_author) < 3 || (strlen($name_of_author) >= 20)) {
        $author_OK = false;
        $_SESSION['e_author_name'] = "The name cannot be less than 3 or more than 20 characters";
    }

    if (!ctype_alpha($name_of_author)) {
        $author_OK = false;
        $_SESSION['e_author_name'] = "The name can only consist of letters";
    }

    // Checking surname of author
    $surname_of_author = trim($_POST['surname-of-author']);
    if (strlen($surname_of_author) < 3 || (strlen($surname_of_author) >= 30)) {
        $author_OK = false;
        $_SESSION['e_author_surname'] = "Surname can not be less than 3 or more than 30 characters";
    }

    if (!ctype_alpha($surname_of_author)) {
        $author_OK = false;
        $_SESSION['e_author_surname'] = "The name can only consist of letters";
    }


    // Checking exsit of img author
    if (empty($_FILES['img-of-author']['name'])) {
        $author_OK = false;
        $_SESSION['e_img_of_author'] = "You didn't select a photo";
    } else if ($_FILES['img-of-author']['size'] > 2097152) {
        $author_OK = false;
        $_SESSION['e_img_of_author'] = "The size of the photo is greater than 2 MB. Please choose another photo smaller than 2MB";
    }

    // Checking extension of file
    if ($_FILES['img-of-author']['name']) {
        $allowed = array('gif', 'png', 'jpg', 'jpeg');
        $filename = $_FILES['img-of-author']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            $author_OK = false;
            $_SESSION['e_img_of_author'] = "The selected file extension is not allowed on this site. Allowed extensions are jpg, jpeg, png and gif";
        }
    }

    // Remember data
    $_SESSION['fr_author_name'] = $name_of_author;
    $_SESSION['fr_author_surname'] = $surname_of_author;

    require_once "connect.php";
    try {
        $link = new mysqli($db_server, $db_login, $db_password, $db_name);
        if ($link->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            $name_of_author = htmlentities($name_of_author, ENT_QUOTES, "utf-8");
            $surname_of_author = htmlentities($surname_of_author, ENT_QUOTES, "utf-8");

            // Author exist
            $result = $link->query("SELECT CONCAT(COALESCE(name,''),' ',COALESCE(surname,'')) AS whole_name FROM authors WHERE name='$name_of_author' AND surname='$surname_of_author'");
            if (!$result) {
                throw new Exception($link->error);
            }
            $how_many_author = $result->num_rows;
            if ($how_many_author > 0) {
                $author_OK = false;
                $_SESSION['e_author_name'] = "The author already exists in our database";
            }

            // Img author exist
            $path_author_img = "img/authors/" . $_SESSION['login'] . "/" . $_FILES['img-of-author']['name'];
            $result = $link->query("SELECT id FROM authors WHERE img_author='$path_author_img'");
            if (!$result) {
                throw new Exception($link->error);
            }
            $how_many_img = $result->num_rows;
            if ($how_many_img > 0) {
                $author_OK = false;
                $_SESSION['e_img_of_author'] = "The name of the photo you selected already exists in our database. Make sure you don't want to add the same photo or rename the photo";
            }

            if ($author_OK == true) {
                // $author_id = $_POST['select-author'];
                // $category_id = $_POST['select-category'];
                $login = $_SESSION['login'];
                if ($result = $link->query("SELECT * FROM users WHERE user='$login'")) {
                    $row = $result->num_rows;
                    if ($row > 0) {
                        while ($cell = $result->fetch_assoc()) {
                            $login_id = $cell['id'];
                        }
                    }
                }

                // Adding img of author
                $image = $_FILES['img-of-author']['name'];
                if (!is_dir("img/authors/$login")) {
                    mkdir("img/authors/$login", 0777);
                }
                $target = "img/authors/" . $login . "/" . basename($image);
                move_uploaded_file($_FILES['img-of-author']['tmp_name'], $target);


                // Checking dates
                if (isset($_POST['born-date'])) {
                    $bornDate = $_POST['born-date'];
                    $convertDate = strtotime($bornDate);
                    $bornDate = date('Y-m-d', $convertDate);
                    $bornDateChar = "'$bornDate'";
                } else {
                    $bornDateChar = "NULL";
                }

                if (isset($_POST['death-date'])) {
                    $deathDate = $_POST['death-date'];
                    $convertDate = strtotime($deathDate);
                    $deathDate = date('Y-m-d', $convertDate);
                    $deathDateChar = "'$deathDate'";
                } else {
                    $deathDateChar = "NULL";
                }

                // Adding to database author
                if ($link->query("INSERT INTO authors(name, surname, date_birth, date_death, img_author) VALUES('$name_of_author', '$surname_of_author', $bornDateChar, $deathDateChar, '$target')")) {
                    $_SESSION['successful_author_add'] = true;
                    $_SESSION['name_author'] = $name_of_author;
                    $_SESSION['surname_author'] = $surname_of_author;
                    $_SESSION['born_date'] = $_POST['born-date'];
                    $_SESSION['death_date'] = $_POST['death-date'];
                    $_SESSION['img_author'] = $target;
                    header('Location: authoradded.php');
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
    <title>ProjectQ12 | Add author</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="Shortcut icon" href="img/logo.svg" />
    <link rel="stylesheet" href="css/datedropper.css" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="dist/select.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="dist/select.min.js"></script>
    <script type="text/javascript" src="js/datedropper.js"></script>
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
            <source src="video/bgvideo.mp4" type="video/mp4">
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
            <h3>„A writer is someone for whom writing is more difficult than it is for other people.”<span class="author gradient"> - Thomas Mann</span></h3>
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
                <h3>Here you can add an author <img src="img/logo-red.svg" /></h3>
                <form method="POST" enctype="multipart/form-data">

                    <!-- Name of author -->
                    <div class="wrapper-name-author">
                        <div class="box-name-author">
                            <input type="text" name="name-of-author" placeholder="Name of author" value="<?php
                                                                                                            if (isset($_SESSION['fr_author_name'])) {
                                                                                                                echo $_SESSION['fr_author_name'];
                                                                                                                unset($_SESSION['fr_author_name']);
                                                                                                            }
                                                                                                            ?>" required />
                            <label for="name-of-author"></label>
                        </div>
                        <?php
                        if (isset($_SESSION['e_author_name'])) {
                            echo "<h4>";
                            echo $_SESSION['e_author_name'];
                            unset($_SESSION['e_author_name']);
                            echo "</h4>";
                        }
                        ?>
                    </div>

                    <!-- Surname of author -->
                    <div class="wrapper-surname-author">
                        <div class="box-surname-author">
                            <input type="text" name="surname-of-author" placeholder="Surname of author" value="<?php
                                                                                                                if (isset($_SESSION['fr_author_surname'])) {
                                                                                                                    echo $_SESSION['fr_author_surname'];
                                                                                                                    unset($_SESSION['fr_author_surname']);
                                                                                                                }
                                                                                                                ?>" required />
                            <label for="surname-of-author"></label>
                        </div>
                        <?php
                        if (isset($_SESSION['e_author_surname'])) {
                            echo "<h4>";
                            echo $_SESSION['e_author_surname'];
                            unset($_SESSION['e_author_surname']);
                            echo "</h4>";
                        }
                        ?>
                    </div>

                    <!-- Add born-date -->
                    <div class="wrapper-button">
                        <div class="box-button">
                            <button id="add-date-born">Add born date</button>
                        </div>
                    </div>

                    <!-- Add author born-date -->
                    <div class="wrapper-born-date" id="date-born-wrapper">
                        <div class="box-born-date">
                            <span>Date of born: </span>
                            <div class="center-input">
                                <input type="text" id="date-born" name="born-date" data-default-date="01-01-1970" data-large-mode="true" data-min-year="1000" data-format="S F, Y" disabled="disabled" />
                            </div>
                        </div>
                    </div>

                    <!-- Add death-date -->
                    <div class="wrapper-button">
                        <div class="box-button">
                            <button id="add-date-death">Add death date</button>
                        </div>
                    </div>

                    <!-- Add author death-date -->
                    <div class="wrapper-death-date" id="date-death-wrapper">
                        <div class="box-death-date">
                            <span>Date of death: </span>
                            <div class="center-input">
                                <input type="text" id="date-death" name="death-date" data-default-date="01-01-2000" data-large-mode="true" data-min-year="1000" data-format="S F, Y" disabled="disabled" />
                            </div>
                        </div>
                    </div>

                    <!-- Img of author -->
                    <div class="wrapper-img-author">
                        <div class="box-img-author">
                            <span>Img of the quote: </span>
                            <input type="file" id="img-author" name="img-of-author" accept="image/png, image/jpeg" />
                            <label for="img-author">
                                <span id="text-img-quote">Choose The Img</span>
                                <i class="fas fa-cloud-upload-alt"></i>
                            </label>
                        </div>
                        <h4 id='error-img'>
                            <?php
                            if (isset($_SESSION['e_img_of_author'])) {
                                echo $_SESSION['e_img_of_author'];
                                unset($_SESSION['e_img_of_author']);
                            }
                            ?>
                        </h4>
                    </div>
                    <input type="submit" value="Add author" name="add-author" />
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
        // IMG QUOTE ADD
        const imgInput = document.querySelector("#img-author");
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
    <script>
        $('#date-born').dateDropper();
        $('#date-death').dateDropper();
    </script>

    <!-- Date -->
    <script>
        // Hide elements
        $('#date-born-wrapper').hide();
        $('#date-death-wrapper').hide();
        $('#add-date-death').hide();

        $('#add-date-born').click(function(event) {
            event.preventDefault();
            $('#date-born-wrapper').slideToggle("slow", function() {})
            if ($('#add-date-born').html() == "Add born date") {
                $('#add-date-born').html('Remove born date');
                $('#date-born').attr("disabled", false);
            } else {
                $('#add-date-born').html('Add born date');
                $('#date-death-wrapper').slideUp();
                $('#date-born').attr("disabled", true);
            }
            $('#add-date-death').html('Add death date');
            $('#add-date-death').slideToggle();
            $('#date-death').attr("disabled", true);
        })

        $('#add-date-death').click(function(event) {
            event.preventDefault();
            $('#date-death-wrapper').slideToggle("slow", function() {})
            if ($('#add-date-death').html() == "Add death date") {
                $('#add-date-death').html('Remove death date');
                $('#date-death').attr("disabled", false);
            } else {
                $('#add-date-death').html('Add death date');
                $('#date-death').attr("disabled", true);
            }
        })
    </script>
    <script src="js/searchbar.js"></script>

</body>

</html>