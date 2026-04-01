export const initTourOverview = () => {
    const tabSections = document.querySelectorAll('.tour-overview-section');

    tabSections.forEach((section) => {
        const tabButtons = Array.from(section.querySelectorAll('.tab-nav .tab-btn'));
        const tabContents = Array.from(section.querySelectorAll('.tab-content[data-tab]'));

        if (!tabButtons.length || !tabContents.length) {
            return;
        }

        const setActiveTab = (target) => {
            tabButtons.forEach((button) => {
                const isActive = button.dataset.target === target;
                button.classList.toggle('active', isActive);
                button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            tabContents.forEach((content) => {
                const isActive = content.dataset.tab === target;
                content.classList.toggle('active', isActive);
                content.classList.toggle('hidden', !isActive);
            });
        };

        tabButtons.forEach((button, index) => {
            button.setAttribute('type', 'button');
            button.setAttribute('aria-selected', 'false');

            button.addEventListener('click', () => {
                const target = button.dataset.target;

                if (!target) {
                    return;
                }

                setActiveTab(target);
            });

            if (index === 0 && !button.dataset.target) {
                button.dataset.target = tabContents[0]?.dataset.tab || '';
            }
        });

        const defaultTarget = tabButtons.find((button) => button.classList.contains('active'))?.dataset.target
            || tabButtons[0]?.dataset.target
            || tabContents[0]?.dataset.tab;

        if (defaultTarget) {
            setActiveTab(defaultTarget);
        }
    });
};
