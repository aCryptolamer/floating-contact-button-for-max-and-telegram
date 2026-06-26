jQuery(function ($) {

    const $list = $('#max-buttons-sortable');
    const $orderInput = $('#max_buttons_order');

    if (!$list.length || !$orderInput.length) return;

    function updateOrder() {
        const order = [];
        $list.children('.max-button-card').each(function () {
            order.push($(this).data('button'));
        });
        $orderInput.val(order.join(','));
    }

    $list.sortable({
        items: '.max-button-card',
        handle: 'summary',
        cancel: 'input, a, label',
        placeholder: 'max-sort-placeholder',
        tolerance: 'pointer',
        update: updateOrder
    });

    // фиксируем порядок после первичной отрисовки
    setTimeout(updateOrder, 0);
});