// JavaScript to handle role selection
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-role]').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const role = this.getAttribute('data-role');
            const form = this.closest('form');
            const roleInput = form.querySelector('input[name="role"]');
            roleInput.value = role;
            form.submit();
        });
    });
});