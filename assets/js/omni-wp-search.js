window.addEventListener('DOMContentLoaded', event => {
    const button = document.getElementById('get_results');
    const resultsDiv = document.getElementById('results');
    const loadMoreButton = document.getElementById('load_more');
    loadMoreButton.style.display = 'none';
    let offset = 0;
    const limit = parseInt(omni_ajax.answers_per_page);

    // Helper function to debounce
    const debounce = (func, wait) => {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        }
    }

    // Helper function to clear the result div
    const clearResultsDiv = () => {
        while (resultsDiv.firstChild) {
            resultsDiv.removeChild(resultsDiv.firstChild);
        }
    }

    const searchFunc = async function () {
        const query = document.getElementById('query').value; // Let's assume your query is coming from some input field
        buttonDisabledState(true, '<span class="spin dashicons dashicons-update"></span>');

        const postData = new URLSearchParams({
            'action': 'omni_search_handle_query',
            'query': query,
            'offset': offset,
            'limit': limit,
            'nonce': omni_ajax.query_nonce,
        });

        try {
            const response = await fetch(omni_ajax.url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: postData,
            });

            const data = await response.json();

            // Clear existing results if offset is 0
            if (offset === 0) clearResultsDiv();

            if (data.success) {
                buttonDisabledState(false, omni_ajax._search);
                updateResultsDiv(data);
                loadMoreButton.style.display = 'block'
                // Add offset for next page of results
                offset = offset + limit;
            } else {
                resultsDiv.textContent = 'An error occurred while fetching data.';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    const buttonDisabledState = function(state, text) {
        state ? button.setAttribute('disabled', true) : button.removeAttribute('disabled');
        button.innerHTML = text;
    }

    const createElement = function(element, text) {
        const newElement = document.createElement(element);
        newElement.textContent = text;
        return newElement;
    }

    const updateResultsDiv = function (data) {
        data.data.sources[0].forEach(item => {
            const div = document.createElement('div');
            div.append(createElement('h3', item.title));
            div.append(createElement('p', item.pageContent));

            let link = createElement('a', omni_ajax._read_more);
            link.href = item.url;
            div.append(link);

            resultsDiv.appendChild(div);
        });

        if (data.data.results.length > 0 && "0" !== omni_ajax.search_answer) {
            data.data.results.reverse().forEach(result =>
                resultsDiv.prepend(createElement('blockquote', result.text))
            );
            resultsDiv.prepend(createElement('h2', omni_ajax._results));
        }
    }

    button.addEventListener('click', function() {
        offset = 0; // Reset offset to 0 when new search is performed
        searchFunc();
    });

    // Click event for 'Load more' button
    loadMoreButton.addEventListener('click', function () {
        searchFunc();
    });
});