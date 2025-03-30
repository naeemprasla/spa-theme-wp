// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // First define all functions
    const setupLoader = () => {
      // Create loader if it doesn't exist
      if (!document.getElementById('spa-loading')) {
        const loader = document.createElement('div');
        loader.id = 'spa-loading';
        loader.innerHTML = '<div class="spinner"></div>';
        document.body.appendChild(loader);
      }
      
      // Hide loader when content loads
      document.addEventListener('spa:content-loaded', hideLoader);
      document.addEventListener('spa:transition-complete', hideLoader);
    };
  
    const showLoader = () => {
      const loader = document.getElementById('spa-loading');
      if (loader) {
        loader.classList.add('active');
        document.getElementById('spa-content').classList.add('loading');
      }
    };
  
    const hideLoader = () => {
      const loader = document.getElementById('spa-loading');
      if (loader) {
        loader.classList.remove('active');
        document.getElementById('spa-content').classList.remove('loading');
      }
    };
  
    const shouldHandleAsSPA = () => {
      // Don't handle admin or API requests as SPA
      if (window.location.pathname.includes('/wp-admin') || 
          window.location.pathname.includes('/wp-json')) {
        return false;
      }
      return document.getElementById('spa-content') !== null;
    };
  
    const renderContent = (data) => {
      const content = document.getElementById('spa-content');
      if (!content) return;
      
      content.innerHTML = `
        <h1>${data.title}</h1>
        <div class="page-content">${data.content}</div>
      `;
      
      if (data.acf && typeof ACFRenderer !== 'undefined') {
        ACFRenderer.render(content, data.acf);
      }
      
      // Update page title
      const siteName = document.title.split(' | ')[1] || 'SPA Site';
      document.title = `${data.title} | ${siteName}`;
    };
  
    const updateActiveNav = () => {
      const currentPath = window.location.pathname;
      document.querySelectorAll('[data-spa-link]').forEach(link => {
        const linkPath = new URL(link.href).pathname;
        link.classList.toggle('active', linkPath === currentPath);
      });
    };
  
    const showErrorState = () => {
      const content = document.getElementById('spa-content');
      if (content) {
        content.innerHTML = `
          <div class="error">
            <h2>Error Loading Content</h2>
            <p>Please try again later.</p>
            <a href="${spaConfig.siteUrl}" data-spa-link>Return Home</a>
          </div>
        `;
      }
    };
  
    const loadInitialContent = () => {
      const path = window.location.pathname;
      const pageSlug = path.split('/').filter(Boolean).pop() || 'home';
      
      showLoader();
      
      fetch(`${spaConfig.apiUrl}page-by-slug/${pageSlug}`)
        .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.json();
        })
        .then(data => {
          renderContent(data);
          updateActiveNav();
          document.dispatchEvent(new CustomEvent('spa:content-loaded'));
        })
        .catch(error => {
          console.error('Page load error:', error);
          showErrorState();
        })
        .finally(() => {
          hideLoader();
        });
    };
  
    const setupNavigation = () => {
      document.addEventListener('click', (e) => {
        const link = e.target.closest('[data-spa-link]');
        if (link) {
          e.preventDefault();
          const url = new URL(link.href);
          if (url.pathname !== window.location.pathname) {
            showLoader();
            history.pushState(null, null, url.pathname);
            loadInitialContent();
          }
        }
      });
  
      // Handle browser back/forward
      window.addEventListener('popstate', () => {
        showLoader();
        loadInitialContent();
      });
    };
  
    const setupSPA = () => {
      // Initialize Barba if available
      if (typeof Barba !== 'undefined') {
        new SPATransitions();
      }
    };
  
    // Now initialize everything
    ACFRenderer.init();
    setupLoader();
    setupNavigation();
    setupSPA();
  
    // Initial content load
    if (shouldHandleAsSPA()) {
      loadInitialContent();
    }
  
    // Make functions available globally if needed
    window.loadPageContent = loadInitialContent;
    window.showLoader = showLoader;
    window.hideLoader = hideLoader;
  });