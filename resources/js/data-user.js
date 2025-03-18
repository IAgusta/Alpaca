document.addEventListener('DOMContentLoaded', function () {
    function filterUsers() {
        const searchInput = document.getElementById('simple-search').value.toLowerCase();
        const onlineFilter = document.getElementById('online').checked;
        const offlineFilter = document.getElementById('offline').checked;
        const adminFilter = document.getElementById('admin').checked;
        const trainerFilter = document.getElementById('trainer').checked;
        const userFilter = document.getElementById('user').checked;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const usernameElement = row.querySelector('th div div span');
            const emailElement = row.querySelector('th div div span.font-normal');
            const username = usernameElement.textContent.toLowerCase();
            const email = emailElement.textContent.toLowerCase();
            const isActive = row.querySelector('td:nth-child(3) div').textContent.trim().toLowerCase() === 'online';
            const role = row.querySelector('td:nth-child(2) div').textContent.trim().toLowerCase();

            let matchesSearch = username.includes(searchInput) || email.includes(searchInput);
            let matchesStatus = (!onlineFilter && !offlineFilter) || (onlineFilter && isActive) || (offlineFilter && !isActive);
            let matchesRole = (!adminFilter && !trainerFilter && !userFilter) || (adminFilter && role === 'admin') || (trainerFilter && role === 'trainer') || (userFilter && role === 'user');

            if (matchesSearch && matchesStatus && matchesRole) {
                row.style.display = '';
                highlightText(usernameElement, searchInput);
                highlightText(emailElement, searchInput);
            } else {
                row.style.display = 'none';
            }
        });
    }

    function highlightText(element, text) {
        const innerHTML = element.textContent;
        const index = innerHTML.toLowerCase().indexOf(text.toLowerCase());
        if (index >= 0) {
            element.innerHTML = innerHTML.substring(0, index) + "<span class='highlight'>" + innerHTML.substring(index, index + text.length) + "</span>" + innerHTML.substring(index + text.length);
        } else {
            element.innerHTML = innerHTML; // Reset if no match
        }
    }

    function clearFilters() {
        document.querySelectorAll('#filterDropdown input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        filterUsers();
    }

    // Attach filterUsers function to the search input and filter checkboxes
    document.getElementById('simple-search').addEventListener('input', filterUsers);
    document.querySelectorAll('#filterDropdown input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', filterUsers);
    });

    // Attach clearFilters function to the clear button
    document.querySelector('#filterDropdown button').addEventListener('click', clearFilters);
});