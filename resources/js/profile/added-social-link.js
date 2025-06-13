document.addEventListener('tabContentLoaded', function () {
    // Elements
    const form = document.getElementById('social-media-form');
    const socialMediaInput = document.getElementById('social-media-input');
    const actionUrl = form.getAttribute('data-action');
    
    // Initialize with existing data
    let socialLinks = JSON.parse(socialMediaInput.value || '{}');
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        // Collect all current values
        const links = {};
        document.querySelectorAll('input[name^="social["]').forEach(input => {
            const matches = input.name.match(/social\[(.+?)\]/);
            if (matches && matches[1] && input.value.trim()) {
                links[matches[1]] = input.value.trim();
            }
        });

        // Update the hidden input
        socialMediaInput.value = JSON.stringify(links);
        
        // Verify we have data
        if (Object.keys(links).length === 0) {
            e.preventDefault();
            alert('Please add at least one social media link');
        }
    });
    
    // Update function for both individual and bulk saves
    function updateSocialLinks() {
        const newLinks = {};
        
        // Get all platform inputs
        document.querySelectorAll('input[name^="social["]').forEach(input => {
            const matches = input.name.match(/social\[(.+?)\]/);
            if (matches && matches[1] && input.value.trim()) {
                newLinks[matches[1]] = input.value.trim();
            }
        });
        
        // Merge with existing
        socialLinks = {...socialLinks, ...newLinks};
        socialMediaInput.value = JSON.stringify(socialLinks);

        // Log temporary storage
        console.log('Temporary storage updated:', socialLinks);
    }
    
    // Save individual link
    document.querySelectorAll('.save-link-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const container = this.closest('.social-link-container');
            const input = container.querySelector('input[type="url"]');
            const url = input.value.trim();
            
            if (url) {
                const platform = container.dataset.platform;
                socialLinks[platform] = url;
                socialMediaInput.value = JSON.stringify(socialLinks);
                
                // Log temporary storage
                console.log('Temporary storage after saving link:', JSON.parse(socialMediaInput.value));
                
                // Update UI
                container.querySelector('.link-display-container span').textContent = url;
                container.querySelector('.link-display-container').classList.remove('hidden');
                container.querySelector('.add-link-btn').classList.add('hidden');
                container.querySelector('.link-input-container').classList.add('hidden');
            }
        });
    });
    
    // Other event handlers (add/remove links)...
    document.querySelectorAll('.add-link-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const container = this.closest('.social-link-container');
            container.querySelector('.link-input-container').classList.remove('hidden');
            container.querySelector('.link-display-container').classList.add('hidden');
            this.classList.add('hidden');
        });
    });
    
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-link-btn')) {
            const btn = e.target.closest('.remove-link-btn');
            const container = btn.closest('.social-link-container');
            const platform = container.dataset.platform;
            
            // Remove from our data
            delete socialLinks[platform];
            socialMediaInput.value = JSON.stringify(socialLinks);
            
            // Update UI
            container.querySelector('.link-display-container span').textContent = '';
            container.querySelector('.link-display-container').classList.add('hidden');
            container.querySelector('.add-link-btn').classList.remove('hidden');
        }
    });
});