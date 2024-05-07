window.addEventListener('DOMContentLoaded', event => {
    const form = document.getElementById('omni_search_form');
    const button = document.getElementById('get_results');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const resultsDiv = document.getElementById('results');
    const limit = parseInt(omni_ajax.answers_per_page);
    let offset = 0;
    const searchFunc = async function () {

        const query = document.getElementById('query').value;
        if(query.length === 0)
            return false;

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
                console.log(data.data);
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
        state ? prevButton.setAttribute('disabled', true) : prevButton.removeAttribute('disabled');
        state ? nextButton.setAttribute('disabled', true) : nextButton.removeAttribute('disabled');
        button.innerHTML = text;
        prevButton.innerHTML = 'Prev';
        nextButton.innerHTML = 'Next';

    }

    const createElement = function (element, text) {
        const newElement = document.createElement(element);
        newElement.textContent = text;

        return newElement;
    }

    const updateResultsDiv = function (data) {

        data.data.forEach(item => {
            const div = document.createElement('div');

            let title = createElement('h3');
            let link = createElement('a', item.title);
            link.href = item.url;
            title.append(link);
            div.append(title);

            div.append(createElement('p', item.short_description));


            resultsDiv.appendChild(div);
        });
    }

    const updateButtonVisibility = function (data) {
        const numOfResults = data.data.length;
        prevButton.style.display = offset <= 0 ? 'none' : 'inline';
        nextButton.style.display = numOfResults < limit ? 'none' : 'inline';
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        searchFunc();
    });

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