const initTaskBoard = () => {
    const board = document.querySelector('[data-task-board]');

    if (!board) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const moveUrlTemplate = board.dataset.moveUrlTemplate ?? '';
    const todayValue = board.dataset.today ?? '';
    const columnLists = Array.from(board.querySelectorAll('[data-task-list]'));
    const userFilter = board.querySelector('[data-user-filter]');
    const startDateFilter = board.querySelector('[data-start-date]');
    const endDateFilter = board.querySelector('[data-end-date]');
    const clearFilterButton = board.querySelector('[data-clear-filter]');
    const modalPanel = board.querySelector('[data-task-modal-panel]');
    const modalHandle = board.querySelector('[data-task-modal-drag-handle]');
    let activeCard = null;
    let dragging = false;

    const taskCards = () => Array.from(board.querySelectorAll('[data-task-card]'));

    const getMoveUrl = (taskId) => moveUrlTemplate.replace('__TASK__', String(taskId));

    const visibleCards = (list) => Array.from(list.querySelectorAll('[data-task-card]:not([hidden])'));

    const refreshColumnState = (list) => {
        const column = list.closest('[data-task-column]');
        const emptyState = list.querySelector('[data-task-empty]');
        const countBadge = column?.querySelector('[data-task-count]');
        const cards = visibleCards(list);

        if (emptyState) {
            emptyState.hidden = cards.length > 0;
        }

        if (countBadge) {
            countBadge.textContent = String(cards.length);
        }
    };

    const refreshBoardState = () => {
        columnLists.forEach(refreshColumnState);
    };

    const todayString = () => todayValue || new Date().toISOString().slice(0, 10);

    const syncDateConstraints = () => {
        const today = todayString();

        if (startDateFilter) {
            startDateFilter.max = today;
        }

        if (endDateFilter) {
            endDateFilter.max = today;
            endDateFilter.min = startDateFilter?.value || '';
        }
    };

    const applyUserFilter = () => {
        syncDateConstraints();

        const selectedUserId = userFilter?.value ?? 'all';
        const startDate = startDateFilter?.value ? new Date(`${startDateFilter.value}T00:00:00`) : null;
        const endDate = endDateFilter?.value ? new Date(`${endDateFilter.value}T23:59:59.999`) : null;

        board.querySelectorAll('[data-task-card]').forEach((card) => {
            const cardCreatedAt = card.dataset.taskCreatedAt ? new Date(card.dataset.taskCreatedAt) : null;
            const matchesFilter =
                selectedUserId === 'all' ||
                String(card.dataset.taskUserId ?? '') === selectedUserId;
            const matchesStartDate = !startDate || !cardCreatedAt || cardCreatedAt >= startDate;
            const matchesEndDate = !endDate || !cardCreatedAt || cardCreatedAt <= endDate;

            card.hidden = !(matchesFilter && matchesStartDate && matchesEndDate);
        });

        refreshBoardState();
    };

    const getDragAfterElement = (list, y) => {
        const draggableElements = [...list.querySelectorAll('[data-task-card]:not([hidden]):not(.is-dragging)')];

        return draggableElements.reduce(
            (closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;

                if (offset < 0 && offset > closest.offset) {
                    return {
                        offset,
                        element: child,
                    };
                }

                return closest;
            },
            {
                offset: Number.NEGATIVE_INFINITY,
                element: null,
            }
        ).element;
    };

    const moveTask = async (card, list) => {
        const taskId = card.dataset.taskId;
        const status = list.dataset.taskStatus;
        const position = Array.from(list.querySelectorAll('[data-task-card]:not([hidden])')).indexOf(card);

        const response = await fetch(getMoveUrl(taskId), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                status,
                position,
            }),
        });

        if (!response.ok) {
            throw new Error('Task move failed');
        }
    };

    const centerModal = () => {
        if (!modalPanel) {
            return;
        }

        const viewportWidth = window.innerWidth;
        const width = Math.min(560, Math.max(340, viewportWidth - 24));

        modalPanel.style.width = `${width}px`;
        modalPanel.style.maxWidth = 'calc(100vw - 24px)';
        modalPanel.style.height = 'auto';
        modalPanel.style.maxHeight = 'none';
        modalPanel.style.left = '50%';
        modalPanel.style.top = '50%';
        modalPanel.style.transform = 'translate(-50%, -50%)';
        modalPanel.style.position = 'fixed';
        modalPanel.style.resize = 'both';
        modalPanel.style.overflow = 'hidden';
    };

    const makeModalDraggable = () => {
        if (!modalPanel || !modalHandle) {
            return;
        }

        let pointerId = null;
        let originX = 0;
        let originY = 0;
        let startLeft = 0;
        let startTop = 0;

        const onPointerMove = (event) => {
            if (pointerId === null || event.pointerId !== pointerId) {
                return;
            }

            const nextLeft = startLeft + (event.clientX - originX);
            const nextTop = startTop + (event.clientY - originY);

            modalPanel.style.transform = 'none';
            modalPanel.style.left = `${Math.max(12, nextLeft)}px`;
            modalPanel.style.top = `${Math.max(12, nextTop)}px`;
        };

        const endDrag = (event) => {
            if (pointerId === null || event.pointerId !== pointerId) {
                return;
            }

            pointerId = null;
            dragging = false;
            modalHandle.releasePointerCapture(event.pointerId);
            window.removeEventListener('pointermove', onPointerMove);
            window.removeEventListener('pointerup', endDrag);
            window.removeEventListener('pointercancel', endDrag);
        };

        modalHandle.addEventListener('pointerdown', (event) => {
            if (event.target.closest('button')) {
                return;
            }

            if (dragging) {
                return;
            }

            dragging = true;
            pointerId = event.pointerId;
            const bounds = modalPanel.getBoundingClientRect();

            originX = event.clientX;
            originY = event.clientY;
            startLeft = bounds.left;
            startTop = bounds.top;

            modalPanel.style.transform = 'none';
            modalHandle.setPointerCapture(event.pointerId);
            window.addEventListener('pointermove', onPointerMove);
            window.addEventListener('pointerup', endDrag);
            window.addEventListener('pointercancel', endDrag);
        });
    };

    board.addEventListener('task-modal-open', () => {
        window.requestAnimationFrame(centerModal);
    });

    if (userFilter) {
        userFilter.addEventListener('change', applyUserFilter);
    }

    if (startDateFilter) {
        startDateFilter.addEventListener('change', () => {
            syncDateConstraints();
            applyUserFilter();
        });
    }

    if (endDateFilter) {
        endDateFilter.addEventListener('change', () => {
            syncDateConstraints();
            applyUserFilter();
        });
    }

    if (clearFilterButton) {
        clearFilterButton.addEventListener('click', () => {
            if (userFilter) {
                userFilter.value = 'all';
            }

            if (startDateFilter) {
                startDateFilter.value = '';
            }

            if (endDateFilter) {
                endDateFilter.value = '';
            }

            applyUserFilter();
        });
    }

    taskCards().forEach((card) => {
        card.addEventListener('dragstart', () => {
            if (card.hidden) {
                return;
            }

            activeCard = card;
            card.classList.add('is-dragging', 'opacity-70');
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('is-dragging', 'opacity-70');
            refreshBoardState();
            activeCard = null;
        });
    });

    columnLists.forEach((list) => {
        list.addEventListener('dragover', (event) => {
            event.preventDefault();

            if (!activeCard || activeCard.hidden) {
                return;
            }

            const afterElement = getDragAfterElement(list, event.clientY);

            if (afterElement == null) {
                list.appendChild(activeCard);
            } else {
                list.insertBefore(activeCard, afterElement);
            }
        });

        list.addEventListener('drop', async (event) => {
            event.preventDefault();

            if (!activeCard || activeCard.hidden) {
                return;
            }

            try {
                await moveTask(activeCard, list);
                refreshBoardState();
            } catch (error) {
                console.error(error);
                window.location.reload();
            }
        });
    });

    makeModalDraggable();
    syncDateConstraints();
    refreshBoardState();
    applyUserFilter();
};

document.addEventListener('DOMContentLoaded', initTaskBoard);
