document.addEventListener('DOMContentLoaded', () => {
    setupRelatedPartyAutoComplete('creator');
    setupRelatedPartyAutoComplete('composer');
    setupRelatedPartyAutoComplete('lyric_writer');
    setupRelatedPartyAutoComplete('singer');
    setupRelatedPartyAutoComplete('voice_artist');
});

function setupRelatedPartyAutoComplete(target) {
    const inputName = document.getElementById(`${target}-name`);
    const suggestions = document.getElementById(`${target}-suggestions`);
    const targetId = document.getElementById(`${target}-id`);
    const notFound = document.getElementById(`${target}-not-found`);
    const targetLink = document.getElementById(`${target}-create-link`);

    // DOMが1つでも見つからなければ何もせずに終了
    if (!inputName || !suggestions || !targetId || !notFound || !targetLink) {
        return;
    }

    let timeout = null;

    inputName.addEventListener('input', () => {
        const query = inputName.value.trim();
        clearTimeout(timeout);

        if (query.length === 0) {
            suggestions.classList.add('hidden');
            notFound.classList.add('hidden');
            return;
        }

        timeout = setTimeout(async () => {
            try {
                const response = await fetch(`/${target}/search?q=${encodeURIComponent(query)}`);
                if (!response.ok) throw new Error('予期せぬエラー');

                const data = await response.json();
                suggestions.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = item.name;
                        li.classList.add(
                            'px-4',
                            'py-2',
                            'cursor-pointer',
                            'hover:bg-blue-100',
                            'border-b',
                            'last:border-b-0'
                        );
                        li.addEventListener('click', () => {
                            inputName.value = item.name;
                            targetId.value = item.id;
                            suggestions.classList.add('hidden');
                            notFound.classList.add('hidden');
                        });
                        suggestions.appendChild(li);
                    });
                    suggestions.classList.remove('hidden');
                    notFound.classList.add('hidden');
                } else {
                    suggestions.classList.add('hidden');
                    targetId.value = '';
                    notFound.classList.remove('hidden');
                    const baseUrl = createLink.dataset.baseUrl;
                    targetLink.href = `${baseUrl}?name=${encodeURIComponent(query)}`;
                }
            } catch (error) {
                console.error('検索中にエラーが発生しました:', error);
                suggestions.classList.add('hidden');
                notFound.classList.add('hidden');
            }
        }, 300);
    });
}