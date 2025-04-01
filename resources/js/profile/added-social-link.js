document.addEventListener('DOMContentLoaded', function () {
    const saveSocialLinksButton = document.getElementById('save-social-links');
    const socialLinksInput = document.querySelector('input[name="social_media"]');
    const modalToggleIcon = document.querySelector('[data-modal-toggle="social-input-modal"] svg');

    function updateModalIcon(socialLinks) {
        if (Object.keys(socialLinks).length > 0) {
            modalToggleIcon.classList.remove('hidden');
        } else {
            modalToggleIcon.classList.add('hidden');
        }
    }

    saveSocialLinksButton.addEventListener('click', function () {
        const socialLinks = {};
        document.querySelectorAll('.social-link-container').forEach(container => {
            const platform = container.dataset.platform;
            const displaySpan = container.querySelector('.link-display-container span');
            if (displaySpan && displaySpan.textContent.trim()) {
                socialLinks[platform] = displaySpan.textContent.trim();
            }
        });

        // Store in hidden input
        socialLinksInput.value = JSON.stringify(socialLinks);

        // Update icon visibility
        updateModalIcon(socialLinks);

        // Close modal
        document.querySelector('[data-modal-hide="social-input-modal"]').click();

        // Submit the form
        document.getElementById('profile-form').submit();  // Make sure this ID exists on your form
    });

    document.querySelectorAll('.add-link-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const container = this.closest('.social-link-container');
            container.querySelector('.link-input-container').classList.remove('hidden');
            container.querySelector('.link-display-container').classList.add('hidden');
            this.classList.add('hidden');
        });
    });

    document.querySelectorAll('.save-link-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const container = this.closest('.social-link-container');
            const input = container.querySelector('input[type="url"]');
            const url = input.value.trim();
            if (url) {
                container.querySelector('.link-display-container span').textContent = url;
                container.querySelector('.link-display-container').classList.remove('hidden');
                container.querySelector('.add-link-btn').classList.add('hidden');
                container.querySelector('.link-input-container').classList.add('hidden');
                input.value = '';
            }
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-link-btn')) {
            const btn = e.target.closest('.remove-link-btn');
            const container = btn.closest('.social-link-container');
            container.querySelector('.link-display-container span').textContent = '';
            container.querySelector('.link-display-container').classList.add('hidden');
            container.querySelector('.add-link-btn').classList.remove('hidden');
        }
    });
});