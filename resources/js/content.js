document.addEventListener("DOMContentLoaded", function () {
    // Fix curly quotes in links within Quill editor content
    document.querySelectorAll(".ql-editor a").forEach(link => {
        link.href = link.href.replace(/“|”/g, '"');
        link.target = link.target.replace(/“|”/g, '"');
    });

    // Handle clicks on links within Quill editor content
    document.querySelectorAll(".ql-editor a").forEach(link => {
        link.addEventListener("click", function (e) {
            e.stopPropagation();
            e.preventDefault(); // Prevent Laravel from interpreting it as a relative path
            const url = new URL(this.href.replace(/“|”/g, '"'));
            window.open(url.href, '_blank');
        });
    });

    // Center-align images within Quill editor content
    document.querySelectorAll(".ql-editor img").forEach(img => {
        img.style.display = "block";
        img.style.margin = "0 auto";
        img.style.maxWidth = "100%";
        img.style.height = "auto";
    });

    document.querySelectorAll(".ql-editor h1").forEach(h1 => {
        h1.classList.add("text-lg", "font-bold", "mb-4");
    });

    document.querySelectorAll(".ql-code-block-container").forEach(container => {
        let codeLines = [];

        // Extract and combine code lines
        container.querySelectorAll(".ql-code-block").forEach(line => {
            let text = line.textContent.trim(); // Use textContent for accurate copying
            codeLines.push(text);
        });

        let formattedCode = codeLines.join("\n"); // Join lines with newlines

        // Create <pre><code> structure
        let pre = document.createElement("pre");
        let code = document.createElement("code");
        code.textContent = formattedCode;
        pre.appendChild(code);

        // Style the <pre> block
        pre.style.display = "block";
        pre.style.margin = "10px 0";
        pre.style.padding = "10px";
        pre.style.backgroundColor = "#282c34";
        pre.style.color = "#fff";
        pre.style.borderRadius = "5px";
        pre.style.whiteSpace = "pre-wrap";
        pre.style.overflowX = "auto";
        pre.style.position = "relative";

        // Create "Copy" button
        let copyBtn = document.createElement("button");
        copyBtn.innerText = "Copy";
        copyBtn.style.position = "absolute";
        copyBtn.style.right = "10px";
        copyBtn.style.top = "5px";
        copyBtn.style.padding = "5px 10px";
        copyBtn.style.fontSize = "12px";
        copyBtn.style.cursor = "pointer";
        copyBtn.style.backgroundColor = "#007bff";
        copyBtn.style.color = "#fff";
        copyBtn.style.border = "none";
        copyBtn.style.borderRadius = "3px";
        copyBtn.style.zIndex = "5";

        // Copy function with fallback method
        copyBtn.addEventListener("click", function () {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(formattedCode).then(() => {
                    copyBtn.innerText = "Copied!";
                    setTimeout(() => copyBtn.innerText = "Copy", 2000);
                }).catch(err => {
                    console.error("Failed to copy:", err);
                });
            } else {
                // Fallback for non-secure environments
                let textarea = document.createElement("textarea");
                textarea.value = formattedCode;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand("copy");
                document.body.removeChild(textarea);
                copyBtn.innerText = "Copied!";
                setTimeout(() => copyBtn.innerText = "Copy", 2000);
            }
        });

        // Wrap everything and replace original content
        let wrapper = document.createElement("div");
        wrapper.style.position = "relative";
        wrapper.appendChild(copyBtn);
        wrapper.appendChild(pre);

        container.replaceWith(wrapper);
    });

    // Center-align and resize videos within Quill editor content
    document.querySelectorAll(".ql-editor .ql-video").forEach(video => {
        video.style.display = "block";
        video.style.margin = "0 auto";
        video.style.width = "80%"; // Adjust the width as needed
        video.style.maxWidth = "800px"; // Set a maximum width
        video.style.height = "auto"; // Maintain aspect ratio
        video.style.maxHeight = "450px"; // Set a maximum height
        video.style.height = "450px"; // Set height to fit full HD
    });
});