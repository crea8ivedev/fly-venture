export const initGiftCardTabs = () => {
    const section = document.querySelector('.ctm-popular-gift');
    if (!section) {return;}

    const buttons = Array.from(section.querySelectorAll('.gift-card-tab-btn[data-target]'));
    const panels = Array.from(section.querySelectorAll('.gift-card-panel[data-tab]'));

    if (!buttons.length || !panels.length) {return;}

    /*
     * NOTE: panels use display:flex from .gift-card-panel (unlayered CSS) which has
     * higher cascade priority than @layer utilities where Tailwind's .hidden lives.
     * Direct style.display assignment (inline style) overrides all CSS layers reliably.
     */
    const switchTab = (target) => {
        buttons.forEach((btn) => {
            btn.classList.toggle('active', btn.dataset.target === target);
            btn.setAttribute('aria-selected', btn.dataset.target === target ? 'true' : 'false');
        });
        panels.forEach((panel) => {
            const isActive = panel.dataset.tab === target;
            panel.classList.toggle('active', isActive);
            panel.style.display = isActive ? '' : 'none';
        });
    };

    buttons.forEach((btn) => {
        btn.addEventListener('click', () => {
            if (btn.dataset.target) {switchTab(btn.dataset.target);}
        });
    });

    // Activate the tab that has the .active class, or the first tab
    const defaultBtn = buttons.find((b) => b.classList.contains('active')) || buttons[0];
    if (defaultBtn?.dataset.target) {switchTab(defaultBtn.dataset.target);}
};

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
