/* منطق صفحة المتجر: فلترة، بحث، وترتيب */

(function () {
  'use strict';

  var params = new URLSearchParams(window.location.search);
  var state = {
    category: params.get('category') || '',
    maxPrice: 9000,
    search: '',
    sort: 'default'
  };

  function buildCategoryFilters() {
    var wrap = document.getElementById('category-filters');
    CATEGORIES.forEach(function (c) {
      var label = document.createElement('label');
      label.innerHTML =
        '<input type="radio" name="category" value="' + c.id + '"' + (state.category === c.id ? ' checked' : '') + '> ' +
        '<i class="' + c.icon + '" style="width:18px; color:var(--color-accent-dark);"></i> ' + c.name;
      wrap.appendChild(label);
    });
    if (!state.category) {
      document.querySelector('input[name="category"][value=""]').checked = true;
    }
    document.querySelectorAll('input[name="category"]').forEach(function (input) {
      input.addEventListener('change', function () {
        state.category = input.value;
        render();
      });
    });
  }

  function applyFilters() {
    return PRODUCTS.filter(function (p) {
      if (state.category && p.category !== state.category) return false;
      if (p.price > state.maxPrice) return false;
      if (state.search && p.name.indexOf(state.search) === -1 && p.description.indexOf(state.search) === -1) return false;
      return true;
    }).sort(function (a, b) {
      if (state.sort === 'price-asc') return a.price - b.price;
      if (state.sort === 'price-desc') return b.price - a.price;
      if (state.sort === 'rating') return b.rating - a.rating;
      return 0;
    });
  }

  function render() {
    var results = applyFilters();
    document.getElementById('results-count').textContent = results.length;
    renderInto('#store-grid', results.map(productCardHTML));
    document.getElementById('empty-state').style.display = results.length ? 'none' : 'block';
    if (typeof gsap !== 'undefined') {
      gsap.fromTo('#store-grid .product-card', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.5, stagger: 0.05, ease: 'power2.out' });
    }
  }

  function renderNewestProducts() {
    var newest = PRODUCTS.slice(-8).reverse();
    renderInto('#newest-products', newest.map(productCardHTML));
  }

  document.addEventListener('DOMContentLoaded', function () {
    buildCategoryFilters();
    renderNewestProducts();

    var priceRange = document.getElementById('price-range');
    var priceValue = document.getElementById('price-range-value');
    priceRange.addEventListener('input', function () {
      state.maxPrice = parseInt(priceRange.value, 10);
      priceValue.textContent = priceRange.value;
      render();
    });

    document.getElementById('search-input').addEventListener('input', function (e) {
      state.search = e.target.value.trim();
      render();
    });

    document.getElementById('sort-select').addEventListener('change', function (e) {
      state.sort = e.target.value;
      render();
    });

    document.getElementById('reset-filters').addEventListener('click', function () {
      state = { category: '', maxPrice: 9000, search: '', sort: 'default' };
      document.querySelector('input[name="category"][value=""]').checked = true;
      priceRange.value = 9000;
      priceValue.textContent = 9000;
      document.getElementById('search-input').value = '';
      document.getElementById('sort-select').value = 'default';
      render();
    });

    render();
  });
})();
