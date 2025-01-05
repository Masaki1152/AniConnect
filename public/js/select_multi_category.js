document.addEventListener('DOMContentLoaded', () => {
    const optionList = document.getElementById('custom-multi-select-list');
    const selectedCategoriesContainer = document.getElementById('selected-categories-container');
    const maxSelections = 3;

    // 初期値の取得
    let selectedValues = Array.from(selectedCategoriesContainer.querySelectorAll('input'))
        .map(input => input.value);

    // 初期値に基づいて背景色を設定
    selectedValues.forEach((categoryId) => {
        const option = optionList.querySelector(`[data-value="${categoryId}"]`);
        if (option) {
            option.classList.add('bg-gray-500', 'text-white');
        }
    });

    // セレクトボックスにクリックイベントを追加
    optionList.addEventListener('click', (event) => {
        const option = event.target;

        if (option.classList.contains('custom-option')) {
            const value = option.dataset.value;

            if (selectedValues.includes(value)) {
                // 選択解除
                selectedValues = selectedValues.filter(v => v !== value);
                option.classList.remove('bg-gray-500', 'text-white');
                // hidden inputを削除
                const inputToRemove = selectedCategoriesContainer.querySelector(`input[value="${value}"]`);
                if (inputToRemove) {
                    selectedCategoriesContainer.removeChild(inputToRemove);
                }
            } else {
                if (selectedValues.length >= maxSelections) {
                    // 最大選択数を超えた場合
                    alert(`カテゴリーは最大${maxSelections}個まで選択できます。`);
                } else {
                    // 新規選択
                    selectedValues.push(value);
                    option.classList.add('bg-gray-500', 'text-white');

                    // hidden inputを追加
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = 'work_review[categories_array][]';
                    newInput.value = value;
                    selectedCategoriesContainer.appendChild(newInput);
                }
            }
        }
    });
});