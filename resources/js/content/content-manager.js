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
        const answerIndex = container.children.length;
        const div = document.createElement("div");
        div.classList.add("flex", "items-center", "space-x-2", "mb-2");

        div.innerHTML = `
            <input type="text" name="answers[${answerIndex}][text]" class="w-full border-gray-300 rounded-md shadow-sm">
            <input type="checkbox" name="answers[${answerIndex}][correct]" value="1">
            <span class="text-sm">Correct</span>
            <button type="button" class="text-red-600 remove-answer">×</button>
        `;
        container.appendChild(div);

        // Remove answer option
        div.querySelector(".remove-answer").addEventListener("click", function() {
            div.remove();
        });
    });
});