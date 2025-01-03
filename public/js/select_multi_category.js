document.addEventListener('DOMContentLoaded', () => {
    const selectBox = document.getElementById('custom-multi-select');
    const optionList = document.getElementById('custom-multi-select-list');
    const maxSelections = 3;
    let selectedValues = [];

    // 初期化: 既に選択されている項目を配列に追加
    // selectedValues = Array.from(selectBox.options)
    //     .filter(option => option.selected)
    //     .map(option => option.value);

    // セレクトボックスにクリックイベントを追加
    optionList.addEventListener('click', (event) => {
        const option = event.target;

        if (option.classList.contains('custom-option')) {
            const value = option.dataset.value;

            if (selectedValues.includes(value)) {
                // 選択解除
                selectedValues = selectedValues.filter(v => v !== value);
                option.classList.remove('bg-gray-500', 'text-white');
            } else {
                if (selectedValues.length >= maxSelections) {
                    // 最大選択数を超えた場合
                    alert(`カテゴリーは最大${maxSelections}個まで選択できます。`);
                } else {
                    // 新規選択
                    selectedValues.push(value);
                    option.classList.add('bg-gray-500', 'text-white');
                }
            }
        }
    });
});