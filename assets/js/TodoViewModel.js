function TodoViewModel() {
    let self = this;

    self.tasks = ko.observableArray([]);
    self.newTaskTitle = ko.observable('');

    self.loadTasks = async () => {
        try {
            const response = await axios.get('./api/get_tasks.php');
            self.tasks(response.data);
        } catch (error) {
            console.error('Ошибка при загрузке задач:', error);
        }
    };

    self.addTask = async () => {
        const title = self.newTaskTitle().trim();
        if (!title) return;

        try {
            await axios.post('./api/post_task.php', { title: title });
            self.newTaskTitle('');
            self.loadTasks();
        } catch (error) {
            alert('Не удалось добавить задачу');
        }
    };

    self.toggleStatus = async (task) => {
        const newStatus = task.status == 1 ? 0 : 1;

        try {
            await axios.post('./api/toggle_task.php', {
                id: task.id,
                status: newStatus,
            });

            self.loadTasks();
        } catch (error) {
            alert('Ошибка при обновлении статуса');
        }
    };

    self.delete = async (task) => {
        if (!confirm('Удалить эту задачу?')) return;

        try {
            await axios.post('./api/delete_task.php', { id: task.id });
            self.tasks.remove(task);
        } catch (error) {
            alert('Ошибка при удалении');
        }
    };

    self.loadTasks();
}

ko.applyBindings(new TodoViewModel());
