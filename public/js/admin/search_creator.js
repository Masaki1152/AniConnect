document.addEventListener('DOMContentLoaded', () => {
    const inputName = document.getElementById('creator-name');
    const suggestions = document.getElementById('creator-suggestions');
    const creatorId = document.getElementById('creator-id');
    const notFound = document.getElementById('creator-not-found');
    const createLink = document.getElementById('creator-create-link');

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
                const response = await fetch(`/creator/search?q=${encodeURIComponent(query)}`);
                if (!response.ok) throw new Error('予期せぬエラー');

                const data = await response.json();
                suggestions.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(creator => {
                        const li = document.createElement('li');
                        li.textContent = creator.name;
                        li.classList.add(
                            'px-4',
                            'py-2',
                            'cursor-pointer',
                            'hover:bg-blue-100',
                            'border-b',
                            'last:border-b-0'
                        );
                        li.addEventListener('click', () => {
                            inputName.value = creator.name;
                            creatorId.value = creator.id;
                            suggestions.classList.add('hidden');
                            notFound.classList.add('hidden');
                        });
                        suggestions.appendChild(li);
                    });
                    suggestions.classList.remove('hidden');
                    notFound.classList.add('hidden');
                } else {
                    suggestions.classList.add('hidden');
                    creatorId.value = '';
                    notFound.classList.remove('hidden');
                    createLink.href = `/creator/create?name=${encodeURIComponent(query)}`;
                }
            } catch (error) {
                console.error('検索中にエラーが発生しました:', error);
                suggestions.classList.add('hidden');
                notFound.classList.add('hidden');
            }
        }, 300);
    });
});