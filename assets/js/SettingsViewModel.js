function SettingsViewModel() {
    let self = this;

    self.enableNotifications = ko.observable(false);
    self.notificationMethod = ko.observable('none');

    self.emailText = ko.observable('');
    self.telegramText = ko.observable('');

    self.loadSettings = async () => {
        try {
            const response = await axios.get('./api/settings/get_settings.php');
            const data = response.data;
            self.enableNotifications(data.notifEnabled == 1);
            self.notificationMethod(data.notification_type);
            self.emailText(data.email || '');
            self.telegramText(data.telegram_chat_id || '');
        } catch (error) {
            console.error('Ошибка при загрузке настроек:', error);
        }
    };

    self.saveSettings = async () => {
        try {
            await axios.post('./api/settings/save_settings.php', {
                enable_notifications: self.enableNotifications() ? 1 : 0,
                notification_method: self.notificationMethod(),
                email: self.emailText(),
                telegram: self.telegramText(),
            });
            alert('Настройки сохранены');
            self.loadSettings();
        } catch (error) {
            alert('Ошибка при сохранении настроек');
        }
    };

    self.loadSettings();
}

// ko.applyBindings(new SettingsViewModel(), document.querySelector('.settings-block'));
