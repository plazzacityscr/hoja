/**
 * Gentelella JavaScript for Hoja Project
 * Based on Gentelella Bootstrap 5 Admin Dashboard
 */

// DOM Ready Helper
const DOM = {
  ready: (callback) => {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', callback);
    } else {
      callback();
    }
  },
  select: (selector) => document.querySelector(selector),
  selectAll: (selector) => [...document.querySelectorAll(selector)],
  addClass: (element, className) => element?.classList.add(className),
  removeClass: (element, className) => element?.classList.remove(className),
  toggleClass: (element, className) => element?.classList.toggle(className),
  hasClass: (element, className) => element?.classList.contains(className),
  closest: (element, selector) => element?.closest(selector),
  find: (element, selector) => element?.querySelector(selector),
  findAll: (element, selector) => [...(element?.querySelectorAll(selector) || [])],
};

// Sidebar Toggle
function initSidebarToggle() {
  const toggleBtn = DOM.select('.nav.toggle');
  const body = DOM.select('body');

  if (toggleBtn && body) {
    toggleBtn.addEventListener('click', () => {
      if (DOM.hasClass(body, 'nav-md')) {
        DOM.removeClass(body, 'nav-md');
        DOM.addClass(body, 'nav-sm');
      } else {
        DOM.removeClass(body, 'nav-sm');
        DOM.addClass(body, 'nav-md');
      }
    });
  }
}

// Sidebar Menu Accordion
function initSidebarMenu() {
  const menuItems = DOM.selectAll('.nav.side-menu > li > a');

  menuItems.forEach(item => {
    item.addEventListener('click', (e) => {
      const parentLi = DOM.closest(item, 'li');
      const childMenu = DOM.find(parentLi, '.nav.child_menu');

      if (childMenu) {
        e.preventDefault();

        // Toggle current menu
        if (DOM.hasClass(childMenu, 'active')) {
          DOM.removeClass(childMenu, 'active');
          DOM.removeClass(childMenu, 'in');
          childMenu.style.display = 'none';
        } else {
          // Close other menus
          DOM.selectAll('.nav.child_menu').forEach(menu => {
            DOM.removeClass(menu, 'active');
            DOM.removeClass(menu, 'in');
            menu.style.display = 'none';
          });

          DOM.addClass(childMenu, 'active');
          DOM.addClass(childMenu, 'in');
          childMenu.style.display = 'block';
        }
      }
    });
  });
}

// Initialize Active Menu Item
function initActiveMenuItem() {
  const currentPath = window.location.pathname;
  const menuItems = DOM.selectAll('.nav.side-menu a');

  menuItems.forEach(item => {
    const href = item.getAttribute('href');
    if (href && (currentPath === href || currentPath.startsWith(href))) {
      const parentLi = DOM.closest(item, 'li');
      const grandParentLi = DOM.closest(parentLi, '.nav.side-menu > li');

      if (grandParentLi) {
        DOM.addClass(grandParentLi, 'active');
        const childMenu = DOM.find(grandParentLi, '.nav.child_menu');
        if (childMenu) {
          DOM.addClass(childMenu, 'active');
          DOM.addClass(childMenu, 'in');
          childMenu.style.display = 'block';
        }
      }

      DOM.addClass(parentLi, 'active');
    }
  });
}

// Panel Collapse
function initPanelCollapse() {
  const collapseLinks = DOM.selectAll('.collapse-link');

  collapseLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();

      const xPanel = DOM.closest(link, '.x_panel');
      const xContent = DOM.find(xPanel, '.x_content');

      if (xContent) {
        if (DOM.hasClass(xContent, 'collapsed')) {
          DOM.removeClass(xContent, 'collapsed');
          xContent.style.display = 'block';
          DOM.removeClass(link, 'collapse');
          DOM.addClass(link, 'expand');
        } else {
          DOM.addClass(xContent, 'collapsed');
          xContent.style.display = 'none';
          DOM.removeClass(link, 'expand');
          DOM.addClass(link, 'collapse');
        }
      }
    });
  });
}

// Progress Bars Animation
function initProgressBars() {
  const progressBars = DOM.selectAll('.progress .progress-bar');

  progressBars.forEach(bar => {
    const transitionGoal = bar.getAttribute('data-transitiongoal');

    if (transitionGoal) {
      const goal = parseInt(transitionGoal);
      if (goal > 0) {
        bar.style.width = '0%';
        bar.style.transition = 'width 1s ease-in-out';

        setTimeout(() => {
          bar.style.width = goal + '%';
        }, 100);
      }
    }
  });
}

// Initialize all components
DOM.ready(() => {
  initSidebarToggle();
  initSidebarMenu();
  initActiveMenuItem();
  initPanelCollapse();
  initProgressBars();
});

// Export for external use
window.Gentelella = {
  DOM,
  initSidebarToggle,
  initSidebarMenu,
  initActiveMenuItem,
  initPanelCollapse,
  initProgressBars,
};
