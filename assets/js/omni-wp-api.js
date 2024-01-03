(function($) {
    // Получите индекс активного таба из localStorage, если он был сохранен
    var activeTabIndex = localStorage.getItem('activeTabIndex');

    // По умолчанию активируйте первый таб, если индекс не был сохранен
    if (activeTabIndex === null) {
        activeTabIndex = 0;
    }

    // Установите активный таб
    $('.tab-opener').eq(activeTabIndex).addClass('active');
    $('.tab-item').eq(activeTabIndex).addClass('active');

    // Обработчик клика
    $('.tab-opener').click(function(event) {
        event.preventDefault();
        if (!$(this).hasClass('active')) {
            var aim = $(this).parents('.tab-control').find('.tab-opener').removeClass('active').index(this);
            $(this).addClass('active');
            $(this).parents('.tabset').find('.tab-item').removeClass('active').eq(aim).addClass('active');

            // Сохраните индекс активного таба в localStorage
            localStorage.setItem('activeTabIndex', aim);
        }
    });
})(jQuery);