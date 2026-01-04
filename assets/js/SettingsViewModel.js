function TodoViewModel() {
    let self = this;

    self.enableNotifications = ko.observable(false);
    self.notificationMethod = ko.observable('none');

    self.emailText = ko.observable('');
    self.telegramText = ko.observable('');

    self.loadSettings = async () => {
        try {
            const response = await axios.get('./api/get_settings.php');
            const data = response.data;
            self.enableNotifications(data.enable_notifications == 1);
            self.notificationMethod(data.notification_method);
            self.emailText(data.email || '');
            self.telegramText(data.telegram || '');
        } catch (error) {
            console.error('Ошибка при загрузке настроек:', error);
        }
    };

    self.saveSettings = async () => {
        try {
            await axios.post('./api/save_settings.php', {
                enable_notifications: self.enableNotifications() ? 1 : 0,
                notification_method: self.notificationMethod(),
                email: self.emailText(),
                telegram: self.telegramText(),
            });
            alert('Настройки сохранены');
        } catch (error) {
            alert('Ошибка при сохранении настроек');
        }
    };

    self.loadSettings();
}

ko.applyBindings(new TodoViewModel());
