const initEmploymentFilters = () => {
    const filterWrap = document.querySelector('[data-employment-filter]');
    const listingsWrap = document.querySelector('[data-employment-listings]');
    const emptyState = document.querySelector('[data-employment-empty]');

    if (!filterWrap || !listingsWrap) {
        return;
    }

    const filterButtons = Array.from(filterWrap.querySelectorAll('[data-filter]'));
    const cards = Array.from(listingsWrap.querySelectorAll('[data-location]'));

    const applyFilter = (value) => {
        let visibleCount = 0;

        cards.forEach((card) => {
            const matches = value === 'all' || card.dataset.location === value;
            card.classList.toggle('hidden', !matches);

            if (matches) {
                visibleCount += 1;
            }
        });

        filterButtons.forEach((button) => {
            button.classList.toggle('is-active', button.dataset.filter === value);
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }
    };

    filterWrap.addEventListener('click', (event) => {
        const button = event.target.closest('[data-filter]');

        if (!button) {
            return;
        }

        applyFilter(button.dataset.filter);
    });

    applyFilter('all');
};

export default initEmploymentFilters;
