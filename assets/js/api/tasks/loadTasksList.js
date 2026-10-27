
async function loadTaskList() {
    try {
        const res = await fetch('/api/tasks/fetchMyTaskList.php', {
            method: 'GET',
            credentials: 'include'
        });

        const data = await res.json();
        const container = document.querySelector('.task-list');
        container.innerHTML = '';

        if (!data.success || data.data.length === 0) {
            container.innerHTML = `<div class="task-item">
                <div class="task-content">
                    <div class="task-description">No tasks for today</div>
                </div>
            </div>`;
            return;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        data.data.forEach(task => {
            const isCompleted = task.status === 'Completed';

            let dueText = '';
            let dueColor = '';

            if (task.due_date) {
                const dueDate = new Date(task.due_date);
                dueDate.setHours(0, 0, 0, 0);

                const diffDays = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));

                const visualDate = formatDueDate(dueDate, today);
                dueText = `Due: ${visualDate}`;

                if (diffDays < 0) {
                    dueColor = 'style="color:red"'; // overdue
                } else if (diffDays <= 3) {
                    dueColor = 'style="color:orange"'; // nearing deadline
                }
            } else {
                dueText = `Priority: ${task.priority}`;
            }

            const taskHTML = `
                <div class="task-item">
                    <input type="checkbox" class="task-checkbox" data-id="${task.task_id}" ${isCompleted ? 'checked' : ''}>

                    <div class="task-content ${isCompleted ? 'completed' : ''}">
                        <div class="task-description">${task.title}</div>
                        <div class="task-time" ${dueColor}>
                            ${dueText}
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', taskHTML);
        });

        // Checkbox behavior
        document.querySelectorAll('.task-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                const content = this.nextElementSibling;
                const timeEl = content.querySelector('.task-time');
                const taskId = this.dataset.id;
                const newStatus = this.checked ? 'Completed' : 'Pending';

                const prevCompleted = content.classList.contains('completed'); // store previous state
                if (this.checked) {
                    content.classList.add('completed');
                    timeEl.style.color = ''; // clear due date color
                } else {
                    content.classList.remove('completed');
                }

                (async () => {
                    try {
                        const res = await fetch('/api/tasks/updateTaskItemStatus.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            credentials: 'include',
                            body: JSON.stringify({
                                task_id: taskId,
                                status: newStatus
                            })
                        });

                        const result = await res.json();
                        if (!result.success) {
                            throw new Error(result.error || 'Update failed');
                        }

                    } catch (err) {
                        // revert UI
                        this.checked = !this.checked;
                        if (prevCompleted) {
                            content.classList.add('completed');
                            timeEl.style.color = '';
                        } else {
                            content.classList.remove('completed');
                        }

                        // show toast
                        window.showToast(
                            "Failed to update task status. Please try again.",
                            "error",
                            "Task Update"
                        );
                    }
                })();
            });
        });

    } catch (err) {
        fetch('/api/helpers/logConsoleError.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                error: err.message || err,
                script: 'loadTaskList.js'
            }),
            credentials: 'include'
        });
    }
}

window.loadTaskList = loadTaskList;
