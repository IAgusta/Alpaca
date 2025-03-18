document.addEventListener('DOMContentLoaded', function () {
    const rowsPerPage = 10;
    let currentPage = 1;

    function filterUsers() {
        const searchInput = document.getElementById('simple-search').value.toLowerCase();
        const onlineFilter = document.getElementById('online').checked;
        const offlineFilter = document.getElementById('offline').checked;
        const adminFilter = document.getElementById('admin').checked;
        const trainerFilter = document.getElementById('trainer').checked;
        const userFilter = document.getElementById('user').checked;
        const rows = document.querySelectorAll('tbody tr');

        let filteredRows = Array.from(rows).filter(row => {
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
                highlightText(usernameElement, searchInput);
                highlightText(emailElement, searchInput);
                return true;
            } else {
                row.style.display = 'none';
                return false;
            }
        });

        if (searchInput === '') {
            filteredRows = Array.from(rows);
        }

        paginateRows(filteredRows);
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

    function paginateRows(rows) {
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updatePagination(totalPages);
    }

    function updatePagination(totalPages) {
        const pagination = document.querySelector('nav[aria-label="Table navigation"] ul');
        pagination.innerHTML = '';

        const createPageItem = (page, text, isCurrent = false) => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#';
            a.className = `flex items-center justify-center text-sm py-2 px-3 leading-tight ${isCurrent ? 'text-primary-600 bg-primary-50 border border-primary-300' : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'}`;
            a.textContent = text;
            a.addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = page;
                paginateRows(rows);
            });
            li.appendChild(a);
            return li;
        };

        if (currentPage > 1) {
            pagination.appendChild(createPageItem(currentPage - 1, 'Previous'));
        }

        if (totalPages <= 5) {
            for (let i = 1; i <= totalPages; i++) {
                pagination.appendChild(createPageItem(i, i, i === currentPage));
            }
        } else {
            if (currentPage > 2) {
                pagination.appendChild(createPageItem(1, '1'));
                if (currentPage > 3) {
                    pagination.appendChild(createPageItem(null, '...'));
                }
            }

            const startPage = Math.max(1, currentPage - 1);
            const endPage = Math.min(totalPages, currentPage + 1);

            for (let i = startPage; i <= endPage; i++) {
                pagination.appendChild(createPageItem(i, i, i === currentPage));
            }

            if (currentPage < totalPages - 1) {
                if (currentPage < totalPages - 2) {
                    pagination.appendChild(createPageItem(null, '...'));
                }
                pagination.appendChild(createPageItem(totalPages, totalPages));
            }
        }

        if (currentPage < totalPages) {
            pagination.appendChild(createPageItem(currentPage + 1, 'Next'));
        }
    }

    // Attach filterUsers function to the search input and filter checkboxes
    document.getElementById('simple-search').addEventListener('input', filterUsers);
    document.querySelectorAll('#filterDropdown input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', filterUsers);
    });

    // Attach clearFilters function to the clear button
    document.querySelector('#filterDropdown button').addEventListener('click', clearFilters);

    // Initial filter and pagination setup
    filterUsers();

    // Initial pagination setup
    const rows = document.querySelectorAll('tbody tr');
    paginateRows(rows);
});