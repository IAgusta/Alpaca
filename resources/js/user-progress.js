function toggleProgress(moduleId, button) {
    fetch(`/module-progress/${moduleId}/toggle`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = button.closest("div");
            const icon = button.querySelector("span");

            // Toggle visibility icon
            if (data.status === "read") {
                icon.textContent = "visibility_off";
                container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
                container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
            } else {
                icon.textContent = "visibility";
                container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
                container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
            }

            // Update course progress dynamically
            updateCourseProgressUI();
        }
    })
    .catch(error => console.error("Error:", error));
}

function updateCourseProgressUI() {
    fetch("/user/course-progress")
        .then(response => response.json())
        .then(data => {
            const progressText = document.getElementById("course-status");
            if (progressText) {
                if (data.course_completed) {
                    progressText.textContent = "🎉 Course Completed!";
                    progressText.classList.add("text-green-500");
                } else {
                    progressText.textContent = `${data.completed_modules} / ${data.total_modules} modules completed`;
                    progressText.classList.remove("text-green-500");
                }
            }
        });
}