import Quill from 'quill';
import ImageResize from 'quill-image-resize-module';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', function () {
    const editorElement = document.querySelector('#editor');
    if (editorElement) {
        Quill.register('modules/imageResize', ImageResize);

        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'font': [] }],  // Font selection
                    [{ 'size': ['small', false, 'large', 'huge'] }], // Font size
                    ['bold', 'italic', 'underline'],  // Text formatting
                    [{ 'align': [] }], // Text alignment (left, center, right, justify)
                    [{ 'header': [1, 2, 3, false] }],  // Header styles
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],  // Lists
                    ['image', 'video'],  // Image and video support
                    ['code-block']  // Code block
                ],
                imageResize: {
                    displayStyles: {
                        backgroundColor: 'black',
                        border: 'none',
                        color: 'white'
                    },
                    modules: ['Resize', 'DisplaySize']
                }
            }
        });

        // Update hidden input on change
        const hiddenInput = document.querySelector('#content-hidden');
        quill.on('text-change', () => {
            hiddenInput.value = quill.root.innerHTML; // This saves the full HTML
        });

        // Preview button functionality
        document.querySelector('#preview-btn').addEventListener('click', (event) => {
            event.preventDefault();
            document.querySelector('#preview-area').innerHTML = quill.root.innerHTML;
        });

        // Delete content functionality
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const contentId = button.dataset.id;
                fetch(`/admin/modules/content/${contentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(response => response.json())
                    .then(() => button.closest('.content-item').remove());
            });
        });
    } else {
        console.error("❌ Quill initialization failed: #editor not found!");
    }
});
