// SCRIPT RIPPLES
// const buttons = document.querySelectorAll(".btn");
//     buttons.forEach(btn => {
//         btn.addEventListener("click", function(e) {
//             let x = e.clientX - e.target.offsetLeft;
//             let y = e.clientY - e.target.offsetTop;

//             let ripples = document.createElement('span');
//             ripples.classList.add('ripples');
//             ripples.style.left = x + 'px';
//             ripples.style.top = y + 'px';
//             this.appendChild(ripples);
//             setTimeout(() => {
//                 ripples.remove();
//         }, 1000)
//     })
// });


// SCRIPT SHOW EYE AND PASSWORD
// document.addEventListener("DOMContentLoaded", function () {
//     let inputPasswords = document.querySelectorAll(".password");
//     let eyes = document.querySelectorAll(".fa-eye");
//     let eyestwo = document.querySelectorAll(".fa-eye-slash");
//     let state = true;

//     for (let i = 0; i < inputPasswords.length; i++) {
//         inputPasswords[i].addEventListener("keydown", function () {
//             eyes[i].style.top = "calc(50% - 10px)";
//             eyestwo[i].style.top = "calc(50% - 10px)";
//             let lengthInput = inputPasswords[i].value.length;
//             if (lengthInput > 2) {
//                 if (state) {
//                     eyes[i].addEventListener("click", function () {
//                         eyestwo[i].style.display = "block";
//                         eyes[i].style.display = "none";
//                         inputPasswords[i].setAttribute("type", "text");
//                         state = false;
//                     });
//                     eyes[i].style.display = "block";
//                     eyestwo[i].style.display = "none";
//                 } else {
//                     eyestwo[i].addEventListener("click", function () {
//                         eyestwo[i].style.display = "none";
//                         eyes[i].style.display = "block";
//                         inputPasswords[i].setAttribute("type", "password");
//                         state = true;
//                     });
//                     eyes[i].style.display = "none";
//                     eyestwo[i].style.display = "block";
//                 }
//             } else {
//                 eyes[i].style.display = "none";
//                 eyestwo[i].style.display = "none";
//                 inputPasswords[i].setAttribute("type", "password");
//                 state = true;
//             }
//         });
//     }
// })

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