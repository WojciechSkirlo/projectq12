document.addEventListener("DOMContentLoaded", function () {
    //SHOW USER
    const userBtn = document.querySelector("#user-account");
    const infoUser = document.querySelector(".login-info");
    userBtn.addEventListener("mouseover", function () {
        infoUser.style.display = "block";
    })

    userBtn.addEventListener("mouseout", function () {
        infoUser.style.display = "none";
    })

    infoUser.addEventListener("mouseover", function () {
        infoUser.style.display = "block";
    })

    infoUser.addEventListener("mouseout", function () {
        infoUser.style.display = "none";
    })
});