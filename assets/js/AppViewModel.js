function AppViewModel() {
    this.todo = new TodoViewModel();
    this.settings = new SettingsViewModel();
}

document.addEventListener('DOMContentLoaded', function () {
    ko.applyBindings(new AppViewModel());
});
