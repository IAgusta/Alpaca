document.addEventListener('alpine:init', () => {
    Alpine.data('roleChangeModal', () => ({
        changeUserRole(userId, role) {
            if (confirm('Are you sure you want to change the role?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="role" value="${role}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    }));
});

document.addEventListener('DOMContentLoaded', function () {
    // Select modal, form, and input fields
    const roleColumn = document.getElementById('sort-role');
    const roleSortIcon = document.getElementById('role-sort-icon');
    let sortAscending = true;

    // Define SVG icons
    const ascendingIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-down-alt" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10.082 10.371 9.664 9H8.598l1.789 5.332h1.234L13.402 9h-1.12l-.419 1.371zm1.57.785L11 13.313h-.047l-.652-2.157z"/>
            <path d="M12.96 2H9.028v.691l2.579 3.72v.054H9.098v.867h3.785v-.691l-2.567-3.72v-.054h2.645zM4.5 13.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-2 .007-.007a.497.497 0 0 1 .7.006l2 2a.5.5 0 0 1-.707.708L4.5 3.707V13.5z"/>
        </svg>
    `;
    const descendingIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-alpha-down" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10.082 5.629 9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371zm1.57-.785L11 2.687h-.047l-.652 2.157z"/>
            <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293z"/>
        </svg>
    `;

    // Add event listeners to all role dropdown options
    document.querySelectorAll('[data-role]').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();

            // Get selected role and user ID
            const role = this.getAttribute('data-role');
            const userId = this.getAttribute('data-user-id');

            // Confirm role change
            if (confirm('Are you sure you want to change the role?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${userId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="role" value="${role}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Handle role sorting
    roleColumn.addEventListener('click', function () {
        const table = document.querySelector('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Sort rows by role text
        rows.sort((rowA, rowB) => {
            const roleA = rowA.querySelector('td:nth-child(3)').textContent.trim(); // Role is in the 3rd column
            const roleB = rowB.querySelector('td:nth-child(3)').textContent.trim();
            
            return sortAscending ? roleA.localeCompare(roleB) : roleB.localeCompare(roleA);
        });

        // Toggle sorting direction
        sortAscending = !sortAscending;
        roleSortIcon.innerHTML = sortAscending ? ascendingIcon : descendingIcon; // Change icon

        // Append sorted rows back to the table
        rows.forEach(row => tbody.appendChild(row));
    });
});