// SHOW&HIDE PASSWORD
let inputPassword = document.querySelectorAll(".password");
let eye = document.querySelectorAll(".fa-eye");
let eyeSlash = document.querySelectorAll(".fa-eye-slash");

eye[0].addEventListener("click", function () {
    inputPassword[0].setAttribute("type", "text");
    eye[0].style.display = "none";
    eyeSlash[0].style.display = "block";
    eye[0].style.top = "calc(50% - 10px)";
    eyeSlash[0].style.top = "calc(50% - 10px)";
});

eyeSlash[0].addEventListener("click", function () {
    inputPassword[0].setAttribute("type", "password");
    eye[0].style.display = "block";
    eyeSlash[0].style.display = "none";
    eye[0].style.top = "calc(50% - 10px)";
    eyeSlash[0].style.top = "calc(50% - 10px)";
});

if (inputPassword.length > 1) {
    eye[1].addEventListener("click", function () {
        inputPassword[1].setAttribute("type", "text");
        eye[1].style.display = "none";
        eyeSlash[1].style.display = "block";
        eye[1].style.top = "calc(50% - 10px)";
        eyeSlash[1].style.top = "calc(50% - 10px)";
    });

    eyeSlash[1].addEventListener("click", function () {
        inputPassword[1].setAttribute("type", "password");
        eye[1].style.display = "block";
        eyeSlash[1].style.display = "none";
        eye[1].style.top = "calc(50% - 10px)";
        eyeSlash[1].style.top = "calc(50% - 10px)";
    });
}

//ERROR INTO
let errorInfo = document.querySelectorAll(".error");
let tag = document.querySelectorAll(".info-error");
for (let i = 0; i < errorInfo.length; i++) {
    errorInfo[i].addEventListener("mouseover", function () {
        tag[i].style.display = "block";
    });

    errorInfo[i].addEventListener("mouseout", function () {
        tag[i].style.display = "none";
    });

    errorInfo[i].addEventListener("click", function (e) {
        errorInfo[i].style.display = "none";
    });
}