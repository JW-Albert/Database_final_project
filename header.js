document.addEventListener('DOMContentLoaded', () => {
  const currentPath = window.location.pathname;

  const navItems = document.querySelectorAll('.nav-item, .dropdown a');

  navItems.forEach(a => {
    if (a.getAttribute('href') === currentPath) {
      a.classList.add('active');

      // 如果點到 dropdown 裡的項目，也要讓主項目（系所成員）亮起來
      if (a.closest('.dropdown')) {
        const memberMainItem = document.querySelector('.nav-item.members');
        if (memberMainItem) {
          memberMainItem.classList.add('active');
        }
      }
    }

    // 額外處理：如果路徑是 /members 或其子頁面，也讓主選單亮起
    if (currentPath.startsWith('/members')) {
      const memberMainItem = document.querySelector('.nav-item.members');
      if (memberMainItem) {
        memberMainItem.classList.add('active');
      }
    }
  });
});
