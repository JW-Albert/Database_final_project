document.addEventListener('DOMContentLoaded', () => {
  const currentPath = window.location.pathname;

  // 所有選單連結（包含 dropdown 裡的）
  const navItems = document.querySelectorAll('.nav-item, .dropdown a');

  navItems.forEach(a => {
    const href = a.getAttribute('href');

    // 判斷目前的網址是否以這個 href 開頭（包含子路徑）
    if (href && currentPath.startsWith(href)) {
      a.classList.add('active');
    }
  });

  // 額外處理：如果在 /members 或其子頁面，標記「系所成員」為 active
  if (currentPath.startsWith('/members')) {
    const memberMainItem = document.querySelector('.nav-item.members');
    if (memberMainItem) {
      memberMainItem.classList.add('active');
    }
  }
});
