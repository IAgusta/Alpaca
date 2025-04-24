// resources/js/course/progress.js

export function toggleProgress(moduleId, button) {
    const container = button.closest('.module-container');
    const isCurrentlyRead = container.dataset.read === 'true';
    
    // Update the container's data attribute
    container.dataset.read = (!isCurrentlyRead).toString();
    
    // Update container classes
    updateContainerClasses(container, !isCurrentlyRead);
    
    // Update the icon
    updateVisibilityIcon(button, !isCurrentlyRead);

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
            container.dataset.read = isCurrentlyRead.toString();
            updateContainerClasses(container, isCurrentlyRead);
            updateVisibilityIcon(button, isCurrentlyRead);
            console.error("Failed to update progress");
        }
        updateCourseProgressUI();
        updateToggleAllButtonState();
    })
    .catch(error => {
        console.error("Error:", error);
        // Revert on error
        container.dataset.read = isCurrentlyRead.toString();
        updateContainerClasses(container, isCurrentlyRead);
        updateVisibilityIcon(button, isCurrentlyRead);
    });
}

function updateContainerClasses(container, isRead) {
    if (isRead) {
        container.classList.remove("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
        container.classList.add("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
    } else {
        container.classList.remove("bg-gray-200", "border-gray-400", "text-gray-600", "dark:bg-gray-800", "dark:border-gray-600", "dark:text-gray-400");
        container.classList.add("bg-white", "border-blue-500", "text-black", "dark:bg-gray-900", "dark:border-blue-500", "dark:text-white");
    }
}

function updateVisibilityIcon(button, isRead) {
    const existingIcon = button.querySelector('.visibility-icon');
    const newIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    newIcon.setAttribute("class", "visibility-icon w-6 h-6");
    newIcon.setAttribute("viewBox", "0 -960 960 960");
    newIcon.setAttribute("fill", "currentColor");
    
    const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
    path.setAttribute("d", isRead 
        ? "m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Z"
        : "M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z"
    );
    
    newIcon.appendChild(path);
    existingIcon.replaceWith(newIcon);
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
    
    // Determine current action from actual module states instead of button text
    const totalModules = moduleContainers.length;
    const readModules = Array.from(moduleContainers)
        .filter(container => container.dataset.read === 'true')
        .length;
    
    const isMarkingAsRead = readModules < totalModules;
    const newReadState = isMarkingAsRead;
    
    // Optimistic UI update - change immediately
    button.disabled = true;
    button.textContent = 'Processing...';
    
    // Update all modules visually first
    moduleContainers.forEach(container => {
        container.dataset.read = newReadState.toString();
        updateContainerClasses(container, newReadState);
        const button = container.querySelector('.toggle-button');
        updateVisibilityIcon(button, newReadState);
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
        container.dataset.read = previousState.toString();
        updateContainerClasses(container, previousState);
        const button = container.querySelector('.toggle-button');
        updateVisibilityIcon(button, previousState);
    });
    
    button.textContent = previousState ? 'Mark All As Unread' : 'Mark All As Read';
}

function updateToggleAllButtonState() {
    const button = document.getElementById('toggleAllButton');
    if (!button) return;

    const moduleContainers = document.querySelectorAll('.module-container');
    const totalModules = moduleContainers.length;
    if (totalModules === 0) return;

    const readModules = Array.from(moduleContainers)
        .filter(container => container.dataset.read === 'true')
        .length;

    // Update button text based on current state
    button.textContent = readModules === totalModules ? 'Mark All As Unread' : 'Mark All As Read';
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

    // Initialize toggle all button state
    updateToggleAllButtonState();
});