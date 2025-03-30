class ACFRenderer {
    static init() {
      this.fieldRenderers = {
        'Textarea': this.renderTextarea,
        'Rich Text Editor': this.renderRichText,
        'CodeBlock': this.renderCodeBlock,
        'Shortcode': this.renderShortcode
      };
    }
  
    static render(container, acfData) {
      if (!acfData?.page_sections) return;
  
      let html = '';
      acfData.page_sections.forEach(section => {
        html += this.renderSection(section);
      });
  
      if (html && container) {
        const acfContainer = document.createElement('div');
        acfContainer.className = 'acf-container';
        acfContainer.innerHTML = html;
        container.appendChild(acfContainer);
        
        document.dispatchEvent(new CustomEvent('acf:rendered', {
          detail: { container: acfContainer }
        }));
      }
    }
  
    static renderSection(section) {
      let rowsHtml = '';
      section.section_rows?.forEach(row => {
        let colsHtml = '';
        row.row_columns?.forEach(col => {
          colsHtml += this.renderColumn(col);
        });
        rowsHtml += `<div class="acf-row">${colsHtml}</div>`;
      });
      
      return `<section class="acf-section">${rowsHtml}</section>`;
    }
  
    static renderColumn(col) {
      const type = col.select_field_type?.value || col.select_field_type;
      const renderer = this.fieldRenderers[type] || this.renderDefault;
      return `<div class="acf-column">${renderer(col)}</div>`;
    }
  
    static renderTextarea(col) {
      return col.textarea ? `<div class="acf-textarea">${col.textarea}</div>` : '';
    }
  
    static renderRichText(col) {
      return col.rich_text_editor || '';
    }
  
    static renderCodeBlock(col) {
      return col.custom_code_block ? 
        `<pre><code>${this.escapeHtml(col.custom_code_block)}</code></pre>` : '';
    }
  
    static renderShortcode(col) {
      return col.shortcode_area || '';
    }
  
    static renderDefault() {
      return '<div class="acf-default">Content not available</div>';
    }
  
    static escapeHtml(unsafe) {
      return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
    }
  }
  
  // Initialize automatically
  ACFRenderer.init();
  window.ACFRenderer = ACFRenderer;