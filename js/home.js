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


// Hide on scroll down show on scroll up
(function () {

    var doc = document.documentElement;
    var w = window;

    var prevScroll = w.scrollY || doc.scrollTop;
    var curScroll;
    var direction = 0;
    var prevDirection = 0;

    var nav = document.getElementById('main-nav');

    var checkScroll = function () {

        curScroll = w.scrollY || doc.scrollTop;
        if (curScroll > prevScroll) {
            //scrolled up
            direction = 2;
        } else if (curScroll < prevScroll) {
            //scrolled down
            direction = 1;
        }

        if (direction !== prevDirection) {
            toggleHeader(direction, curScroll);
        }

        prevScroll = curScroll;
    };

    var toggleHeader = function (direction, curScroll) {
        if (direction === 2 && curScroll > 52) {
            nav.classList.add('hide');
            prevDirection = direction;
        } else if (direction === 1) {
            nav.classList.remove('hide');
            prevDirection = direction;
        }
    };

    window.addEventListener('scroll', checkScroll);

})();

const scrollUp = document.querySelector(".scroll-up");
window.addEventListener('scroll', function () {
    if (document.body.scrollTop > 800 || document.documentElement.scrollTop > 800) {
        scrollUp.style.display = "block";
    } else {
        scrollUp.style.display = "none";
    }
})

var scroll = new SmoothScroll('a[href*="#"]', {
    speed: 500
});


// Mobile menu
let btnOpen = $('#nav-hamburger-open');
let btnClose = $('#nav-hamburger-close');
let btnCloseTwo = $('nav#mobile-nav .nav-left');
btnOpen.on('click', function () {
    $('#belt1').toggleClass('rotate-up');
    $('#belt2').toggleClass('disappear');
    $('#belt3').toggleClass('rotate-down');
    $('#mobile-nav').toggleClass('show');
    $('body').toggleClass('fixed');
    $('#darker-screen').toggleClass('show');
})

btnClose.on('click', function () {
    $('#belt1').toggleClass('rotate-up');
    $('#belt2').toggleClass('disappear');
    $('#belt3').toggleClass('rotate-down');
    $('#mobile-nav').toggleClass('show');
    $('body').toggleClass('fixed');
    $('#darker-screen').toggleClass('show');
})

btnCloseTwo.on('click', function () {
    $('#belt1').toggleClass('rotate-up');
    $('#belt2').toggleClass('disappear');
    $('#belt3').toggleClass('rotate-down');
    $('#mobile-nav').toggleClass('show');
    $('body').toggleClass('fixed');
    $('#darker-screen').toggleClass('show');
})

$(window).resize(function () {
    if ($(window).width() > 800) {
        $('#darker-screen').removeClass('show');
        $('#mobile-nav').removeClass('show');
        $('#belt1').removeClass('rotate-up');
        $('#belt2').removeClass('disappear');
        $('#belt3').removeClass('rotate-down');
        $('body').removeClass('fixed');
    }
})

// Mobile 100vh
let vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty('--vh', `${vh}px`);

window.addEventListener('resize', () => {
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
});