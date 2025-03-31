// resources/js/course/progress.js

export function toggleProgress(moduleId, button) {
    const icon = button.querySelector('.visibility-icon');
    const container = button.closest('.module-container');
    
    // Determine current state from the icon
    const isRead = icon.textContent.trim() === 'visibility_off';
    
    // Optimistic UI update
    icon.textContent = isRead ? 'visibility' : 'visibility_off';
    
    // Update container classes
    if (isRead) {
        container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
        container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
    } else {
        container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
        container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
    }

    // Send request to server
    fetch(`/module-progress/${moduleId}/toggle`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // Revert if server failed
            revertToggleState(button, isRead);
            console.error("Failed to update progress");
        }
        updateCourseProgressUI();
    })
    .catch(error => {
        console.error("Error:", error);
        revertToggleState(button, isRead);
    });
}

function revertToggleState(button, previousState) {
    const icon = button.querySelector('.visibility-icon');
    const container = button.closest('.module-container');
    
    icon.textContent = previousState ? 'visibility_off' : 'visibility';
    
    if (previousState) {
        container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
        container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
    } else {
        container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
        container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
    }
}

export function updateCourseProgressUI(completedModules = null, totalModules = null, courseCompleted = null) {
    if (completedModules !== null && totalModules !== null && courseCompleted !== null) {
        updateUI(completedModules, totalModules, courseCompleted);
    } else {
        fetch("/user/course-progress")
            .then(response => response.json())
            .then(data => updateUI(data.completed_modules, data.total_modules, data.course_completed))
            .catch(error => console.error("Error updating course progress:", error));
    }
}

function updateUI(completedModules, totalModules, courseCompleted) {
    const progressText = document.getElementById("course-status");
    const progressBar = document.getElementById("course-progress-bar");

    if (progressText) {
        if (courseCompleted) {
            progressText.textContent = "🎉 Course Completed!";
            progressText.classList.add("text-green-500");
        } else {
            progressText.textContent = `${completedModules} / ${totalModules} modules completed`;
            progressText.classList.remove("text-green-500");
        }
    }

    if (progressBar) {
        const percentage = totalModules > 0 ? (completedModules / totalModules) * 100 : 0;
        progressBar.style.width = `${percentage}%`;
        progressBar.textContent = `${Math.round(percentage)}%`;
    }
}

export function toggleAllModules(courseId) {
    const button = document.getElementById('toggleAllButton');
    const moduleContainers = document.querySelectorAll('.module-container');
    
    // Determine current action from button text
    const isMarkingAsRead = button.textContent.includes('Mark All As Read');
    const newReadState = isMarkingAsRead; // true if marking as read, false if unread
    
    // Optimistic UI update - change immediately
    button.disabled = true;
    button.textContent = 'Processing...';
    
    // Update all modules visually first
    moduleContainers.forEach(container => {
        const icon = container.querySelector('.visibility-icon');
        
        // Update icon
        icon.textContent = newReadState ? 'visibility_off' : 'visibility';
        
        // Update container classes
        if (newReadState) {
            container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
            container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
        } else {
            container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
            container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
        }
    });

    // Send request to server
    fetch(`/courses/${courseId}/toggle-all`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Content-Type": "application/json",
        },
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update button text to reflect new state
            button.textContent = data.newStatus === 'read' ? 'Mark All As Unread' : 'Mark All As Read';
            
            // Update progress UI
            updateCourseProgressUI(data.completed_modules, data.total_modules, data.course_completed);
        } else {
            throw new Error('Server returned unsuccessful status');
        }
    })
    .catch(error => {
        console.error("Error:", error);
        // Revert UI changes on error
        revertAllModulesUI(!newReadState);
    })
    .finally(() => {
        button.disabled = false;
    });
}

function revertAllModulesUI(previousState) {
    const button = document.getElementById('toggleAllButton');
    const moduleContainers = document.querySelectorAll('.module-container');
    
    moduleContainers.forEach(container => {
        const icon = container.querySelector('.visibility-icon');
        
        icon.textContent = previousState ? 'visibility_off' : 'visibility';
        
        if (previousState) {
            container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
            container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
        } else {
            container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
            container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
        }
    });
    
    button.textContent = previousState ? 'Mark All As Unread' : 'Mark All As Read';
}

// Initialize functions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Event delegation for toggle buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-button')) {
            const button = e.target.closest('.toggle-button');
            const moduleId = button.closest('.module-container').dataset.moduleId;
            toggleProgress(moduleId, button);
        }
        
        if (e.target.id === 'toggleAllButton') {
            const courseId = e.target.dataset.courseId;
            toggleAllModules(courseId);
        }
    });
});