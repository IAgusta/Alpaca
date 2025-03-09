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
        roleSortIcon.textContent = sortAscending ? '🔽' : '🔼'; // Change icon

        // Append sorted rows back to the table
        rows.forEach(row => tbody.appendChild(row));
    });
});