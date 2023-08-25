if (typeof mixpanel !== 'undefined') {
    if (typeof __APP_DATA__ !== 'undefined' && __APP_DATA__.user_id) {
        mixpanel.identify(__APP_DATA__.user_id);
    }
}

window.hero_end_position = 750;

var calculateHeroPosition = function () {
    var $hero = document.getElementsByClassName("app-hero")[0];
    if ($hero) {
        var hero_position = $hero.getBoundingClientRect();
        window.hero_end_position = hero_position.y + hero_position.height + 50 + window.pageYOffset;
        // console.log('window.hero_end_position', window.hero_end_position);

        setTimeout(calculateHeroPosition, 5000);
    }
};

var initShareButtons = function () {
    var $sticky_share_buttons = document.getElementsByClassName("st-sticky-share-buttons")[0];
    var is_sticky_visible = false;
    var $hero = document.getElementsByClassName("app-hero")[0];
    var prop = 'left';

    if (!$sticky_share_buttons) {
        return false;
    }

    calculateHeroPosition();

    if (window.innerWidth <= 1024) {
        prop = 'bottom';
    }

    window.onscroll = function() {
        var currentScrollPos = window.pageYOffset;
        if (currentScrollPos<(window.hero_end_position)) {
            if (is_sticky_visible) {
                $sticky_share_buttons.style.setProperty(prop, '-100px', 'important');
                is_sticky_visible = false;
            }
        } else {
            if (!is_sticky_visible) {
                $sticky_share_buttons.style.setProperty(prop, '0px', 'important');
                is_sticky_visible = true;
            }
        }
    }
}

setTimeout(initShareButtons, 4000);

var modal_instances = {};
var get_modal = function (modal) {
    if (modal_instances[modal]) {
        return modal_instances[modal];
    }
    var el = document.getElementById(modal + '-modal');
    if (el) {
        var modal_instance = new bootstrap.Modal(el);
        modal_instances[modal] = modal_instance;
        return modal_instance;
    }
}

function set_cookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function get_cookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function delete_cookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

delete_cookie('redirect_to_checkout');

var goToCheckout = function (is_anual) {
    if (typeof app !== 'undefined' && typeof app.goToCheckout !== 'undefined') {
        app.goToCheckout(is_anual);
    } else {
        plan = (is_anual) ? 'pro-yearly' : 'pro';
        mixpanel.track("ClickedOnUpgrade", { 'editor': 'templates-page', 'plan': plan});
        if (__APP_DATA__.user && __APP_DATA__.user.id) {
            window.location.href = "/subscriptions/pay/" + plan;
        } else {
            // $('#UpgradeModal').modal('hide');
            get_modal('upgrade').hide();
            get_modal('login').show();
            set_cookie('redirect_to_checkout', plan, 1);
            mixpanel.track("ViewLoginModal", { 'editor': 'templates-page' });
        }
    }
};

// var TopicsDropdown = document.getElementById('TopicsDropdown');
// var TopicsDropdownMenu = document.getElementById('TopicsDropdownMenu');

// TopicsDropdown.onclick = function(e) {
//     e.preventDefault();
//     e.stopPropagation();
//     TopicsDropdownMenu.classList.toggle('show');
// };

// document.addEventListener('click', function (e) {
//     let clickedOutside = true;

//     e.path.forEach(item => {
//         if (!clickedOutside) {
//             return;
//         }

//         if (item.className && item.className.indexOf('dropdown-menu-categories') > -1) {
//             clickedOutside = false;
//         }
//     });

//     if (clickedOutside) {
//         if (TopicsDropdownMenu.className.indexOf('show') > -1) {
//             TopicsDropdownMenu.classList.remove('show');
//         }
//     }
// });