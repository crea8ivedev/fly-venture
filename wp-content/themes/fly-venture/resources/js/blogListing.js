const initBlogListing = () => {
  const section = document.querySelector('.blog-listing-section');

  if (!section) {
    return;
  }

  const grid        = section.querySelector('.blog-listing-grid');
  const pagination  = section.querySelector('.blog-pagination');
  const emptyMsg    = section.querySelector('.blog-listing-empty');
  const searchInput = section.querySelector('.blog-search-input');
  const searchBtn   = section.querySelector('.blog-search-btn');
  const categorySelect = section.querySelector('.blog-category-select');

  const ajaxUrl = section.dataset.ajaxUrl || '';
  const nonce   = section.dataset.nonce   || '';

  if (!ajaxUrl || !nonce) {
    return;
  }

  let currentPage     = 1;
  let currentSearch   = '';
  let currentCategory = 0;
  let debounceTimer   = null;
  let xhr             = null;

  const buildPostCard = (post) => {
    const thumb = post.thumbnail
      ? `<img src="${post.thumbnail.url}" alt="${escAttr(post.thumbnail.alt)}" height="250" width="375" loading="lazy">`
      : '';

    const pill = post.category
      ? `<span class="popular-tour-pill">${escHtml(post.category.name)}</span>`
      : '';

    const excerpt = post.excerpt
      ? `<p class="mb-20">${escHtml(post.excerpt)}</p>`
      : '';

    return `<div class="popular-tour-card">
      <div class="popular-tour-card-media">
        ${thumb}
        ${pill}
      </div>
      <div class="popular-tour-card-body">
        <div class="top-content">
          <h4>${escHtml(post.title)}</h4>
          <div class="popular-tour-metas">
            <div class="flex gap-10 items-center mb-16">
              <div class="flex items-center gap-6 w-1/2">
                <div class="author">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.64453 9.02087C6.99175 8.3681 6.66536 7.58337 6.66536 6.66671C6.66536 5.75004 6.99175 4.96532 7.64453 4.31254C8.29731 3.65976 9.08203 3.33337 9.9987 3.33337C10.9154 3.33337 11.7001 3.65976 12.3529 4.31254C13.0056 4.96532 13.332 5.75004 13.332 6.66671C13.332 7.58337 13.0056 8.3681 12.3529 9.02087C11.7001 9.67365 10.9154 10 9.9987 10C9.08203 10 8.29731 9.67365 7.64453 9.02087ZM3.33203 15V14.3334C3.33203 13.8612 3.4537 13.4273 3.69703 13.0317C3.94036 12.6362 4.26314 12.3339 4.66536 12.125C5.52648 11.6945 6.40148 11.3717 7.29036 11.1567C8.17925 10.9417 9.08203 10.8339 9.9987 10.8334C10.9154 10.8328 11.8181 10.9406 12.707 11.1567C13.5959 11.3728 14.4709 11.6956 15.332 12.125C15.7348 12.3334 16.0579 12.6356 16.3012 13.0317C16.5445 13.4278 16.6659 13.8617 16.6654 14.3334V15C16.6654 15.4584 16.5023 15.8509 16.1762 16.1775C15.8501 16.5042 15.4576 16.6673 14.9987 16.6667H4.9987C4.54036 16.6667 4.14814 16.5037 3.82203 16.1775C3.49592 15.8514 3.33259 15.4589 3.33203 15Z" fill="#2872A3"/></svg>
                </div>
                <div class="content"><p>${escHtml(post.author)}</p></div>
              </div>
              <div class="flex items-center gap-6 w-1/2">
                <div class="author">
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.66797 7.49996C1.66797 5.92829 1.66797 5.14329 2.1563 4.65496C2.64464 4.16663 3.42964 4.16663 5.0013 4.16663H15.0013C16.573 4.16663 17.358 4.16663 17.8463 4.65496C18.3346 5.14329 18.3346 5.92829 18.3346 7.49996C18.3346 7.89246 18.3346 8.08913 18.213 8.21163C18.0905 8.33329 17.893 8.33329 17.5013 8.33329H2.5013C2.1088 8.33329 1.91214 8.33329 1.78964 8.21163C1.66797 8.08913 1.66797 7.89163 1.66797 7.49996ZM1.66797 15C1.66797 16.5716 1.66797 17.3566 2.1563 17.845C2.64464 18.3333 3.42964 18.3333 5.0013 18.3333H15.0013C16.573 18.3333 17.358 18.3333 17.8463 17.845C18.3346 17.3566 18.3346 16.5716 18.3346 15V10.8333C18.3346 10.4408 18.3346 10.2441 18.213 10.1216C18.0905 9.99996 17.893 9.99996 17.5013 9.99996H2.5013C2.1088 9.99996 1.91214 9.99996 1.78964 10.1216C1.66797 10.2441 1.66797 10.4416 1.66797 10.8333V15Z" fill="#2872A3"/><path d="M5 4.99996V2.49996C5 2.03972 5.3731 1.66663 5.83333 1.66663C6.29357 1.66663 6.66667 2.03972 6.66667 2.49996V4.99996C6.66667 5.4602 6.29357 5.83329 5.83333 5.83329C5.3731 5.83329 5 5.4602 5 4.99996ZM13.3333 4.99996V2.49996C13.3333 2.03972 13.7064 1.66663 14.1667 1.66663C14.6269 1.66663 15 2.03972 15 2.49996V4.99996C15 5.4602 14.6269 5.83329 14.1667 5.83329C13.7064 5.83329 13.3333 5.4602 13.3333 4.99996Z" fill="#2872A3"/></svg>
                </div>
                <div class="content"><p>${post.date}</p></div>
              </div>
            </div>
          </div>
          ${excerpt}
        </div>
        <div class="bottom-contents">
          <div class="popular-tour-btns inline-block! w-full">
            <a href="${post.permalink}" class="btn btn-orange w-full" aria-label="Read More" role="link" target="_self">READ MORE</a>
          </div>
        </div>
      </div>
    </div>`;
  };

  const buildPagination = (maxPages, paged) => {
    if (maxPages <= 1) return '';

    let html = '';

    html += `<button class="blog-page-btn blog-page-prev" data-page="${paged - 1}" aria-label="Previous" ${paged <= 1 ? 'disabled' : ''}><span>&laquo;</span> Prev</button>`;

    for (let i = 1; i <= maxPages; i++) {
      html += `<button class="blog-page-btn ${i === paged ? 'active' : ''}" data-page="${i}" aria-label="Page ${i}">${i}</button>`;
    }

    html += `<button class="blog-page-btn blog-page-next" data-page="${paged + 1}" aria-label="Next" ${paged >= maxPages ? 'disabled' : ''}>Next <span>&raquo;</span></button>`;

    return html;
  };

  const setLoading = (loading) => {
    section.classList.toggle('is-loading', loading);
  };

  const fetchPosts = (page = 1) => {
    if (xhr) {
      xhr.abort();
    }

    setLoading(true);

    xhr = jQuery.ajax({
      url:      ajaxUrl,
      type:     'POST',
      dataType: 'json',
      data: {
        action:   'flyventure_blog_listing',
        nonce:    nonce,
        paged:    page,
        search:   currentSearch,
        category: currentCategory,
      },
    });

    xhr.done((response) => {
      if (!response || !response.success) return;

      const { posts, max_num_pages, paged } = response.data;

      if (grid) {
        if (posts.length) {
          grid.innerHTML = posts.map(buildPostCard).join('');
          grid.hidden = false;
          if (emptyMsg) emptyMsg.hidden = true;
        } else {
          grid.innerHTML = '';
          grid.hidden = true;
          if (emptyMsg) emptyMsg.hidden = false;
        }
      }

      if (pagination) {
        pagination.innerHTML = buildPagination(max_num_pages, paged);
        pagination.hidden = max_num_pages <= 1;
        bindPaginationEvents();
      }

      currentPage = paged;
    });

    xhr.always(() => {
      setLoading(false);
      xhr = null;
    });
  };

  const bindPaginationEvents = () => {
    if (!pagination) return;
    pagination.querySelectorAll('.blog-page-btn').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (btn.disabled) return;
        const page = parseInt(btn.dataset.page, 10);
        if (page && page !== currentPage) {
          fetchPosts(page);
          section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });
  };

  // Search
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        currentSearch = searchInput.value.trim();
        currentPage   = 1;
        fetchPosts(1);
      }, 400);
    });
  }

  if (searchBtn) {
    searchBtn.addEventListener('click', () => {
      clearTimeout(debounceTimer);
      currentSearch = searchInput ? searchInput.value.trim() : '';
      currentPage   = 1;
      fetchPosts(1);
    });
  }

  // Category filter
  if (categorySelect) {
    categorySelect.addEventListener('change', () => {
      currentCategory = parseInt(categorySelect.value, 10) || 0;
      currentPage     = 1;
      fetchPosts(1);
    });
  }

  // Bind initial pagination
  bindPaginationEvents();
};

const escHtml = (str) => {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
};

const escAttr = (str) => escHtml(str);

export default initBlogListing;
