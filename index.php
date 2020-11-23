<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ProjectQ12 | Login</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
</head>

<body>
    <main>
        <section id="left-container">
            <div class="sign-up-link">
                <h2>New here?</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla blandit, turpis sit amet vestibulum suscipit, urna nunc interdum felis, pulvinar laoreet lacus velit eu velit.</p>
                <div class="btn-sign-up">
                    <a href="#" class="btn">Sign up
                        <span style="left: 243px; top: 685px;"></span>
                    </a>
                </div>
            </div>
        </section>
        <section id="right-container">
            <div class="sign-in">
                <h2>Sign <span>in</span></h2>
                <form action="" method="POST" class="sign-in-form">
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="login" placeholder="Login" />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" placeholder="Password" />
                    </div>
                    <input type="submit" value="login" class="btn" />
                </form>
            </div>
        </section>
    </main>

    <script type="text/javascript">
        const buttons = document.querySelectorAll(".btn");
        buttons.forEach(btn => {
            btn.addEventListener("click", function(e) {
                let x = e.clientX - e.target.offsetLeft;
                let y = e.clientY - e.target.offsetTop;

                let ripples = document.createElement('span');
                ripples.classList.add('ripples');
                ripples.style.left = x + 'px';
                ripples.style.top = y + 'px';
                this.appendChild(ripples);
                setTimeout(() => {
                    ripples.remove();
                }, 1000)
            })
        });
    </script>
</body>

</html>