// Script to handle task editing
document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.edit-btn');
    const modal = document.getElementById('edit-modal');
    const taskIndexInput = document.getElementById('task-index');
    const taskTitleInput = document.getElementById('edit-task');
    const categorySelect = document.getElementById('edit-category');
    const dueDateInput = document.getElementById('edit-due-date');
    const prioritySelect = document.getElementById('edit-priority');
    const closeModalBtn = document.querySelector('.close-modal');

    // Show the edit modal with the current task details
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const taskIndex = button.getAttribute('data-index');
            const taskCard = button.closest('.task-card');
            
            taskIndexInput.value = taskIndex;
            taskTitleInput.value = taskCard.querySelector('.task-title').textContent;
            categorySelect.value = taskCard.querySelector('.task-category').textContent;
            dueDateInput.value = taskCard.querySelector('.task-due-date').textContent.split(': ')[1];
            prioritySelect.value = taskCard.querySelector('.task-priority').textContent.split(' ')[0];
            
            modal.style.display = 'flex';
        });
    });

    // Close the modal
    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close modal if clicked outside the modal content
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
