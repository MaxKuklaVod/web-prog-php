// public/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    // Можно добавить интерактивности, например, подтверждение удаления (если будет)
    // или улучшение работы с фильтром дат, но пока оставляем простым.

    // Пример: выделение активного фильтра в навигации
    const currentUrl = new URL(window.location.href);
    const filterParam = currentUrl.searchParams.get("filter");
    const dateFilterParam = currentUrl.searchParams.get("date_filter");

    document.querySelectorAll('header nav a').forEach(link => {
        const linkUrl = new URL(link.href);
        const linkFilter = linkUrl.searchParams.get("filter");
        if (filterParam === linkFilter && filterParam !== 'date') {
            link.classList.add('active');
        }
    });
    // Если это главная (без filter) или filter=current (т.к. это дефолт)
     if (!filterParam && !dateFilterParam) {
        const mainLink = document.querySelector('header nav a[href="index.php"]');
        if(mainLink) mainLink.classList.add('active');
    } else if (filterParam === 'current' && !dateFilterParam) {
        const currentLink = document.querySelector('header nav a[href="index.php?filter=current"]');
         if(currentLink) currentLink.classList.add('active');
    }

    // Для поля date_filter_nav нет смысла делать active, т.к. это сама форма
});