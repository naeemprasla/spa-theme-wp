/**
 * Renders ACF repeater sections
 */
class SPASectionRenderer {
    static init() {
        this.sectionTemplates = {
            'hero_section': this.renderHeroSection,
            'content_section': this.renderContentSection,
            'card_grid': this.renderCardGrid
        };
    }

    static renderSections(sections, container) {
        if (!sections || !Array.isArray(sections)) return;
        
        let html = '';
        sections.forEach(section => {
            const renderer = this.sectionTemplates[section.acf_fc_layout];
            if (renderer) {
                html += renderer(section);
            }
        });
        
        container.innerHTML = html;
    }

    static renderHeroSection(section) {
        const bgImage = section.background_image?.url || '';
        return `
            <section class="hero-section" style="background-image: url(${bgImage})">
                <div class="container">
                    <h1>${section.title || ''}</h1>
                    ${section.subtitle ? `<p>${section.subtitle}</p>` : ''}
                    ${section.button_link ? `<a href="${section.button_link.url}" class="button" data-spa-link>${section.button_link.title}</a>` : ''}
                </div>
            </section>
        `;
    }

    static renderContentSection(section) {
        return `
            <section class="content-section">
                <div class="container">
                    ${section.title ? `<h2>${section.title}</h2>` : ''}
                    <div class="content">${section.content || ''}</div>
                </div>
            </section>
        `;
    }

    static renderCardGrid(section) {
        let cardsHtml = '';
        if (section.cards) {
            cardsHtml = section.cards.map(card => `
                <div class="card">
                    ${card.title ? `<h3>${card.title}</h3>` : ''}
                    ${card.content ? `<p>${card.content}</p>` : ''}
                    ${card.link ? `<a href="${card.link.url}" data-spa-link>${card.link.title}</a>` : ''}
                </div>
            `).join('');
        }
        
        return `
            <section class="card-grid-section">
                <div class="container">
                    ${section.title ? `<h2>${section.title}</h2>` : ''}
                    <div class="cards">${cardsHtml}</div>
                </div>
            </section>
        `;
    }
}

// Initialize
SPASectionRenderer.init();
window.SPASectionRenderer = SPASectionRenderer;