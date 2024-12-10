document.addEventListener('DOMContentLoaded', function () {
    const toggleCategoriesButton = document.getElementById('toggleCategories');
    const closeCategoriesButton = document.getElementById('closeCategories');
    const categoryFilter = document.getElementById('categoryFilter');

    // カテゴリーフィルタの表示
    toggleCategoriesButton.addEventListener('click', () => {
        categoryFilter.style.display = 'block';
        toggleCategoriesButton.style.display = 'none';
        closeCategoriesButton.style.display = 'inline';
    });

    // カテゴリーフィルタの非表示
    closeCategoriesButton.addEventListener('click', () => {
        categoryFilter.style.display = 'none';
        toggleCategoriesButton.style.display = 'inline';
        closeCategoriesButton.style.display = 'none';
    });
});