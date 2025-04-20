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

document.addEventListener("DOMContentLoaded", function () {
    // Initialize TipTap editor for preview containers
    document.querySelectorAll('[id^="wysiwyg-preview-"]').forEach((container) => {
        const content = container.innerHTML; // Get the content from the container
        container.innerHTML = ''; // Clear the plain HTML content

        new Editor({
            element: container,
            extensions: [
                StarterKit.configure({
                    textStyle: false,
                }),
                Bold.configure(),
                TextStyle,
                Color,
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

    // Render code blocks with a "Copy" button
    document.querySelectorAll('[id^="wysiwyg-preview-"] code').forEach((codeBlock) => {
        const codeContent = codeBlock.textContent.trim(); // Extract code content
        const wrapper = document.createElement("div");
        wrapper.classList.add("relative", "p-4", "bg-gray-800", "text-white", "rounded-lg", "overflow-auto", "shadow-sm");

        // Create <pre><code> structure
        const pre = document.createElement("pre");
        const code = document.createElement("code");
        code.textContent = codeContent;
        pre.appendChild(code);

        // Style the <pre> block
        pre.style.margin = "0";
        pre.style.whiteSpace = "pre-wrap";
        pre.style.overflowX = "auto";

        // Create "Copy" button
        const copyBtn = document.createElement("button");
        copyBtn.innerText = "Copy";
        copyBtn.classList.add("absolute", "top-2", "right-2", "px-2", "py-1", "bg-blue-500", "text-white", "rounded", "text-sm", "hover:bg-blue-600");

        // Copy functionality
        copyBtn.addEventListener("click", function () {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(codeContent).then(() => {
                    copyBtn.innerText = "Copied!";
                    setTimeout(() => (copyBtn.innerText = "Copy"), 2000);
                });
            } else {
                const textarea = document.createElement("textarea");
                textarea.value = codeContent;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
                copyBtn.innerText = "Copied!";
                setTimeout(() => (copyBtn.innerText = "Copy"), 2000);
            }
        });

        // Append elements
        wrapper.appendChild(copyBtn);
        wrapper.appendChild(pre);
        codeBlock.replaceWith(wrapper);
    });
});