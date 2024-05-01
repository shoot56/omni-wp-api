window.addEventListener('DOMContentLoaded', event => {
    const button = document.getElementById('get_results');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const resultsDiv = document.getElementById('results');
    let offset = 0;
    const limit = 6;

    const searchFunc = async function () {
        const query = document.getElementById('query').value;
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
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: postData
            });
            const data = await response.json();

            // Clear existing results
            resultsDiv.innerHTML = '';

            if (data.success) {
                buttonDisabledState(false, omni_ajax._search);
                updateResultsDiv(data);

                // Update the visibility states for prev and next buttons
                updateButtonVisibility(data);
            } else {
                resultsDiv.textContent = 'An error occurred while fetching data.';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    const buttonDisabledState = function (state, text) {
        state ? button.setAttribute('disabled', true) : button.removeAttribute('disabled');
        button.innerHTML = text;
    }

    const createElement = function (element, text) {
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

    const updateButtonVisibility = function (data) {
        const numOfResults = data.data.sources[0].length;
        prevButton.style.display = offset <= 0 ? 'none' : 'inline';
        nextButton.style.display = numOfResults < limit ? 'none' : 'inline';
    }

    button.addEventListener('click', searchFunc);

    prevButton.addEventListener('click', function () {
        offset = Math.max(0, offset - limit);
        searchFunc();
    });

    nextButton.addEventListener('click', function () {
        offset = offset + limit;
        searchFunc();
    });

    // initialize button visibility
    prevButton.style.display = 'none';
    nextButton.style.display = 'none';
});