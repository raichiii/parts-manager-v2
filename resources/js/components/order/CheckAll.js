$('.no-filter-control').eq(0).append('<div class="text-center"><input type="checkbox" class="check-all"></div>')
$(function () {
    //全選択チェックボックス
    $('.check-all').change(function () {
        $('.check-part').prop('checked', this.checked)
    })
})
