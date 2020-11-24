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
let inputPasswords = document.querySelectorAll("input[type='password']");
let eyes = document.querySelectorAll(".fa-eye");
let eyestwo = document.querySelectorAll(".fa-eye-slash");

for (let i = 0; i < inputPasswords.length; i++) {
    inputPasswords[i].addEventListener('keydown', () => {
        let counter = parseInt(inputPasswords[i].value.length);
        if (counter > 1) {
            eyes[i].style.display = "block";
            eyes[i].style.top = "calc(50% - 10px)";
            eyestwo[i].style.top = "calc(50% - 10px)";
            let state = false;
            eyes[i].addEventListener("click", () => {
                if (state) {
                    inputPasswords[i].setAttribute("type", "password");
                    state = false;
                } else {
                    inputPasswords[i].setAttribute("type", "text");
                    state = true;
                }
                eyes[i].style.display = "none";
                eyestwo[i].style.display = "block";
            })
            eyestwo[i].addEventListener("click", () => {
                if (state) {
                    inputPasswords[i].setAttribute("type", "password");
                    state = false;
                } else {
                    inputPasswords[i].setAttribute("type", "text");
                    state = true;
                }
                eyes[i].style.display = "block";
                eyestwo[i].style.display = "none";
            })
        } else {
            eyes[i].style.display = "none";
        }
    })
}