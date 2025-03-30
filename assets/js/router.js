class SPARouter {
    constructor() {
        this.routes = {
            '/': 'loadHomePage',
            '/page/:id': 'loadPage',
            '/post/:id': 'loadPost'
        };
        this.init();
    }

    init() {
        // Handle initial load
        window.addEventListener('load', () => this.handleRoute());
        
        // Handle navigation
        window.addEventListener('popstate', () => this.handleRoute());
        
        // Delegate link clicks
        document.addEventListener('click', (e) => {
            const link = e.target.closest('[data-spa-link]');
            if (link) {
                e.preventDefault();
                this.navigateTo(link.href);
            }
        });
    }

    navigateTo(url) {
        history.pushState(null, null, url);
        this.handleRoute();
    }

    handleRoute() {
        const path = window.location.pathname;
        const match = this.matchRoute(path);
        
        if (match) {
            this[match.route](match.params);
        } else {
            this.load404();
        }
    }

    matchRoute(path) {
        for (const [route, handler] of Object.entries(this.routes)) {
            const routeParts = route.split('/');
            const pathParts = path.split('/');
            
            if (routeParts.length !== pathParts.length) continue;
            
            const params = {};
            let match = true;
            
            for (let i = 0; i < routeParts.length; i++) {
                if (routeParts[i].startsWith(':')) {
                    params[routeParts[i].substring(1)] = pathParts[i];
                } else if (routeParts[i] !== pathParts[i]) {
                    match = false;
                    break;
                }
            }
            
            if (match) {
                return { route: handler, params };
            }
        }
        return null;
    }

    async loadHomePage() {
        await this.loadPage({ id: spaConfig.homePageId });
    }

    async loadPage({ id }) {
        try {
            document.dispatchEvent(new CustomEvent('spa:loading-start'));
            
            const response = await fetch(`${spaConfig.apiUrl}page/${id}`);
            const data = await response.json();
            
            this.renderPage(data);
            
        } catch (error) {
            console.error('Page load error:', error);
            this.loadError();
        } finally {
            document.dispatchEvent(new CustomEvent('spa:loading-end'));
        }
    }

    async loadPost({ id }) {
        try {
            document.dispatchEvent(new CustomEvent('spa:loading-start'));
            
            const response = await fetch(`${spaConfig.apiUrl}post/${id}`);
            const data = await response.json();
            
            this.renderPost(data);
            
        } catch (error) {
            console.error('Post load error:', error);
            this.loadError();
        } finally {
            document.dispatchEvent(new CustomEvent('spa:loading-end'));
        }
    }

    renderPage(data) {
        document.title = `${data.title} | ${document.title.split(' | ')[1]}`;
        
        const content = document.getElementById('spa-content');
        content.innerHTML = `
            <div class="spa-page">
                ${data.featured_image ? `
                    <div class="featured-image-wrapper" data-scroll-animate>
                        <img src="${data.featured_image}" alt="${data.title}" class="featured-image">
                    </div>
                ` : ''}
                <h1 data-animate="fade-up">${data.title}</h1>
                <div class="page-content" data-animate="fade-in">${data.content}</div>
            </div>
        `;
        
        if (data.acf) {
            ACFRenderer.render(content, data.acf);
        }
        
        document.dispatchEvent(new CustomEvent('spa:content-loaded'));
    }

    renderPost(data) {
        document.title = `${data.title} | ${document.title.split(' | ')[1]}`;
        
        const content = document.getElementById('spa-content');
        content.innerHTML = `
            <div class="spa-post">
                ${data.featured_image ? `
                    <div class="featured-image-wrapper" data-scroll-animate>
                        <img src="${data.featured_image}" alt="${data.title}" class="featured-image">
                    </div>
                ` : ''}
                <h1 data-animate="fade-up">${data.title}</h1>
                <div class="post-meta" data-animate="fade-in">
                    <span class="post-date">${data.date}</span>
                    <span class="post-author">By ${data.author}</span>
                </div>
                <div class="post-content" data-animate="fade-in">${data.content}</div>
            </div>
        `;
        
        if (data.acf) {
            ACFRenderer.render(content, data.acf);
        }
        
        document.dispatchEvent(new CustomEvent('spa:content-loaded'));
    }

    load404() {
        document.getElementById('spa-content').innerHTML = `
            <div class="spa-error">
                <h1>Page Not Found</h1>
                <p>The requested page could not be found.</p>
                <a href="/" data-spa-link>Return Home</a>
            </div>
        `;
    }

    loadError() {
        document.getElementById('spa-content').innerHTML = `
            <div class="spa-error">
                <h1>Loading Error</h1>
                <p>Sorry, we couldn't load the requested content.</p>
                <a href="/" data-spa-link>Return Home</a>
            </div>
        `;
    }
}

window.router = new SPARouter();