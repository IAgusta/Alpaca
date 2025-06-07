document.addEventListener('DOMContentLoaded', function() {
    // Handle all create/edit forms
    document.querySelectorAll('[id^="course-create-form"], [id^="course-edit-form-"]').forEach(function(form) {
        // Get unique id ('' for create, or course id for edit)
        let id = '';
        if (form.id.startsWith('course-edit-form-')) {
            id = form.id.replace('course-edit-form-', '');
        } else if (form.id === 'course-create-form') {
            id = '';
        }

        // Checkbox elements
        const isTesting = document.getElementById('is-testing' + (id ? '-' + id : ''));
        const isManga = document.getElementById('is-manga' + (id ? '-' + id : ''));
        const isNovel = document.getElementById('is-novel' + (id ? '-' + id : ''));
        const showOthers = document.getElementById('show-others' + (id ? '-' + id : ''));
        const otherOptions = document.getElementById('other-options' + (id ? '-' + id : ''));

        // Mutually exclusive logic
        if (isTesting && isManga && isNovel) {
            isTesting.addEventListener('change', function() {
                if (this.checked) {
                    isManga.checked = false;
                    isNovel.checked = false;
                }
            });
            isManga.addEventListener('change', function() {
                if (this.checked) {
                    isTesting.checked = false;
                    isNovel.checked = false;
                }
            });
            isNovel.addEventListener('change', function() {
                if (this.checked) {
                    isTesting.checked = false;
                    isManga.checked = false;
                }
            });
        }

        // Show "Other" logic
        if (showOthers && otherOptions) {
            showOthers.addEventListener('click', function() {
                otherOptions.classList.remove('hidden');
                showOthers.classList.add('hidden');
            });
        }

        // Name composition logic
        form.addEventListener('submit', function(e) {
            const mainName = document.getElementById('main-name' + (id ? '-' + id : '')).value.trim();
            const altTitle = document.getElementById('alt-title' + (id ? '-' + id : '')).value.trim();
            let name = mainName;
            if (isTesting && isTesting.checked) name += ' (test)';
            else if (isManga && isManga.checked) name += ' (manga)';
            else if (isNovel && isNovel.checked) name += ' (novel)';
            if (altTitle) name += ' [' + altTitle + ']';
            const nameInput = document.getElementById((form.id === 'course-create-form') ? 'name' : 'name-actual-' + id);
            if (nameInput) nameInput.value = name;
        });
    });
});
