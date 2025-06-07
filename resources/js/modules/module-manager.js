document.addEventListener('DOMContentLoaded', function() {
    function initSortableForList(list) {
        if (list.dataset.sortableInitialized) return; // Prevent double init
        new Sortable(list, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function() {
                const items = list.querySelectorAll('.module-item');
                const positions = Array.from(items).map((item, index) => ({
                    id: parseInt(item.dataset.id),
                    position: index + 1
                }));

                const reorderUrl = list.dataset.url;
                
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
                        // Update the data-position attributes
                        items.forEach((item, index) => {
                            item.dataset.position = index + 1;
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            }
        });
        list.dataset.sortableInitialized = "true";
    }

    // Initialize all visible sortable lists on page load
    document.querySelectorAll('.sortable-list').forEach(initSortableForList);

    // Listen for modal open events (Flowbite, Alpine, or custom event)
    // If you use Flowbite, you can listen for 'show.modal' event
    document.addEventListener('show.modal', function(e) {
        // e.target is the modal element
        e.target.querySelectorAll('.sortable-list').forEach(initSortableForList);
    });

    // If you use Alpine or custom modal logic, trigger a similar event or call initSortableForList as needed
});
