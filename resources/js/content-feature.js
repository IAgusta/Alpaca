document.addEventListener("DOMContentLoaded", function () {
    // Existing code for handling drawers, tooltips, and backdrops
    const drawers = document.querySelectorAll('[data-drawer-target]');
    const tooltipTriggerList = document.querySelectorAll('[data-tooltip-target]');

    // Function to find and remove any backdrop
    function removeAllBackdrops() {
        // Target each possible backdrop selector separately
        const backdropSelectors = [
            '.drawer-backdrop', 
            '[drawer-backdrop]',
            '.bg-gray-900.bg-opacity-50',
            'div[modal-backdrop]'
        ];
        
        // Process each selector individually to avoid syntax errors
        backdropSelectors.forEach(selector => {
            try {
                const elements = document.querySelectorAll(selector);
                elements.forEach(element => element.remove());
            } catch (e) {
                console.log("Selector error:", e);
            }
        });
        
        // Also check for any dynamically added backdrops with specific class combinations
        document.querySelectorAll('div').forEach(div => {
            if (div.classList.contains('fixed') && 
                div.classList.contains('inset-0') && 
                div.classList.contains('bg-gray-900') && 
                (div.classList.contains('bg-opacity-50') || div.classList.contains('bg-opacity-80'))) {
                div.remove();
            }
        });
    }

    // Global click handler to detect clicks on backdrop
    document.addEventListener('click', function(event) {
        // Check if click is on a potential backdrop element
        if (event.target.tagName === 'DIV' && 
            ((event.target.classList.contains('bg-gray-900') && 
            (event.target.classList.contains('bg-opacity-50') || event.target.classList.contains('bg-opacity-80'))) ||
            event.target.hasAttribute('drawer-backdrop') ||
            event.target.classList.contains('drawer-backdrop'))) {
            
            // Find all drawers and add the translate class to hide them
            document.querySelectorAll('[id^="drawer-"]').forEach(drawer => {
                if (drawer) drawer.classList.add('-translate-x-full');
            });
            
            // Remove all backdrops
            removeAllBackdrops();
        }
    });

    // Additional handler for close buttons
    const closeButtons = document.querySelectorAll('[data-drawer-hide]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Small delay to ensure drawer animation completes
            setTimeout(removeAllBackdrops, 300);
        });
    });

    // Handle the ESC key to close drawers
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            // Hide all drawers
            document.querySelectorAll('[id^="drawer-"]').forEach(drawer => {
                if (drawer) drawer.classList.add('-translate-x-full');
            });
            
            // Remove all backdrops
            removeAllBackdrops();
        }
    });

    // Existing code for handling exercise forms
    document.querySelectorAll(".exercise-form").forEach(form => {
        form.querySelector(".submit-answer").addEventListener("click", function () {
            const selectedAnswer = form.querySelector('input[name^="answer-"]:checked');
            const explanationDiv = form.nextElementSibling; // Target the sibling div
            const iconSpan = explanationDiv ? explanationDiv.querySelector(".icon") : null;
            const explanationTextSpan = explanationDiv ? explanationDiv.querySelector(".explanation-text") : null;

            // Debugging: Log the selected answer value
            console.log("Selected Answer Value:", selectedAnswer ? selectedAnswer.value : "No answer selected");

            if (!selectedAnswer) {
                alert("Please select an answer.");
                return;
            }

            if (!explanationDiv || !iconSpan || !explanationTextSpan) {
                console.error("Explanation elements not found.");
                return;
            }

            // Check if the selected answer is correct
            const isCorrect = selectedAnswer.value.trim() === "1"; // Ensure it matches the value in your HTML
            explanationDiv.classList.remove("hidden");

            // Set explanation text
            if (form.dataset.explanation) {
                explanationTextSpan.innerHTML = form.dataset.explanation;
            } else {
                explanationTextSpan.innerHTML = isCorrect ? "Great job! You got it right." : "Oops! That's not correct.";
            }

            // Change text color and icon
            explanationDiv.classList.remove("text-red-500", "text-green-500");
            if (isCorrect) {
                explanationDiv.classList.add("text-green-500");
                iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Correct!';
            } else {
                explanationDiv.classList.add("text-red-500");
                iconSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg> Wrong!';
            }
        });
    });

    // New code for handling module navigation with "Previous" and "Next" buttons
    const modules = document.querySelectorAll('.module-content');
    const prevButton = document.getElementById('prev-module');
    const nextButton = document.getElementById('next-module');
    let currentIndex = 0;

    // Function to show the current module and hide others
    function showModule(index) {
        modules.forEach((module, i) => {
            if (i === index) {
                module.classList.remove('hidden');
                // Scroll to the top of the current module
                module.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Mark the module as read
                const moduleId = module.dataset.moduleId;
                if (moduleId) {
                    markModuleAsRead(moduleId);
                }
            } else {
                module.classList.add('hidden');
            }
        });

        // Disable "Previous" button if it's the first module
        prevButton.classList.toggle('hidden', index === 0);
        // Disable "Next" button if it's the last module
        nextButton.classList.toggle('hidden', index === modules.length - 1);
    }

    // Function to mark a module as read
    function markModuleAsRead(moduleId) {
        fetch(`/mark-module-as-read/${moduleId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Module ${moduleId} marked as read.`);
                updateDrawerContent(moduleId); // Update the drawer content
            } else {
                console.error('Failed to mark module as read:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Handle "Previous" button click
    prevButton.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showModule(currentIndex);
        }
    });

    // Handle "Next" button click
    nextButton.addEventListener('click', () => {
        if (currentIndex < modules.length - 1) {
            currentIndex++;
            showModule(currentIndex);
        }
    });

    // Initialize the first module
    showModule(currentIndex);

    // Function to update the drawer content
    function updateDrawerContent(moduleId) {
        fetch(`/get-drawer-content/${moduleId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const drawerContentList = document.querySelector(`#drawer-content-list-${moduleId}`);
            if (drawerContentList) {
                drawerContentList.innerHTML = data.html; // Replace the drawer content
            }
        })
        .catch(error => {
            console.error('Error fetching drawer content:', error);
        });
    }
});