var TopicsDropdown = document.getElementById('TopicsDropdown');
var TopicsDropdownMenu = document.getElementById('TopicsDropdownMenu');

TopicsDropdown.onclick = function(e) {
    e.preventDefault();
    e.stopPropagation();
    TopicsDropdownMenu.classList.toggle('show');
};

document.addEventListener('click', function (e) {
    let clickedOutside = true;

    e.path.forEach(item => {
        if (!clickedOutside) {
            return;
        }

        if (item.className && item.className.indexOf('dropdown-menu-categories') > -1) {
            clickedOutside = false;
        }
    });

    if (clickedOutside) {
        if (TopicsDropdownMenu.className.indexOf('show') > -1) {
            TopicsDropdownMenu.classList.remove('show');
        }
    }
});