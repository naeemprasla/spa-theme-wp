/**
 * Main application script
 */
document.addEventListener('DOMContentLoaded', async function() {
    // Initialize router
    const router = new SPARouter();
    
    // Add default route
    router.addRoute('*', async (path) => {
        const slug = path === '/' ? 'home' : path.replace(/^\//, '').replace(/\/$/, '');
        const contentContainer = document.getElementById('spa-content');
        
        if (!contentContainer) return;
        
        // Show loading state
        contentContainer.classList.add('loading');
        
        try {
            // Load page data
            const pageData = await SPAContentLoader.loadPage(slug);
            
            if (!pageData) {
                throw new Error('Page not found');
            }
            
            // Update page title
            document.title = pageData.title.rendered;
            
            // Render ACF repeater sections
            if (pageData.acf && pageData.acf.sections) {
                SPASectionRenderer.renderSections(pageData.acf.sections, contentContainer);
            } else {
                // Fallback to content if no sections
                contentContainer.innerHTML = pageData.content.rendered;
            }
            
            // Trigger content loaded event
            const event = new CustomEvent('spa:contentLoaded', {
                detail: { 
                    url: path,
                    pageData: pageData
                }
            });
            document.dispatchEvent(event);
            
        } catch (error) {
            console.error('Error:', error);
            contentContainer.innerHTML = `
                <div class="error-message">
                    <h2>Page Not Found</h2>
                    <p>The requested page could not be loaded.</p>
                    <a href="${spaSettings.homeUrl}" data-spa-link>Return Home</a>
                </div>
            `;
        } finally {
            contentContainer.classList.remove('loading');
        }
    });
    
    // Load initial page
    router.navigate(window.location.pathname, false);
    
    // Enable JS features
    document.querySelector('.no-js-warning').style.display = 'none';
    document.querySelectorAll('.spa-enhanced').forEach(el => {
        el.style.display = '';
    });
});