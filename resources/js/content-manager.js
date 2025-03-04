import Quill from 'quill';
import ImageResize from 'quill-image-resize-module';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', function () {
    const editorElement = document.querySelector('#editor');
    const questionEditorElement = document.querySelector('#question-editor');
    const sortableList = document.querySelector('.sortable-list');
    const contentTypeSelect = document.getElementById("content_type");
    const contentEditor = document.getElementById("content-editor");
    const exerciseForm = document.getElementById("exercise-form");

    if (editorElement || questionEditorElement) {
        Quill.register('modules/imageResize', ImageResize);

        // Shared Quill configuration
        const quillConfig = {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }],  // Font selection
                    [{ 'size': ['small', false, 'large', 'huge'] }], // Font size
                    ['bold', 'italic', 'underline'],  // Text formatting
                    [{ 'align': [] }], // Text alignment (left, center, right, justify)
                    [{ 'header': [1, 2, 3, false] }],  // Header styles
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],  // Lists
                    ['link','image', 'video'],  // Image and video support
                    ['code-block']  // Code block
                ],
                imageResize: {
                    displayStyles: {
                        backgroundColor: 'black',
                        border: 'none',
                        color: 'white'
                    },
                    modules: ['Resize', 'DisplaySize']
                },
                handlers: {
                    image: function () {
                        imageHandler(this.quill); // Pass the Quill instance to the handler
                    }
                }
            }
        };

        // Initialize Quill for the main content editor (if it exists)
        if (editorElement) {
            const quill = new Quill('#editor', quillConfig);

            // Save Quill Editor Content
            quill.on('text-change', () => {
                document.querySelector('#content-hidden').value = quill.root.innerHTML;
            });
        }

        // Initialize Quill for the question editor (if it exists)
        if (questionEditorElement) {
            const questionQuill = new Quill('#question-editor', quillConfig);

            // Save Question Editor Content
            questionQuill.on('text-change', () => {
                document.querySelector('#question-hidden').value = questionQuill.root.innerHTML;
            });
        }

        // 🚀 Enable Drag-and-Drop Sorting with Hamburger Icon
        if (sortableList) {
            new Sortable(sortableList, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function () {
                    let positions = [];
                    document.querySelectorAll('.content-item').forEach((item, index) => {
                        positions.push({ id: item.dataset.id, position: index + 1 });
                    });

                    let reorderUrl = sortableList.dataset.url;

                    fetch(reorderUrl, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ positions: positions }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log("✅ Order updated successfully!");
                        } else {
                            console.error("❌ Failed to update order", data);
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            });
        }

        // Image handler for Quill editor
        function imageHandler(quillInstance) {
            return function () {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = async () => {
                    const file = input.files[0];
                    if (file) {
                        const formData = new FormData();
                        formData.append('image', file);

                        // Upload image to server
                        const response = await fetch('/upload-image', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: formData,
                        });

                        const data = await response.json();
                        if (data.success) {
                            // Insert image into Quill editor
                            const range = quillInstance.getSelection();
                            quillInstance.insertEmbed(range.index, 'image', data.url);
                        } else {
                            alert('Failed to upload image.');
                        }
                    }
                };
            };
        }

        // 🗑 Delete Content
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                if (!confirm("Are you sure you want to delete this content?")) return;

                const formAction = form.getAttribute('action');
                const formMethod = form.querySelector('input[name="_method"]').value;

                fetch(formAction, {
                    method: formMethod,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Content deleted successfully!");
                        form.closest('.content-item').remove();
                    } else {
                        alert("❌ Failed to delete content.");
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });

        // Switch between Content & Exercise (only for create form)
        if (contentTypeSelect) {
            contentTypeSelect.addEventListener("change", function() {
                if (this.value === "content") {
                    contentEditor.classList.remove("hidden");
                    exerciseForm.classList.add("hidden");
                } else {
                    contentEditor.classList.add("hidden");
                    exerciseForm.classList.remove("hidden");
                }
            });
        }

        // Add new answer option (only for exercise form)
        document.getElementById("add-answer")?.addEventListener("click", function() {
            let container = document.getElementById("answers-container");
            let answerIndex = container.children.length;
            let div = document.createElement("div");
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
    } else {
        console.error("❌ Quill initialization failed: #editor or #question-editor not found!");
    }
});