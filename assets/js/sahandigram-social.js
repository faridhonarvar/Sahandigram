jQuery(document).ready(function($) {
    console.log('جاوااسکریپت Sahandigram با jQuery بارگذاری شد!');
    var messageBox = $('#sahandigram-message');
    if (!messageBox.length) {
        console.error('messageBox پیدا نشد!');
        return;
    }

    console.log('تعداد دکمه‌های شئر: ', $('.sahandigram-share-btn').length);

    $(document).on('click', '.sahandigram-like', function(e) {
        console.log('دکمه لایک کلیک شد!');
        if (!sahandigramData.isLoggedIn) {
            e.preventDefault();
            messageBox.text('برای لایک کردن، باید وارد حساب کاربری شوید.');
            messageBox.show();
            setTimeout(function() { messageBox.hide(); }, 2000);
        }
    });

    $(document).on('click', '.sahandigram-save', function(e) {
        console.log('دکمه ذخیره کلیک شد!');
        if (!sahandigramData.isLoggedIn) {
            e.preventDefault();
            messageBox.text('برای ذخیره کردن، باید وارد حساب کاربری شوید.');
            messageBox.show();
            setTimeout(function() { messageBox.hide(); }, 2000);
        }
    });

    $(document).on('click', '.copy-link', function(e) {
        console.log('دکمه کپی لینک کلیک شد!');
        e.preventDefault();
        var url = $(this).data('url');
        navigator.clipboard.writeText(url)
            .then(function() {
                messageBox.text('لینک کپی شد!');
                messageBox.show();
                setTimeout(function() { messageBox.hide(); }, 2000);
            })
            .catch(function(err) {
                console.error('خطا در کپی لینک: ', err);
                messageBox.text('خطا در کپی کردن لینک!');
                messageBox.show();
                setTimeout(function() { messageBox.hide(); }, 2000);
            });
    });

    $(document).on('click', '.sahandigram-share-btn', function(e) {
        e.preventDefault();
        console.log('دکمه شئر کلیک شد!');
        var $menu = $(this).siblings('.sahandigram-share-menu');
        if ($menu.length) {
            $menu.toggleClass('active');
            console.log('وضعیت منوی شئر: ', $menu.hasClass('active') ? 'باز' : 'بسته');
        } else {
            console.error('منوی شئر پیدا نشد!');
        }
    });
});