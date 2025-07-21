document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('csvTable');
    const searchIcon = document.getElementById('search-icon-csv');
    const closeIcon = document.getElementById('close-icon-csv');

    if (table) {
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        function filterTable() {
            const filter = searchInput.value.toLowerCase();
            
            if (filter.length > 0) {
                searchIcon.classList.add('d-none');
                closeIcon.classList.add('active');

                let hasVisibleRows = false;

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let found = false;

                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }

                    if (found) {
                        rows[i].classList.remove('d-none');
                        hasVisibleRows = true;
                    } else {
                        rows[i].classList.add('d-none');
                    }
                }

                // Optional: Display a "No results found" message if no rows are visible
                const noResultsRow = table.querySelector('tbody .no-results');
                if (!hasVisibleRows) {
                    if (!noResultsRow) {
                        const noResultsMessage = document.createElement('p');
                        noResultsMessage.classList.add('no-results');
                        noResultsMessage.innerHTML = `No hay resultados`;
                        table.querySelector('tbody').appendChild(noResultsMessage);
                    }
                } else if (noResultsRow) {
                    noResultsRow.remove();
                }
                
            } else {
                searchIcon.classList.remove('d-none');
                closeIcon.classList.remove('active');

                for (let i = 0; i < rows.length; i++) {
                    rows[i].classList.remove('d-none');
                }

                // Remove any "No results found" message
                const noResultsRow = table.querySelector('tbody .no-results');
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        }

        // Apply the filter on keyup event
        searchInput.addEventListener('keyup', filterTable);

        // Clear input and show all rows on closeIcon click
        closeIcon.addEventListener('click', function () {
            searchInput.value = "";
            filterTable();
        });

    }
});