document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.querySelector('input[name="title"]');
    const genreInput = document.querySelector('input[name="genre"]');
    const yearInput  = document.querySelector('input[name="year"]');
    const tbody      = document.getElementById('books-body');

    if (!titleInput || !genreInput || !yearInput || !tbody) {
        return;
    }

    function performSearch() {
        const params = new URLSearchParams();
        if (titleInput.value.trim() !== '') {
            params.append('title', titleInput.value.trim());
        }
        if (genreInput.value.trim() !== '') {
            params.append('genre', genreInput.value.trim());
        }
        if (yearInput.value.trim() !== '') {
            params.append('year', yearInput.value.trim());
        }

        fetch('ajax/search_books.php?' + params.toString(), {
            method: 'GET',
            credentials: 'same-origin'
        })
            .then(response => response.text())
            .then(html => {
                tbody.innerHTML = html;
            })
            .catch(err => {
                console.error('AJAX search error:', err);
            });
    }

    // Trigger search on typing / change
    titleInput.addEventListener('input', performSearch);
    genreInput.addEventListener('input', performSearch);
    yearInput.addEventListener('input', performSearch);
});
