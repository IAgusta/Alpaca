import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Highlight from '@tiptap/extension-highlight';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import TextAlign from '@tiptap/extension-text-align';
import Image from '@tiptap/extension-image';
import YouTube from '@tiptap/extension-youtube';
import TextStyle from '@tiptap/extension-text-style';
import FontFamily from '@tiptap/extension-font-family';
import { Color } from '@tiptap/extension-color';
import ImageResize from 'tiptap-extension-resize-image';
import Bold from '@tiptap/extension-bold';

window.addEventListener('load', function() {
    if (document.getElementById("wysiwyg-container")) {

    const FontSizeTextStyle = TextStyle.extend({
        addAttributes() {
            return {
            fontSize: {
                default: null,
                parseHTML: element => element.style.fontSize,
                renderHTML: attributes => {
                if (!attributes.fontSize) {
                    return {};
                }
                return { style: 'font-size: ' + attributes.fontSize };
                },
            },
            };
        },
    });

    // Get the initial content
    const initialContent = document.getElementById('content-hidden').value;
    const isExercise = document.querySelector('input[name="question"]') !== null;

    // Initialize single editor instance
    const editor = new Editor({
        element: document.querySelector('#wysiwyg-container'),
        extensions: [
            StarterKit.configure({
                bold: false, // Disable bold in StarterKit
            }),
            Bold.configure(), // Add Bold extension separately
            TextStyle,
            Color,
            FontSizeTextStyle,
            FontFamily,
            Highlight,
            Underline,
            Link.configure({
                openOnClick: false,
                autolink: true,
                defaultProtocol: 'https',
            }),
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Image.configure({
                allowBase64: true,
                HTMLAttributes: {
                    class: 'resize-image',
                },
            }),
            ImageResize.configure({
                resizeDirections: ['top-right', 'bottom-right', 'bottom-left', 'top-left'],
                defaultSize: {
                    width: 'auto',
                    height: 'auto',
                },
            }),
            YouTube,
        ],
        content: initialContent, // Set initial content here
        editorProps: {
            attributes: {
                class: 'format lg:format-lg dark:format-invert focus:outline-none format-blue max-w-none',
            },
        }
    });

    // Synchronize editor content
    editor.on('update', () => {
        const content = editor.getHTML();
        document.getElementById('content-hidden').value = content;
    });

    // set up custom event listeners for the buttons
    document.getElementById('toggleBoldButton').addEventListener('click', () => editor.chain().focus().toggleBold().run());
    document.getElementById('toggleItalicButton').addEventListener('click', () => editor.chain().focus().toggleItalic().run());
    document.getElementById('toggleUnderlineButton').addEventListener('click', () => editor.chain().focus().toggleUnderline().run());
    document.getElementById('toggleStrikeButton').addEventListener('click', () => editor.chain().focus().toggleStrike().run());
    document.getElementById('toggleHighlightButton').addEventListener('click', () => {
    const isHighlighted = editor.isActive('highlight');
    // when using toggleHighlight(), judge if is is already highlighted.
    editor.chain().focus().toggleHighlight({
        color: isHighlighted ? undefined : '#ffc078' // if is already highlighted，unset the highlight color
    }).run();
    });

    document.getElementById('toggleLinkButton').addEventListener('click', () => {
        const url = window.prompt('Enter image URL:', '');
        editor.chain().focus().toggleLink({ href: url }).run();
    });
    document.getElementById('removeLinkButton').addEventListener('click', () => {
        editor.chain().focus().unsetLink().run()
    });
    document.getElementById('toggleCodeBlockButton').addEventListener('click', () => {
        editor.chain().focus().toggleCodeBlock().run();
    });
    document.getElementById('toggleLeftAlignButton').addEventListener('click', () => {
        editor.chain().focus().setTextAlign('left').run();
    });
    document.getElementById('toggleCenterAlignButton').addEventListener('click', () => {
        editor.chain().focus().setTextAlign('center').run();
    });
    document.getElementById('toggleRightAlignButton').addEventListener('click', () => {
        editor.chain().focus().setTextAlign('right').run();
    });
    document.getElementById('toggleListButton').addEventListener('click', () => {
       editor.chain().focus().toggleBulletList().run();
    });
    document.getElementById('toggleOrderedListButton').addEventListener('click', () => {
        editor.chain().focus().toggleOrderedList().run();
    });
    document.getElementById('toggleBlockquoteButton').addEventListener('click', () => {
        editor.chain().focus().toggleBlockquote().run();
    });
    document.getElementById('toggleHRButton').addEventListener('click', () => {
        editor.chain().focus().setHorizontalRule().run();
    });
    document.getElementById('addImageButton').addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = async (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const result = e.target.result;
                    editor.chain().focus().setImage({ 
                        src: result,
                        alt: file.name,
                        title: file.name,
                    }).run();
                };
                reader.readAsDataURL(file);
            }
        };
        input.click();
    });
    
    document.getElementById('addVideoButton').addEventListener('click', () => {
        const url = window.prompt('Enter YouTube URL:', '');
        if (url) {
            editor.chain().focus().insertContent({
                type: 'youtube',
                attrs: {
                    src: url,
                    width: 800, // Default width
                    height: 450, // Default height
                },
            }).setNode('paragraph', { textAlign: 'center' }).run();
        }
    });

    // typography dropdown
    const typographyDropdown = FlowbiteInstances.getInstance('Dropdown', 'typographyDropdown');

    document.getElementById('toggleParagraphButton').addEventListener('click', () => {
        editor.chain().focus().setParagraph().run();
        typographyDropdown.hide();
    });
    
    document.querySelectorAll('[data-heading-level]').forEach((button) => {
        button.addEventListener('click', () => {
            const level = button.getAttribute('data-heading-level');
            editor.chain().focus().toggleHeading({ level: parseInt(level) }).run()
            typographyDropdown.hide();
        });
    });

    const textSizeDropdown = FlowbiteInstances.getInstance('Dropdown', 'textSizeDropdown');

    // Loop through all elements with the data-text-size attribute
    document.querySelectorAll('[data-text-size]').forEach((button) => {
        button.addEventListener('click', () => {
            const fontSize = button.getAttribute('data-text-size');

            // Apply the selected font size via pixels using the TipTap editor chain
            editor.chain().focus().setMark('textStyle', { fontSize }).run();

            // Hide the dropdown after selection
            textSizeDropdown.hide();
        });
    });

    // Listen for color picker changes
    const colorPicker = document.getElementById('color');
    colorPicker.addEventListener('input', (event) => {
        const selectedColor = event.target.value;

        // Apply the selected color to the selected text
        editor.chain().focus().setColor(selectedColor).run();
    })

    document.querySelectorAll('[data-hex-color]').forEach((button) => {
        button.addEventListener('click', () => {
            const selectedColor = button.getAttribute('data-hex-color');

            // Apply the selected color to the selected text
            editor.chain().focus().setColor(selectedColor).run();
        });
    });

    document.getElementById('reset-color').addEventListener('click', () => {
        editor.commands.unsetColor();
    })

    const fontFamilyDropdown = FlowbiteInstances.getInstance('Dropdown', 'fontFamilyDropdown');

    // Loop through all elements with the data-font-family attribute
    document.querySelectorAll('[data-font-family]').forEach((button) => {
        button.addEventListener('click', () => {
            const fontFamily = button.getAttribute('data-font-family');

            // Apply the selected font size via pixels using the TipTap editor chain
            editor.chain().focus().setFontFamily(fontFamily).run();

            // Hide the dropdown after selection
            fontFamilyDropdown.hide();
        });
    });

    // Add form submission handler
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const editorContent = editor.getHTML();
            document.getElementById('content-hidden').value = editorContent;

            // Submit the form
            form.submit();
        });
    }

}

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

// Initialize TipTap editor for preview containers
document.querySelectorAll('[id^="preview-container-"]').forEach((container) => {
    const content = container.innerHTML; // Get the content from the container
    container.innerHTML = ''; // Clear the plain HTML content

    new Editor({
        element: container,
        extensions: [
            StarterKit.configure({
                textStyle: false,
            }),
            TextStyle,
            Color,
            Bold.configure(),
            FontFamily,
            Highlight,
            Underline,
            Link,
            TextAlign.configure({
                types: ['heading', 'paragraph'], // Ensure alignment is applied to headings and paragraphs
            }),
            Image.configure({
                allowBase64: true,
                HTMLAttributes: {
                    class: 'resize-image',
                },
            }),
            ImageResize.configure({
                resizeDirections: ['top-right', 'bottom-right', 'bottom-left', 'top-left'],
                defaultSize: {
                    width: 300,
                    height: 'auto',
                },
            }),
            YouTube,
        ],
        content: content, // Set the content
        editable: false, // Make the editor read-only
        editorProps: {
            attributes: {
                class: 'format lg:format-lg dark:format-invert focus:outline-none format-blue max-w-none',
            },
        },
    });
});

// Initialize TipTap editor for the question field
if (document.getElementById("question-wysiwyg-container")) {
    const questionContainer = document.getElementById("question-wysiwyg-container");
    const questionContent = questionContainer.innerHTML; // Get the content from the container
    questionContainer.innerHTML = ''; // Clear the plain HTML content

    const questionEditor = new Editor({
        element: questionContainer,
        extensions: [
            StarterKit.configure({
                textStyle: false,
                bold: false,
                marks: {
                    bold: false,
                },
            }),
            TextStyle,
            Color,
            FontFamily,
            Highlight,
            Underline,
            Link,
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            Image.configure({
                allowBase64: true,
                HTMLAttributes: {
                    class: 'resize-image',
                },
            }),
            ImageResize.configure({
                resizeDirections: ['top-right', 'bottom-right', 'bottom-left', 'top-left'],
                defaultSize: {
                    width: 300,
                    height: 'auto',
                },
            }),
            YouTube,
        ],
        content: questionContent, // Set the content
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

// Initial color update
updatePreviewTextColor();

// Observe for dark mode changes
const observer = new MutationObserver(updatePreviewTextColor);
observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
});

// --- Exercise Answer Handling for Edit Form ---
document.addEventListener('DOMContentLoaded', function () {
    // Only run if answers-container exists (i.e., on edit form for exercises)
    const answersContainer = document.getElementById("answers-container");
    if (!answersContainer) return;

    // Add Answer Button
    const addAnswerBtn = document.getElementById("add-answer");
    if (addAnswerBtn) {
        addAnswerBtn.addEventListener("click", function () {
            // Find the next available index (max index + 1)
            let maxIndex = -1;
            answersContainer.querySelectorAll('input[name^="answers["][name$="[text]"]').forEach(input => {
                const match = input.name.match(/^answers\[(\d+)\]\[text\]$/);
                if (match) {
                    const idx = parseInt(match[1], 10);
                    if (idx > maxIndex) maxIndex = idx;
                }
            });
            const nextIndex = maxIndex + 1;

            // Create new answer row
            const div = document.createElement("div");
            div.className = "flex items-center space-x-2 mb-2";
            div.innerHTML = `
                <input type="text" name="answers[${nextIndex}][text]" class="w-full border-gray-300 dark:bg-gray-600 dark:border-gray-800 dark:text-white rounded-md shadow-sm">
                <input type="checkbox" name="answers[${nextIndex}][correct]" value="1" class="accent-blue-600">
                <span class="text-sm dark:text-white">Correct</span>
                <button type="button" class="text-red-600 remove-answer">×</button>
            `;
            answersContainer.appendChild(div);
        });
    }

    // Remove Answer (event delegation)
    answersContainer.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains("remove-answer")) {
            e.preventDefault();
            // Remove the closest .flex.items-center parent
            let answerDiv = e.target.closest(".flex.items-center");
            if (!answerDiv) answerDiv = e.target.parentElement;
            if (answerDiv) answerDiv.remove();
        }
    });

    // On form submit, ensure unchecked checkboxes submit "0"
    const form = answersContainer.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            answersContainer.querySelectorAll('.flex.items-center').forEach(row => {
                const checkbox = row.querySelector('input[type="checkbox"][name^="answers"]');
                if (checkbox && !checkbox.checked) {
                    // Add hidden input for unchecked
                    let hidden = row.querySelector('input[type="hidden"][name="' + checkbox.name + '"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = checkbox.name;
                        row.appendChild(hidden);
                    }
                    hidden.value = "0";
                } else if (checkbox && checkbox.checked) {
                    // Remove hidden if checked
                    let hidden = row.querySelector('input[type="hidden"][name="' + checkbox.name + '"]');
                    if (hidden) hidden.remove();
                }
            });
        });
    }
});
