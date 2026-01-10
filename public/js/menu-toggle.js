// Menu toggle functionality
(function() {
    'use strict';
    
    function initMenu() {
        var menuToggles = document.querySelectorAll('.menu-link.menu-toggle');
        
        menuToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var menuItem = this.closest('.menu-item');
                if (!menuItem) return;
                
                // Toggle open class
                menuItem.classList.toggle('open');
                
                // Close sibling open menus
                var siblings = menuItem.parentElement.querySelectorAll('.menu-item.open');
                siblings.forEach(function(sibling) {
                    if (sibling !== menuItem) {
                        sibling.classList.remove('open');
                    }
                });
            });
        });
        
        // Handle submenu links
        var submenuLinks = document.querySelectorAll('.menu-sub .menu-link');
        submenuLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                var menuItem = this.closest('.menu-item');
                if (menuItem) {
                    var activeLinks = document.querySelectorAll('.menu-link.active');
                    activeLinks.forEach(function(activeLink) {
                        activeLink.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });
        
        // Set parent items to open if they have active children
        var activeLinks = document.querySelectorAll('.menu-sub .menu-link.active');
        activeLinks.forEach(function(link) {
            var parent = link.closest('.menu-item');
            while (parent) {
                parent.classList.add('open');
                parent = parent.parentElement.closest('.menu-item');
            }
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenu);
    } else {
        initMenu();
    }
    
    // Re-initialize on dynamic content
    window.addEventListener('load', initMenu);
})();
