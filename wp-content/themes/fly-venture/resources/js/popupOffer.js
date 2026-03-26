const SCROLL_TRIGGER_MIN = 0.6;
const POPUP_SHOWN_SESSION_KEY = 'fv_offer_popup_shown';
const BOOK_NOW_CLICKED_SESSION_KEY = 'fv_offer_book_now_clicked';
const PURCHASE_COMPLETED_SESSION_KEY = 'fv_offer_purchase_completed';

function getSessionFlag(key) {
  try {
    return window.sessionStorage.getItem(key) === 'true';
  } catch {
    return false;
  }
}

function setSessionFlag(key) {
  try {
    window.sessionStorage.setItem(key, 'true');
  } catch {
    // Ignore storage errors.
  }
}

function hasCompletedPurchase() {
  if (getSessionFlag(PURCHASE_COMPLETED_SESSION_KEY)) {
    return true;
  }

  const path = window.location.pathname.toLowerCase();
  const search = window.location.search.toLowerCase();

  return /thank|success|confirmation/.test(path) || /paid=true|status=success/.test(search);
}

function shouldSuppressPopup() {
  return (
    getSessionFlag(POPUP_SHOWN_SESSION_KEY) ||
    getSessionFlag(BOOK_NOW_CLICKED_SESSION_KEY) ||
    hasCompletedPurchase()
  );
}

function attachBookNowTracker() {
  document.addEventListener(
    'click',
    (event) => {
      const target = event.target;
      if (!(target instanceof Element)) {
        return;
      }

      const clickable = target.closest('a, button');
      if (!clickable) {
        return;
      }

      const label = (
        `${clickable.getAttribute('aria-label') || ''
        } ${
        clickable.textContent || ''}`
      )
        .toLowerCase()
        .replace(/\s+/g, ' ')
        .trim();

      if (label.includes('book now')) {
        setSessionFlag(BOOK_NOW_CLICKED_SESSION_KEY);
      }
    },
    { passive: true }
  );
}

function exposePurchaseApi() {
  window.FVOffer = window.FVOffer || {};
  window.FVOffer.markPurchased = function markPurchased() {
    setSessionFlag(PURCHASE_COMPLETED_SESSION_KEY);
  };
}

export function initOfferPopup() {
  if (hasCompletedPurchase()) {
    setSessionFlag(PURCHASE_COMPLETED_SESSION_KEY);
  }

  exposePurchaseApi();
  attachBookNowTracker();

  if (shouldSuppressPopup()) {
    return;
  }

  const popup = document.getElementById('fv-offer-popup');
  if (!popup) {
    return;
  }

  const closeTargets = popup.querySelectorAll('[data-popup-close]');

  let isPopupVisible = false;
  let hasShownOnThisPage = false;

  const showPopup = () => {
    if (isPopupVisible || hasShownOnThisPage || shouldSuppressPopup()) {
      return;
    }

    popup.classList.add('is-open');
    popup.setAttribute('aria-hidden', 'false');
    document.body.classList.add('fv-popup-open');
    isPopupVisible = true;
    hasShownOnThisPage = true;
    setSessionFlag(POPUP_SHOWN_SESSION_KEY);
  };

  const hidePopup = () => {
    if (!isPopupVisible) {
      return;
    }

    popup.classList.remove('is-open');
    popup.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('fv-popup-open');
    isPopupVisible = false;
  };

  closeTargets.forEach((target) => {
    target.addEventListener('click', hidePopup);
  });

  const onScroll = () => {
    if (hasShownOnThisPage || isPopupVisible || shouldSuppressPopup()) {
      window.removeEventListener('scroll', onScroll);
      return;
    }

    const scrollableHeight =
      document.documentElement.scrollHeight - window.innerHeight;

    if (scrollableHeight <= 0) {
      return;
    }

    const scrollProgress = window.scrollY / scrollableHeight;

    if (scrollProgress >= SCROLL_TRIGGER_MIN) {
      showPopup();
      window.removeEventListener('scroll', onScroll);
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
}
