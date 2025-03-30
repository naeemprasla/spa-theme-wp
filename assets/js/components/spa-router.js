/**
 * Client-side router for SPA functionality
 */
class SPARouter {
    constructor() {
        this.routes = {};
        this.currentUrl = '';
        this.init();
    }

    init() {
        // Handle initial load
        this.navigate(window.location.pathname, false);
        
        // Set up event listeners
        window.addEventListener('popstate', this.handlePopState.bind(this));
        document.addEventListener('click', this.handleLinkClick.bind(this));
    }

    addRoute(path, callback) {
        this.routes[path] = callback;
    }

    handlePopState(e) {
        const path = window.location.pathname;
        this.navigate(path, false);
    }

    handleLinkClick(e) {
        const link = e.target.closest('a[data-spa-link]');
        if (!link || link.target === '_blank' || link.href.indexOf(window.location.origin) === -1) {
            return;
        }
        
        e.preventDefault();
        const path = new URL(link.href).pathname;
        this.navigate(path);
    }

    navigate(path, pushState = true) {
        if (path === this.currentUrl) return;
        
        const matchedRoute = Object.keys(this.routes).find(route => {
            const routeRegex = new RegExp('^' + route.replace(/:\w+/g, '([^/]+)') + '$');
            return routeRegex.test(path);
        });
        
        if (matchedRoute) {
            if (pushState) {
                window.history.pushState({}, '', path);
            }
            
            this.currentUrl = path;
            this.routes[matchedRoute](path);
        }
    }
}

window.SPARouter = SPARouter;