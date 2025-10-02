    </div> <!-- Fecha content-spacer -->

    <script>
        // Verificar se o menu precisa de scroll e adicionar indicador visual
function checkMenuScroll() {
    const menu = document.querySelector('.menu');
    const menuContainer = document.querySelector('.menu-container');
    
    if (menu.scrollWidth > menu.clientWidth) {
        menuContainer.classList.add('scrollable');
    } else {
        menuContainer.classList.remove('scrollable');
    }
}

// Verificar ao carregar e redimensionar
document.addEventListener('DOMContentLoaded', checkMenuScroll);
window.addEventListener('resize', checkMenuScroll);

// Suavizar o scroll horizontal com mouse wheel
document.querySelector('.menu').addEventListener('wheel', function(e) {
    if (e.deltaY !== 0) {
        e.preventDefault();
        this.scrollLeft += e.deltaY;
    }
});
        // Menu mobile toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('mobileMenu').classList.toggle('active');
        });

        // Mobile category accordion
        const mobileCategories = document.querySelectorAll('.mobile-category-title');
        mobileCategories.forEach(category => {
            category.addEventListener('click', () => {
                category.classList.toggle('active');
                const submenu = category.nextElementSibling;

                if (submenu.style.display === 'block') {
                    submenu.style.display = 'none';
                } else {
                    // Close any other open submenus
                    document.querySelectorAll('.mobile-submenu').forEach(item => {
                        if (item !== submenu) item.style.display = 'none';
                    });
                    document.querySelectorAll('.mobile-category-title').forEach(item => {
                        if (item !== category) item.classList.remove('active');
                    });

                    submenu.style.display = 'block';
                }
            });
        });

        // Search functionality for mobile menu
        document.getElementById('menuSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const menuItems = document.querySelectorAll('.mobile-submenu a');

            document.querySelectorAll('.mobile-category').forEach(category => {
                let hasVisibleItems = false;
                const categoryItems = category.querySelectorAll('.mobile-submenu a');

                categoryItems.forEach(item => {
                    if (item.textContent.toLowerCase().includes(searchTerm)) {
                        item.style.display = 'block';
                        hasVisibleItems = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide category based on whether it has visible items
                category.style.display = hasVisibleItems ? 'block' : 'none';
            });

            // Open all categories when searching
            if (searchTerm.length > 0) {
                document.querySelectorAll('.mobile-submenu').forEach(submenu => {
                    submenu.style.display = 'block';
                });
                document.querySelectorAll('.mobile-category-title').forEach(title => {
                    title.classList.add('active');
                });
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menuToggle = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');

            if (!menuToggle.contains(event.target) && !mobileMenu.contains(event.target) && mobileMenu.classList.contains('active')) {
                menuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });

        // Prevent submenu from closing when clicking inside it (desktop)
        document.querySelectorAll('.submenu').forEach(submenu => {
            submenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
</body>
</html>