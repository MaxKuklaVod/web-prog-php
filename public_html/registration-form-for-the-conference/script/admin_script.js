document.addEventListener('DOMContentLoaded', function() {
    // Функция для выбора всех чекбоксов
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.getElementsByName('applications[]');
            for (let i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = selectAllCheckbox.checked;
            }
        });
    }
});