
"use strict";
(() => {
  var navbar = document.getElementById("sidebar");
  var navbar1 = document.getElementById("header");
  
  // Skip sticky behavior if header/menu position is set to fixed
  var html = document.documentElement;
  var headerPosition = html.getAttribute('data-header-position');
  var menuPosition = html.getAttribute('data-menu-position');
  
  // If both are fixed, don't apply sticky behavior at all
  if (headerPosition === 'fixed' && menuPosition === 'fixed') {
    return;
  }
  
  function stickyFn() {
    if (!navbar || !navbar1) return;
    
    // Re-check attributes in case they changed dynamically
    var currentHeaderPos = html.getAttribute('data-header-position');
    var currentMenuPos = html.getAttribute('data-menu-position');
    
    // Skip if fixed positioning is set
    if (currentHeaderPos === 'fixed' && currentMenuPos === 'fixed') {
      navbar.classList.remove("sticky-pin");
      navbar1.classList.remove("sticky-pin");
      return;
    }
    
    if (window.scrollY >= 75) {
      if (currentMenuPos !== 'fixed') {
        navbar.classList.add("sticky-pin");
      }
      if (currentHeaderPos !== 'fixed') {
        navbar1.classList.add("sticky-pin");
      }
    } else {
      navbar.classList.remove("sticky-pin");
      navbar1.classList.remove("sticky-pin");
    }
  }
  
  window.addEventListener('scroll', stickyFn);
  window.addEventListener('DOMContentLoaded', stickyFn);
})();

