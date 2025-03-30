class SPATransitions {
    constructor() {
      this.initBarba();
    }
  
    initBarba() {
      // Check for required elements
      if (!document.getElementById('spa-wrapper') || !document.querySelector('.barba-container')) {
        console.error('Barba.js: Required elements not found');
        return;
      }
  
      // Barba.js configuration
      Barba.Pjax.Dom.wrapperId = 'spa-wrapper';
      Barba.Pjax.Dom.containerClass = 'barba-container';
  
      // Transition with loader control
      const PageTransition = Barba.BaseTransition.extend({
        start: function() {
          showLoader();
          document.getElementById('spa-content').classList.add('loading');
          Promise.all([this.newContainerLoading, this.fadeOut()])
            .then(this.fadeIn.bind(this))
            .catch(() => {
              hideLoader();
              document.getElementById('spa-content').classList.remove('loading');
            });
        },
  
        fadeOut: function() {
          return new Promise(resolve => {
            gsap.to(this.oldContainer, {
              opacity: 0,
              duration: 0.3,
              onComplete: resolve
            });
          });
        },
  
        fadeIn: function() {
          window.scrollTo(0, 0);
          this.newContainer.style.opacity = 0;
          
          gsap.to(this.newContainer, {
            opacity: 1,
            duration: 0.4,
            onComplete: () => {
              this.done();
              document.dispatchEvent(new CustomEvent('spa:transition-complete'));
              hideLoader();
              document.getElementById('spa-content').classList.remove('loading');
            }
          });
        }
      });
  
      Barba.Pjax.getTransition = () => PageTransition;
      
      // Start Barba.js
      Barba.Pjax.start();
      
      // Handle initial state
      Barba.Dispatcher.on('initStateChange', () => {
        document.dispatchEvent(new CustomEvent('spa:transition-start'));
      });
    }
  }