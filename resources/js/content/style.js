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
    // Palette from your color buttons
    const palette = [
        "#1A56DB", "#0E9F6E", "#FACA15", "#F05252", "#FF8A4C", "#0694A2",
        "#B4C6FC", "#8DA2FB", "#5145CD", "#771D1D", "#FCD9BD", "#99154B",
        "#7E3AF2", "#CABFFD", "#D61F69", "#F8B4D9", "#F6C196", "#A4CAFE",
        "#5145CD", "#B43403", "#FCE96A", "#1E429F", "#768FFD", "#BCF0DA",
        "#EBF5FF", "#16BDCA", "#E74694", "#83B0ED", "#03543F", "#111928",
        "#4B5563", "#6B7280", "#D1D5DB", "#F3F4F6", "#F3F4F6", "#F9FAFB"
    ];
    // Colors that are too light for light mode or too dark for dark mode
    const lightBg = ["#D1D5DB", "#F3F4F6", "#F9FAFB", "#EBF5FF", "#FCD9BD", "#CABFFD", "#F8B4D9", "#F6C196", "#A4CAFE", "#BCF0DA", "#FCE96A"];
    const darkBg = ["#111928", "#1E429F", "#5145CD", "#771D1D", "#99154B", "#03543F", "#4B5563", "#6B7280", "#0E9F6E", "#0694A2"];

    // Helper to update text color for preview containers
    function updatePreviewTextColor() {
        const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
        document.querySelectorAll('[id^="wysiwyg-preview-"]').forEach((container) => {
            // Check for inline color style on children and adjust if needed
            container.querySelectorAll('[style*="color"]').forEach(el => {
                const color = el.style.color.replace(/\s/g, '').toUpperCase();
                // Convert rgb/rgba to hex if needed
                let hexColor = color;
                if (color.startsWith('RGB')) {
                    const rgb = color.match(/\d+/g);
                    if (rgb && rgb.length >= 3) {
                        hexColor = "#" + rgb.slice(0, 3).map(x => (+x).toString(16).padStart(2, '0')).join('').toUpperCase();
                    }
                }
                // If color is in the palette and would collide, adjust
                if (isDark && darkBg.includes(hexColor)) {
                    el.style.color = "#e5e7eb"; // Tailwind gray-200
                } else if (!isDark && lightBg.includes(hexColor)) {
                    el.style.color = "#1f2937"; // Tailwind gray-800
                }
            });
            // Also handle <strong>, <b>, and bold font-weight elements without explicit color
            container.querySelectorAll('strong, b, [style*="font-weight"]').forEach(el => {
                // Only override if color is not set or is default/auto/inherit
                const computed = window.getComputedStyle(el);
                if (!el.style.color || ['initial', 'inherit', 'unset', '', 'auto', 'rgb(0,0,0)', 'rgb(255,255,255)'].includes(computed.color)) {
                    if (isDark) {
                        el.style.color = "#e5e7eb";
                    } else {
                        el.style.color = "#1f2937";
                    }
                }
            });
            // Fallback for container itself
            if (isDark) {
                container.style.color = "#e5e7eb";
            } else {
                container.style.color = "#1f2937";
            }
        });
    }

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
                YouTube.configure({
                    width: 800,
                    height: 450,
                    HTMLAttributes: {
                        class: 'youtube-video',
                        frameborder: 0,
                        allowfullscreen: 'true',
                        allow: 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
                    },
                }),
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

    // Initial color update
    updatePreviewTextColor();

    // Observe for dark mode changes
    const observer = new MutationObserver(updatePreviewTextColor);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });

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