document.addEventListener('DOMContentLoaded', function () {
    const sortableList = document.querySelector('.sortable-list');
    const contentTypeSelect = document.getElementById("content_type");
    const contentEditor = document.getElementById("content-editor");
    const exerciseForm = document.getElementById("exercise-form");
    const questionEditorElement = document.getElementById("question-editor");
    const exerciseFields = document.getElementById("exercise-fields");
    const editorLabel = document.getElementById("editor-label");

    // Initialize TipTap for the question editor
    if (questionEditorElement) {
        const questionEditor = new Editor({
            element: questionEditorElement,
            extensions: [
                StarterKit,
                TextStyle,
                Color,
                FontFamily,
                Highlight,
                Underline,
                Link,
                TextAlign,
                Image,
                YouTube,
            ],
            content: document.getElementById('question-hidden').value, // Load existing content
            editorProps: {
                attributes: {
                    class: 'format lg:format-lg dark:format-invert focus:outline-none format-blue max-w-none',
                },
            },
        });

        // Synchronize the question editor's content with the hidden input field
        questionEditor.on('update', () => {
            const content = questionEditor.getHTML();
            document.getElementById('question-hidden').value = content;
        });
    }

    // Enable Drag-and-Drop Sorting with Hamburger Icon
    if (sortableList) {
        new Sortable(sortableList, {
            animation: 150,
            handle: '.drag-handle', // Only the hamburger icon is draggable
            ghostClass: 'sortable-ghost', // Add a visual ghost class
            chosenClass: 'sortable-chosen', // Add a visual chosen class
            dragClass: 'sortable-drag', // Add a visual drag class
            onEnd: function () {
                updateContentPositions();
            }
        });

        function updateContentPositions() {
            const items = document.querySelectorAll('.content-item');
            const positions = Array.from(items).map((item, index) => ({
                id: item.dataset.id,
                position: index + 1
            }));

            const reorderUrl = sortableList.dataset.url;
            
            fetch(reorderUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ positions })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log("✅ Order updated successfully!");
                    // Update the data-position attributes to match the new order
                    items.forEach((item, index) => {
                        item.dataset.position = index + 1;
                    });
                } else {
                    console.error("❌ Failed to update order", data);
                    // Optionally revert the UI if the update failed
                }
            })
            .catch(error => {
                console.error("Error:", error);
                // Optionally revert the UI if the request failed
            });
        }
    }

    // Switch between Content & Exercise (only for create form)
    if (contentTypeSelect) {
        contentTypeSelect.addEventListener("change", function() {
            if (this.value === "exercise") {
                exerciseFields.classList.remove("hidden");
                editorLabel.textContent = "Pertanyaan:";
            } else {
                exerciseFields.classList.add("hidden");
                editorLabel.textContent = "Isi Konten:";
            }
        });
    }

    // Add new answer option (only for exercise form)
    document.getElementById("add-answer")?.addEventListener("click", function() {
        const container = document.getElementById("answers-container");
        const answerIndex = container.querySelectorAll('.flex.items-center').length;
        const div = document.createElement("div");
        div.classList.add("flex", "items-center", "space-x-2", "mb-2");

        div.innerHTML = `
            <input type="text" name="answers[${answerIndex}][text]" class="w-full border-gray-300 dark:bg-gray-600 dark:text-white dark:border-gray-800 rounded-md shadow-sm">
            <input type="checkbox" name="answers[${answerIndex}][correct]" value="1" class="accent-blue-600">
            <span class="text-sm dark:text-white">Correct</span>
            <button type="button" class="text-red-600 remove-answer">×</button>
        `;
        container.appendChild(div);
    });

    // Fix: Use event delegation for remove-answer buttons (robust selector)
    document.getElementById("answers-container")?.addEventListener("click", function(e) {
        if (e.target && e.target.classList.contains("remove-answer")) {
            e.preventDefault();
            // Remove the closest .flex.items-center parent
            let answerDiv = e.target.closest(".flex.items-center");
            if (!answerDiv) {
                // fallback for browsers that don't support .closest with multiple classes
                answerDiv = e.target.parentElement;
            }
            if (answerDiv) answerDiv.remove();
        }
    });

    // Ensure unchecked checkboxes are submitted as "correct": false
    document.querySelector('form')?.addEventListener('submit', function(e) {
        // For each answer row, if the checkbox is not checked, add a hidden input with value "0"
        const answersContainer = document.getElementById("answers-container");
        if (answersContainer) {
            const answerRows = answersContainer.querySelectorAll('.flex.items-center');
            answerRows.forEach((row, idx) => {
                const checkbox = row.querySelector('input[type="checkbox"][name^="answers"]');
                if (checkbox && !checkbox.checked) {
                    // Remove any existing hidden for this answer
                    let hidden = row.querySelector('input[type="hidden"][name="' + checkbox.name + '"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = checkbox.name;
                        row.appendChild(hidden);
                    }
                    hidden.value = "0";
                } else if (checkbox && checkbox.checked) {
                    // Remove any hidden if checkbox is checked
                    let hidden = row.querySelector('input[type="hidden"][name="' + checkbox.name + '"]');
                    if (hidden) hidden.remove();
                }
            });
        }
    });

    // --- Dynamic Text Color Swap for Preview Containers ---
    const lightBg = ["#D1D5DB", "#F3F4F6", "#F9FAFB", "#EBF5FF", "#FCD9BD", "#CABFFD", "#F8B4D9", "#F6C196", "#A4CAFE", "#BCF0DA", "#FCE96A"];
    const darkBg = ["#111928", "#1E429F", "#5145CD", "#771D1D", "#99154B", "#03543F", "#4B5563", "#6B7280", "#0E9F6E", "#0694A2"];

    function updatePreviewTextColor() {
        const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
        document.querySelectorAll('[id^="preview-container-"]').forEach((container) => {
            container.querySelectorAll('[style*="color"]').forEach(el => {
                const color = el.style.color.replace(/\s/g, '').toUpperCase();
                let hexColor = color;
                if (color.startsWith('RGB')) {
                    const rgb = color.match(/\d+/g);
                    if (rgb && rgb.length >= 3) {
                        hexColor = "#" + rgb.slice(0, 3).map(x => (+x).toString(16).padStart(2, '0')).join('').toUpperCase();
                    }
                }
                if (isDark && darkBg.includes(hexColor)) {
                    el.style.color = "#e5e7eb";
                } else if (!isDark && lightBg.includes(hexColor)) {
                    el.style.color = "#1f2937";
                }
            });
            // Also handle <strong>, <b>, and bold font-weight elements without explicit color
            container.querySelectorAll('strong, b, [style*="font-weight"]').forEach(el => {
                const computed = window.getComputedStyle(el);
                if (!el.style.color || ['initial', 'inherit', 'unset', '', 'auto', 'rgb(0,0,0)', 'rgb(255,255,255)'].includes(computed.color)) {
                    if (isDark) {
                        el.style.color = "#e5e7eb";
                    } else {
                        el.style.color = "#1f2937";
                    }
                }
            });
            if (isDark) {
                container.style.color = "#e5e7eb";
            } else {
                container.style.color = "#1f2937";
            }
        });
    }

    // Initial color update
    updatePreviewTextColor();

    // Observe for dark mode changes
    const observer = new MutationObserver(updatePreviewTextColor);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
});