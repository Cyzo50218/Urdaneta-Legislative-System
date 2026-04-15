window.TaskManager = (() => {
    let taskToDelete = null;
    let dragAllowed = false;

    let state = {
        allTasks: [],
        filter: 'All Tasks',
        searchQuery: '',
        isPolling: true,
        pollingInterval: 30000,
        pollTimer: null
    };
    let currentDragId = null;
    let dragFrame = null;
    let el = {
        listContainer: null,
        tabButtons: null,
        searchInput: null,
        counts: {},
        tabIndicator: null,
        tabContainer: null
    };
    function refreshElements() {
        el.listContainer = document.querySelector('#adminTaskSystem .space-y-2');
        el.tabButtons = document.querySelectorAll('#adminTaskSystem .filter-tab');
        el.searchInput = document.querySelector('#taskSearchInput');
        el.tabIndicator = document.getElementById('tab-indicator');
        el.tabContainer = document.getElementById('filterTabContainer');

        const statusTypes = ['All Tasks', 'Pending', 'In Progress', 'Completed', 'Blocked'];
        statusTypes.forEach((status) => {
            el.counts[status] = document.querySelector(`#filterTabContainer .filter-tab[data-filter="${status}"] .count-badge`);
        });


        setTimeout(() => {
            const activeTab = document.querySelector('.filter-tab.text-emerald-700');
            if (activeTab) moveIndicator(activeTab);
        }, 100);
    }

    function setupDragAndDrop() {
        if (!el.listContainer) return;

        /* ---------------------------
           TASK DRAG START (Delegated)
        ----------------------------*/
        el.listContainer.addEventListener('mousedown', (e) => {
            dragAllowed = !!e.target.closest('.drag-handle');
        });

        el.listContainer.addEventListener('dragstart', (e) => {
            const taskCard = e.target.closest('[data-id]');
            if (!taskCard || !dragAllowed) {
                e.preventDefault();
                return;
            }

            currentDragId = parseInt(taskCard.dataset.id);
            taskCard.classList.add('dragging');

            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', currentDragId);
        });

        el.listContainer.addEventListener('dragend', () => {
            dragAllowed = false;
            const draggingEl = el.listContainer.querySelector('.dragging');
            if (draggingEl) draggingEl.classList.remove('dragging');
        });




        /* ---------------------------
           REORDER INSIDE LIST
        ----------------------------*/
        el.listContainer.addEventListener('dragover', (e) => {
            e.preventDefault();

            const draggingEl = el.listContainer.querySelector('.dragging');
            if (!draggingEl) return;

            if (dragFrame) cancelAnimationFrame(dragFrame);

            dragFrame = requestAnimationFrame(() => {
                const afterElement = getDragAfterElement(el.listContainer, e.clientY);

                if (!afterElement) {
                    el.listContainer.appendChild(draggingEl);
                } else {
                    el.listContainer.insertBefore(draggingEl, afterElement);
                }
            });
        });

        el.listContainer.addEventListener('drop', (e) => {
            e.preventDefault();

            const draggingEl = el.listContainer.querySelector('.dragging');
            if (!draggingEl) return;

            draggingEl.classList.remove('dragging');

            const newOrder = [...el.listContainer.querySelectorAll('[data-id]')]
                .map(el => parseInt(el.dataset.id))
                .filter(id => id !== 0);

            window.TaskManager.updateOrder(newOrder);
        });

        /* ---------------------------
           DRAG TO FILTER TABS
        ----------------------------*/
        el.tabButtons.forEach(tab => {
            const filterType = tab.getAttribute('data-filter');
            if (filterType === 'All Tasks') return;

            tab.addEventListener('dragover', (e) => {
                e.preventDefault();
                tab.classList.add('ring-2', 'ring-emerald-400');
            });

            tab.addEventListener('dragleave', () => {
                tab.classList.remove('ring-2', 'ring-emerald-400');
            });

            tab.addEventListener('drop', (e) => {
                e.preventDefault();
                tab.classList.remove('ring-2', 'ring-emerald-400');

                const id = currentDragId;
                if (!id) return;

                window.TaskManager.updateStatus(id, filterType);

                const draggingEl = document.querySelector('.dragging');
                if (draggingEl) draggingEl.classList.remove('dragging');

                currentDragId = null;
            });
        });
    }


    function moveIndicator(tabElement) {
        if (!el.tabIndicator || !tabElement) return;

        const containerRect = el.tabContainer.getBoundingClientRect();
        const tabRect = tabElement.getBoundingClientRect();
        const left = tabRect.left - containerRect.left + el.tabContainer.scrollLeft;

        el.tabIndicator.style.width = `${tabRect.width}px`;
        el.tabIndicator.style.transform = `translateX(${left}px)`;
        if (state.filter === 'Blocked') {
            el.tabIndicator.classList.remove('bg-emerald-700');
            el.tabIndicator.classList.add('bg-red-600');
        } else {
            el.tabIndicator.classList.remove('bg-red-600');
            el.tabIndicator.classList.add('bg-emerald-700');
        }
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('[data-id]:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - (box.top + box.height / 2);
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
    function render() {
        if (!el.listContainer) return;
        refreshElements();
        if (el.tabButtons) {
            el.tabButtons.forEach(btn => {
                const filterType = btn.getAttribute('data-filter');
                if (filterType === state.filter) {
                    btn.classList.remove('text-gray-500');
                    btn.classList.add(state.filter === 'Blocked' ? 'text-red-600' : 'text-emerald-700');
                } else {
                    btn.classList.add('text-gray-500');
                    btn.classList.remove('text-emerald-700', 'text-red-600');
                }
            });
        }

        let filtered = state.allTasks.filter(task => {
            const matchesFilter = state.filter === 'All Tasks' || task.status === state.filter;
            const matchesSearch = task.title.toLowerCase().includes(state.searchQuery.toLowerCase()) ||
                task.task_id.toString().includes(state.searchQuery);
            return matchesFilter && matchesSearch;
        });
        const paginationText = document.getElementById('taskPaginationText');
        if (paginationText) {
            paginationText.textContent = `Showing ${filtered.length} of ${state.allTasks.length}`;
        }


        if (filtered.length === 0) {
            el.listContainer.innerHTML = `
        <div class="text-center py-10 text-gray-400">
            No Tasks Found
        </div>
    `;

            const paginationText = document.getElementById('taskPaginationText');
            if (paginationText) {
                paginationText.textContent = `Showing 0 of ${state.allTasks.length}`;
            }

            return;
        }


        el.listContainer.innerHTML = filtered.map(task => {
            const isDraft = task.task_id === 0;

            return `
    <div class="bg-white border ${isDraft ? 'border-dashed border-emerald-400 ring-2 ring-emerald-50 ring-inset' : 'border-gray-200 cursor-grab active:cursor-grabbing drag-handle'} rounded-md p-3 shadow-sm hover:shadow-md hover:border-emerald-400 transition-all group" 
        data-id="${task.task_id}"
        ${!isDraft ? `draggable="true"` : ''}>
        
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="flex items-start gap-3 flex-1">

                <div 
                class="mt-1 text-gray-300 ${isDraft ? 'invisible' : 'group-hover:text-gray-600 '} transition-colors" >
                    <i data-lucide="grip-vertical" class="w-5 h-5"></i>
                </div>

                ${isDraft ? '' : `
                    <input type="checkbox" 
                        ${task.status === 'Completed' ? 'checked' : ''} 
                        onchange="TaskManager.toggleComplete(${task.task_id}, this.checked)"
                        class="mt-1 w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                `}
                
                <div class="flex flex-col flex-1">
                    <div class="flex items-center gap-2">
                        <h4 class="task-title text-sm font-bold ${task.status === 'Completed' ? 'text-gray-400 line-through' : 'text-gray-900'} cursor-pointer group-hover:text-emerald-800 transition-colors" 
                            onclick="TaskManager.enableEdit(${task.task_id}, this)">
                            ${task.title}
                        </h4>
                        <button class="text-gray-300 hover:text-emerald-600 transition-colors" onclick="TaskManager.enableEdit(${task.task_id}, this.previousElementSibling)">
                            <i data-lucide="edit-2" class="w-3 h-3"></i>
                        </button>
                        ${!isDraft && task.priority === 'High' ? '<span class="flex h-2 w-2 rounded-full bg-red-600 animate-pulse"></span>' : ''}
                    </div>

                    <div class="flex items-center gap-3 mt-1 text-[11px] text-gray-500 font-medium">
                        <span class="flex items-center gap-1 text-gray-700 font-bold">
                            <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i> 
                            <input type="date" value="${task.due_date}" 
                                   onchange="TaskManager.updateDueDate(${task.task_id}, this.value)"
                                   class="border-none p-0 bg-transparent text-[11px] font-bold cursor-pointer focus:ring-0 text-emerald-700">
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-gray-400 uppercase leading-none mb-1 tracking-wider">Status</span>
                    <select onchange="TaskManager.updateStatus(${task.task_id}, this.value)" class="text-xs font-bold bg-gray-50 border border-gray-300 rounded px-2 py-1 outline-none hover:border-emerald-500 cursor-pointer text-gray-700 transition">
                        <option value="Pending" ${task.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="In Progress" ${task.status === 'In Progress' ? 'selected' : ''}>In Progress</option>
                        <option value="Completed"
                            ${task.status === 'Completed' ? 'selected' : ''} 
                            ${isDraft ? 'disabled class="text-gray-300"' : ''}>
                                Completed ${isDraft ? '(Save first)' : ''}
                        </option>
                        <option value="Blocked" ${task.status === 'Blocked' ? 'selected' : ''}>Blocked</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-gray-400 uppercase leading-none mb-1 tracking-wider">Priority</span>
                    <select onchange="TaskManager.updatePriority(${task.task_id}, this.value)" class="text-xs font-bold ${task.priority === 'High' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-gray-50 text-gray-700 border-gray-300'} border rounded px-2 py-1 outline-none cursor-pointer transition">
                        <option value="Low" ${task.priority === 'Low' ? 'selected' : ''}>Low</option>
                        <option value="Medium" ${task.priority === 'Medium' ? 'selected' : ''}>Medium</option>
                        <option value="High" ${task.priority === 'High' ? 'selected' : ''}>High</option>
                    </select>
                </div>

                ${!isDraft ? `
                    <div class="flex items-center gap-0.5 border-l border-gray-200 pl-4">
                        <button onclick="TaskManager.deleteTask(${task.task_id})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition" title="Delete Task">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                ` : ''}
            </div>

            ${isDraft ? `
                <div class="flex items-center gap-2 border-l border-gray-200 pl-4">
                    <button onclick="TaskManager.saveDraft()" class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded hover:bg-emerald-700 transition shadow-sm">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i> Save Task
                    </button>
                    <button onclick="window.fetchMyTaskList()" class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-bold rounded hover:bg-gray-200 transition">
                        Cancel
                    </button>
                </div>
            ` : ''}
        </div>
    </div>
    `;
        }).join('');


        if (window.lucide) lucide.createIcons();
    }
    function init() {
        refreshElements();

        setupDragAndDrop();
        if (el.tabButtons) {
            el.tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const filterType = btn.getAttribute('data-filter');
                    state.filter = filterType;
                    el.tabButtons.forEach(b => {
                        b.classList.remove('text-emerald-700', 'text-red-600');
                        b.classList.add('text-gray-500');
                    });
                    btn.classList.remove('text-gray-500');
                    if (filterType === 'Blocked') {
                        btn.classList.add('text-red-600');
                    } else {
                        btn.classList.add('text-emerald-700');
                    }

                    moveIndicator(btn);
                    render();
                });
            });
        }
        if (el.searchInput) {
            el.searchInput.addEventListener('input', (e) => {
                state.searchQuery = e.target.value;
                render();
            });
        }
        window.fetchMyTaskList();
        state.pollTimer = setInterval(() => window.fetchMyTaskList(), state.pollingInterval);
    }

    async function handleOptimisticRequest(url, body, updateFn) {
        const previousTasks = JSON.parse(JSON.stringify(state.allTasks));
        updateFn();
        render();
        updateCounts();

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error || "Server error");
            }
            const freshResponse = await fetch('/../../api/tasks/fetchMyTaskListAll.php');
            const freshResult = await freshResponse.json();
            if (freshResult.success) {
                state.allTasks = freshResult.data;
                render();
            }

        } catch (error) {
            console.error("Action Failed, rolling back:", error);
            state.allTasks = previousTasks;
            render();
            updateCounts();
            alert("Sync failed: " + error.message);
        }
    }

    window.fetchMyTaskList = async function () {
        try {
            const response = await fetch('/../../api/tasks/fetchMyTaskListAll.php');
            const result = await response.json();
            if (result.success) {
                state.allTasks = result.data;
                render();
                updateCounts();
            }
        } catch (error) {
            console.error("Network Error:", error);
        }
    };


    function updateCounts() {
        Object.keys(el.counts).forEach(key => {
            if (el.counts[key]) {
                const count = key === 'All Tasks'
                    ? state.allTasks.length
                    : state.allTasks.filter(t => t.status === key).length;
                el.counts[key].textContent = `(${count})`;
            }
        });
    }

    function formatDate(dateString) {
        if (!dateString) return 'No Date';
        return new Date(dateString).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
    }


    window.openDeleteTaskModal = (id, title) => {
        taskToDelete = id;
        const modal = document.getElementById('deleteTaskRecordModal');
        modal.querySelector('.record-modal-label').textContent = 'Task Management';
        modal.querySelector('.record-modal-title').textContent = 'Delete Task';
        modal.querySelector('.record-modal-body p').textContent = 'Are you sure you want to delete this task?';
        document.getElementById('deleteRecordLabel').textContent = `Task #${id}: ${title}`;
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    };

    return {
        init,

        enableEdit: (id, h4Element) => {
            if (h4Element.tagName === 'INPUT') return;

            const currentTitle = h4Element.innerText.trim();
            const input = document.createElement('input');
            let isSaving = false;

            input.type = 'text';
            input.value = currentTitle === 'New Task Title...' ? '' : currentTitle;
            input.className = 'text-sm font-bold border-b border-emerald-500 outline-none w-full bg-transparent';

            const save = () => {
                if (isSaving) return;
                isSaving = true;

                const newTitle = input.value.trim();

                // DRAFT AUTO-SAVE LOGIC
                if (id === 0) {
                    if (!newTitle) {
                        state.allTasks = state.allTasks.filter(t => t.task_id !== 0);
                        render();
                        return;
                    }

                    const draft = state.allTasks.find(t => t.task_id === 0);

                    fetch('/../../api/tasks/addNewTask.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            title: newTitle,
                            due_date: draft.due_date,
                            status: draft.status,
                            priority: draft.priority
                        })
                    })
                        .then(res => res.json())
                        .then(result => {
                            if (result.success) {
                                // Successfully created in DB, now refresh to get real ID
                                window.fetchMyTaskList();
                            } else {
                                alert("Auto-save failed: " + result.error);
                                render();
                            }
                        });
                    return;
                }

                // EXISTING TASK UPDATE LOGIC
                if (!newTitle || newTitle === currentTitle) {
                    render();
                    return;
                }
                handleOptimisticRequest('/../../api/tasks/updateTaskItemStatus.php',
                    { task_id: id, title: newTitle },
                    () => {
                        const task = state.allTasks.find(t => t.task_id === id);
                        if (task) task.title = newTitle;
                    }
                );
            };

            input.onblur = save;
            input.onkeydown = (e) => {
                if (e.key === 'Enter') { e.preventDefault(); input.blur(); }
                if (e.key === 'Escape') { isSaving = true; render(); }
            };

            h4Element.replaceWith(input);
            input.focus();
        },

        updateDueDate: (id, date) => {
            const task = state.allTasks.find(t => t.task_id === id);
            if (task) task.due_date = date;

            // If it's a draft, don't talk to the server yet. 
            // The enableEdit 'save' function will handle the initial DB creation.
            if (id === 0) {
                render();
                return;
            }

            handleOptimisticRequest('/../../api/tasks/updateTaskItemStatus.php',
                { task_id: id, due_date: date },
                () => { /* state already updated */ }
            );
        },

        updateStatus: (id, status) => {
            const task = state.allTasks.find(t => t.task_id === id);
            if (task) task.status = status;

            if (id === 0) {
                render();
                return;
            }
            handleOptimisticRequest('/../../api/tasks/updateTaskItemStatus.php',
                { task_id: id, status: status },
                () => { /* state already updated */ }
            );
        },

        updatePriority: (id, priority) => {
            const task = state.allTasks.find(t => t.task_id === id);
            if (task) task.priority = priority;

            if (id === 0) {
                render();
                return;
            }
            handleOptimisticRequest('/../../api/tasks/updateTaskItemStatus.php',
                { task_id: id, priority: priority },
                () => { /* state already updated */ }
            );
        },

        updateOrder: async (idList) => {
            // It Optimistically update local state so the UI doesn't jump
            const taskMap = new Map(state.allTasks.map(t => [t.task_id, t]));
            state.allTasks = idList.map(id => taskMap.get(id)).filter(Boolean);

            try {
                const response = await fetch('/../../api/tasks/updateTaskOrder.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order: idList })
                });
                const result = await response.json();
                if (!result.success) throw new Error(result.error);
            } catch (error) {
                console.error("Failed to save order:", error);
                // Re-fetch to fix the UI if the DB update failed
                window.fetchMyTaskList();
            }
        },

        saveDraft: () => {
            const draft = state.allTasks.find(t => t.task_id === 0);
            if (!draft) return;

            fetch('/../../api/tasks/addNewTask.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title: draft.title, due_date: draft.due_date })
            })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        window.fetchMyTaskList();
                    } else {
                        alert(result.error);
                    }
                });
        },

        addNewTask: () => {
            if (state.allTasks.find(t => t.task_id === 0)) return;
            if (state.filter === 'Completed') {
                state.filter = 'Pending';
            }

            let initialStatus = 'Pending';
            if (['Pending', 'In Progress', 'Blocked'].includes(state.filter)) {
                initialStatus = state.filter;
            }

            const draftTask = {
                task_id: 0,
                title: 'New Task Title...',
                status: initialStatus,
                priority: 'Medium',
                due_date: new Date().toISOString().split('T')[0],
                isDraft: true
            };
            state.allTasks.unshift(draftTask);
            render();
            const newH4 = document.querySelector('[data-id="0"] .task-title');
            if (newH4) window.TaskManager.enableEdit(0, newH4);
        },

        toggleComplete: (id, isChecked) => {
            const newStatus = isChecked ? 'Completed' : 'Pending';
            handleOptimisticRequest('/../../api/tasks/updateTaskItemStatus.php',
                { task_id: id, status: newStatus },
                () => {
                    const task = state.allTasks.find(t => t.task_id === id);
                    if (task) task.status = newStatus;
                }
            );
        },

        deleteTask: (id) => {
            const task = state.allTasks.find(t => t.task_id === id);
            const title = task ? task.title : '';
            window.openDeleteTaskModal(id, title);
        },

        closeDeleteModal: () => {
            const modal = document.getElementById('deleteTaskRecordModal');
            if (!modal) return;

            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                taskToDelete = null;
            }, 300);
        },

        confirmDeleteTask: () => {
            if (taskToDelete) {
                window.TaskManager.executeDelete(taskToDelete);
                window.TaskManager.closeDeleteModal();
            }
        },

        executeDelete: (id) => {
            handleOptimisticRequest('/../../api/tasks/deleteTaskItem.php',
                { task_id: id },
                () => {
                    state.allTasks = state.allTasks.filter(t => t.task_id !== id);
                }
            );
        },

        executeDelete: (id) => {
            handleOptimisticRequest('/../../api/tasks/deleteTaskItem.php',
                { task_id: id },
                () => {
                    state.allTasks = state.allTasks.filter(t => t.task_id !== id);
                }
            );
        },
    };
})();