const getPlusIcon = () => `
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.3049 9.88853H9.88853V17.3049H7.41639V9.88853H0V7.41639H7.41639V0H9.88853V7.41639H17.3049V9.88853Z" fill="#2872A3"/>
    </svg>
`;

const getMinusIcon = () => `
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.3049 9.88853H0V7.41639H17.3049V9.88853Z" fill="#2872A3"/>
    </svg>
`;

const setItemState = (item, isOpen, withAnimation) => {
  if (!item) {
    return;
  }

  const answer = item.querySelector('.faq-answer');
  if (!answer) {
    return;
  }

  if (!withAnimation) {
    answer.style.maxHeight = isOpen ? 'none' : '0px';
    return;
  }

  answer.style.overflow = 'hidden';

  if (isOpen) {
    answer.style.maxHeight = '0px';
    void answer.offsetHeight;

    answer.style.maxHeight = `${answer.scrollHeight}px`;
    const onOpenDone = (event) => {
      if (event.propertyName !== 'max-height') {
        return;
      }

      if (item.classList.contains('is-open')) {
        answer.style.maxHeight = 'none';
      }

      answer.removeEventListener('transitionend', onOpenDone);
    };
    answer.addEventListener('transitionend', onOpenDone);
    return;
  }

  const currentHeight = answer.scrollHeight;
  answer.style.maxHeight = `${currentHeight}px`;
  void answer.offsetHeight;

  answer.style.maxHeight = '0px';
};

const initFAQ = () => {
  const section = document.querySelector('.faq-wrapper');

  if (!section) {
    return;
  }

  const questionButtons = section.querySelectorAll('.faq-question');

  if (!questionButtons.length) {
    return;
  }

  const faqItems = section.querySelectorAll('.faq-item');
  faqItems.forEach((item) => {
    const button = item.querySelector('.faq-question');
    const icon = item.querySelector('.faq-icon');
    const answer = item.querySelector('.faq-answer');
    const isExpanded = button?.getAttribute('aria-expanded') === 'true';

    if (answer?.hasAttribute('hidden')) {
      answer.removeAttribute('hidden');
    }

    item.classList.toggle('is-open', Boolean(isExpanded));
    setItemState(item, Boolean(isExpanded), false);

    if (icon) {
      icon.innerHTML = isExpanded ? getMinusIcon() : getPlusIcon();
    }
  });

  questionButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const currentItem = button.closest('.faq-item');
      const isExpanded = button.getAttribute('aria-expanded') === 'true';

      questionButtons.forEach((otherButton) => {
        const otherItem = otherButton.closest('.faq-item');
        const otherIcon = otherButton.querySelector('.faq-icon');
        const isCurrent = otherButton === button;

        if (!isCurrent) {
          otherButton.setAttribute('aria-expanded', 'false');
          otherItem?.classList.remove('is-open');
          setItemState(otherItem, false, true);
        }

        if (otherIcon) {
          otherIcon.innerHTML = isCurrent ? getMinusIcon() : getPlusIcon();
        }
      });

      if (!isExpanded) {
        button.setAttribute('aria-expanded', 'true');
        currentItem?.classList.add('is-open');
        setItemState(currentItem, true, true);

        const icon = button.querySelector('.faq-icon');
        if (icon) {
          icon.innerHTML = getMinusIcon();
        }
      } else {
        button.setAttribute('aria-expanded', 'false');
        currentItem?.classList.remove('is-open');
        setItemState(currentItem, false, true);

        const icon = button.querySelector('.faq-icon');
        if (icon) {
          icon.innerHTML = getPlusIcon();
        }
      }
    });
  });
};

export default initFAQ;
