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
      ? `<a href="${post.permalink}" class="blog-card__thumbnail" aria-label="${escAttr(post.title)}" tabindex="-1">
           <img src="${post.thumbnail.url}" alt="${escAttr(post.thumbnail.alt)}" class="w-full h-full object-cover" loading="lazy">
         </a>`
      : '';

    const cat = post.category
      ? `<a href="${post.category.url}" class="blog-card__category">${escHtml(post.category.name)}</a>`
      : '';

    return `<article class="blog-card">
      ${thumb}
      <div class="blog-card__body">
        ${cat}
        <h2 class="blog-card__title">
          <a href="${post.permalink}">${escHtml(post.title)}</a>
        </h2>
        <div class="blog-card__meta">
          <span class="blog-card__author">
            <a href="${post.author_url}">${escHtml(post.author)}</a>
          </span>
          <time class="blog-card__date" datetime="${escAttr(post.date_time)}">${post.date}</time>
        </div>
        ${post.excerpt ? `<div class="blog-card__excerpt">${escHtml(post.excerpt)}</div>` : ''}
      </div>
    </article>`;
  };

  const buildPagination = (maxPages, paged) => {
    if (maxPages <= 1) return '';

    let html = '';

    if (paged > 1) {
      html += `<button class="blog-page-btn" data-page="${paged - 1}" aria-label="Previous"><span>&laquo;</span> Previous</button>`;
    }

    if (paged < maxPages) {
      html += `<button class="blog-page-btn" data-page="${paged + 1}" aria-label="Next">Next <span>&raquo;</span></button>`;
    }

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
