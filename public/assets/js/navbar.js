function navbar() {
    // open
    const burger = document.querySelectorAll(".navbar-burger");
    const menu = document.querySelectorAll(".navbar-menu");

    if (burger.length && menu.length) {
        for (var i = 0; i < burger.length; i++) {
            burger[i].addEventListener("click", function () {
                for (var j = 0; j < menu.length; j++) {
                    menu[j].classList.toggle("hidden");
                }
            });
        }
    }

    // close
    const close = document.querySelectorAll(".navbar-close");
    const backdrop = document.querySelectorAll(".navbar-backdrop");

    if (close.length) {
        for (var i = 0; i < close.length; i++) {
            close[i].addEventListener("click", function () {
                for (var j = 0; j < menu.length; j++) {
                    hideMenu(menu[j])
                }
            });
        }
    }

    function hideMenu(menu){
        document.getElementById('navbar-drop').classList.add('ltr:animate-slide-left','rtl:animate-slide-left-hide-nav');
        setTimeout(() => {
            // console.log('ll')
            menu.classList.toggle("hidden");
            document.getElementById('navbar-drop').classList.remove('ltr:animate-slide-left','rtl:animate-slide-left-hide-nav');
        }, 500);
    }

    if (backdrop.length) {
        for (var i = 0; i < backdrop.length; i++) {
            backdrop[i].addEventListener("click", function () {
                for (var j = 0; j < menu.length; j++) {
                    hideMenu(menu[j]);
                }
            });
        }
    }
}
