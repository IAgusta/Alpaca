document.addEventListener('DOMContentLoaded', function() {
    const sortableList = document.querySelector('.sortable-list');
    if (!sortableList) return;

    new Sortable(sortableList, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: function() {
            const items = document.querySelectorAll('.module-item');
            const positions = Array.from(items).map((item, index) => ({
                id: parseInt(item.dataset.id),
                position: index + 1
            }));

            const reorderUrl = sortableList.dataset.url;
            
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
                    console.log("✅ Module order updated successfully!");
                    // Update the data-position attributes
                    items.forEach((item, index) => {
                        item.dataset.position = index + 1;
                    });
                } else {
                    console.error("❌ Failed to update module order");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }
    });
});
