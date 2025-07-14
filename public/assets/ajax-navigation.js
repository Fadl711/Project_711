// كود جافاسكريبت بسيط لتنقل AJAX مع دعم الرجوع للخلف

document.addEventListener('DOMContentLoaded', function () {
    // استهداف جميع الروابط التي تحمل كلاس ajax-link
    document.body.addEventListener('click', function (e) {
        const link = e.target.closest('a.ajax-link');
        if (link) {
            const url = link.getAttribute('href');
            if (url && url !== '#') {
                e.preventDefault();
                fetchPage(url, true);
            }
        }
    });

    // دعم الرجوع للخلف
    window.addEventListener('popstate', function (event) {
        if (event.state && event.state.url) {
            fetchPage(event.state.url, false);
        }
    });
    updateNavbarActive();
});

function fetchPage(url, push = true) {
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // استخراج القسم الرئيسي فقط
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('#main-content');
        if (newContent) {
            document.querySelector('#main-content').innerHTML = newContent.innerHTML;
            if (push) {
                window.history.pushState({url: url}, '', url);
            }
            updateNavbarActive();
        }
    });
}

// تحديث حالة الروابط في النافبار حسب الصفحة الحالية
function updateNavbarActive() {
    const path = window.location.pathname;
    document.querySelectorAll('.ajax-link').forEach(function(link) {
        link.classList.remove('border-b-2', 'font-bold', 'text-xl', 'pointer-events-none', 'opacity-50');
        // استخدم link.pathname للمقارنة بدلاً من href
        if (link.pathname === path) {
            link.classList.add('border-b-2', 'font-bold', 'text-xl', 'pointer-events-none', 'opacity-50');
        }
    });
}
