function countCharacter(inputElement) {
    const maxLength = parseInt(inputElement.getAttribute('data-max-length'), 10);
    const counterId = inputElement.getAttribute('data-counter-id');
    const characterCountDisplay = document.getElementById(counterId);
    const currentLength = inputElement.value.length;

    if (!counterId || isNaN(maxLength)) {
        console.error('Invalid data attributes for:', inputElement);
        return;
    }

    if (currentLength <= maxLength) {
        characterCountDisplay.textContent = `あと${maxLength - currentLength}文字入力できます。`;
        characterCountDisplay.classList.remove('text-red-500');
        characterCountDisplay.classList.add('text-gray-500');
    } else {
        characterCountDisplay.textContent = `${currentLength - maxLength}文字オーバーしています。`;
        characterCountDisplay.classList.remove('text-gray-500');
        characterCountDisplay.classList.add('text-red-500');
    }
}

// ページ読み込み時にすべての入力フィールドを初期化
window.onload = () => {
    document.querySelectorAll('[data-max-length]').forEach(inputElement => countCharacter(inputElement));
};
// 投稿フォームが開かれるたびにすべての入力フィールドを再初期化
if (document.getElementById('toggleComments')) {
    document.getElementById('toggleComments').addEventListener('click', () => {
        document.querySelectorAll('[data-max-length]').forEach(inputElement => countCharacter(inputElement));
    });
}