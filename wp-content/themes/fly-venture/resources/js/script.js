const initTampaLoadMore = () => {
  const section = document.querySelector('.tempa-tour-wrap');

  if (!section) {
    return;
  }

  const grid        = document.querySelector('#tampa-tours-grid');
  const wrapEl      = document.querySelector('#tampa-load-more-wrap');
  const btn         = document.querySelector('#tampa-load-more');

  if (!grid || !btn) {
    return;
  }

  const ajaxUrl = section.dataset.ajaxUrl || '';
  const nonce   = btn.dataset.nonce       || '';

  let offset   = parseInt(btn.dataset.offset,   10) || 0;
  let perPage  = parseInt(btn.dataset.perPage,   10) || 5;
  let currentCat  = btn.dataset.category;
  let total    = parseInt(btn.dataset.total,     10) || 0;
  let loading  = false;

  btn.addEventListener('click', () => {
    if (loading) return;

    loading = true;
    btn.disabled = true;
    btn.textContent = 'Loading...';

    jQuery.ajax({
      url:      ajaxUrl,
      type:     'POST',
      dataType: 'json',
      data: {
        action:   'flyventure_load_more_tours',
        nonce:    nonce,
        offset:   offset,
        per_page: perPage,
        category: currentCat,
      },
    })
      .done((response) => {
        if (response && response.success && response.data.html) {
          grid.insertAdjacentHTML('beforeend', response.data.html);
          offset += perPage;

          // Hide button when all tours loaded
          if (offset >= total) {
            wrapEl && (wrapEl.style.display = 'none');
          }
        }
      })
      .always(() => {
        loading      = false;
        btn.disabled = false;
        btn.textContent = btn.dataset.loadMoreText || 'Load More';
      });
  });
};


export default initTampaLoadMore;
