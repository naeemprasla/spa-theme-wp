/**
 * Handles loading content from WordPress REST API
 */
class SPAContentLoader {
    static async loadPage(slug) {
        try {
            const response = await fetch(`${spaSettings.restUrl}spa/v1/page/${slug}`);
            
            if (!response.ok) {
                throw new Error('Page not found');
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error loading page:', error);
            return null;
        }
    }

    static async loadMenu(location = 'primary') {
        try {
            const response = await fetch(`${spaSettings.restUrl}spa/v1/menu/${location}`);
            
            if (!response.ok) {
                throw new Error('Menu not found');
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error loading menu:', error);
            return [];
        }
    }
}

window.SPAContentLoader = SPAContentLoader;