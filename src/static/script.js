document.addEventListener('DOMContentLoaded', () => {
    const searchTypeSelect = document.getElementById('searchType');
    const searchInput = document.getElementById('searchInput');

    searchTypeSelect.addEventListener('change', function() {
        const selected = this.value;

        if (selected === 'id' || selected === 'age') {
            searchInput.type = 'number';
            searchInput.placeholder = 'Enter a number';
        } else {
            searchInput.type = 'text';
            if (selected === 'dni') {
                searchInput.placeholder = 'Enter DNI';
            } else if (selected === 'name') {
                searchInput.placeholder = 'Enter Name';
            } else if (selected === 'surname') {
                searchInput.placeholder = 'Enter Surname';
            } else {
                searchInput.placeholder = '';
            }
        }
        searchInput.value = '';
    });
});
