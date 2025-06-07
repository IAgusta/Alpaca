        // Exercise preview logic
        document.querySelectorAll('.exercise-container').forEach(function(container) {
            const form = container.querySelector('.exercise-form');
            const overlay = container.querySelector('.exercise-overlay');
            const resultCard = container.querySelector('.exercise-result-card');
            const explanation = form.getAttribute('data-explanation') || '';
            const radios = form.querySelectorAll('input[type="radio"]');
            const submitBtn = form.querySelector('.submit-answer');

            // Prevent double submission
            let submitted = false;

            submitBtn?.addEventListener('click', function(e) {
                e.preventDefault();
                if (submitted) return;
                let selected = form.querySelector('input[type="radio"]:checked');
                if (!selected) return;

                submitted = true;
                // Accept both "1" and "true" as correct
                let val = selected.value;
                let isCorrect = (val === "1" || val === "true" || val === 1 || val === true);
                // Highlight selected answer
                form.querySelectorAll('label').forEach(label => {
                    label.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
                });
                let selectedLabel = selected.closest('label');
                if (isCorrect) {
                    selectedLabel.classList.add('border-green-500', 'bg-green-50');
                } else {
                    selectedLabel.classList.add('border-red-500', 'bg-red-50');
                }

                // Show overlay
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');

                // Show floating card
                resultCard.classList.remove('hidden');
                resultCard.classList.add('flex');

                // Set icon and message
                const iconDiv = resultCard.querySelector('.result-icon');
                const msgDiv = resultCard.querySelector('.result-message');
                const expDiv = resultCard.querySelector('.result-explanation');
                if (isCorrect) {
                    iconDiv.innerHTML = `<svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;
                    msgDiv.textContent = "Your Answer is Correct";
                    msgDiv.className = "result-message font-semibold text-lg mb-1 text-green-600";
                } else {
                    iconDiv.innerHTML = `<svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`;
                    msgDiv.textContent = "Your Answer is Incorrect";
                    msgDiv.className = "result-message font-semibold text-lg mb-1 text-red-600";
                }
                expDiv.textContent = explanation;

                // Blur form (optional: add blur effect)
                form.classList.add('pointer-events-none', 'opacity-60');
            });
        });