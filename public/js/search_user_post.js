// 検索バーの右端に表示するクリアボタンの表示/非表示
function toggleClearButton() {
    const input = document.getElementById("searchInput");
    const clearButton = document.getElementById("clearButton");

    if (input.value.length > 0) {
        clearButton.classList.remove("hidden");
    } else {
        clearButton.classList.add("hidden");
    }
}

// 検索をクリアする
function clearSearch() {
    const input = document.getElementById("searchInput");
    input.value = "";
    input.focus();
    // ボタンを非表示にする
    toggleClearButton();
}

// デフォルトのカテゴリーを選択した場合
function removeEmptyCategory(select) {
    if (select.value === "") {
        // valueが空ならnameを削除してリクエストに含めない
        select.removeAttribute("name");
    } else {
        // 選択したらnameを復活
        select.setAttribute("name", "category");
    }
}