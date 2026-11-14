/**
 * メニューバーの出し入れ
 */
$(function () {
    const accordionByBtn2 = (() => {
        const topAccordion2 = $("#accordion2").offset().top
        const bottomBtn = $("#sidebar-btn").outerHeight()
        if (topAccordion2 < bottomBtn) {
            return "#accordion"
        }
        return "#accordion2"
    })();
    $("#accordion, #sidebar-btn").hover(
        function() {
            $("#accordion, #sidebar-btn2").addClass("active")
        },
        function() {
            $("#accordion, #sidebar-btn2").removeClass("active")
        }
    )
    $(`${accordionByBtn2}, #sidebar-btn2`).hover(
        function() {
            $(`${accordionByBtn2}, #sidebar-btn2`).addClass("active")
        },
        function() {
            $(`${accordionByBtn2}, #sidebar-btn2`).removeClass("active")
        }
    )
})

$("#accordion-sp").addClass("d-none")
$(function () {
    $('#sidebar-btn').on('click', function () {
        $("#accordion-sp").toggleClass("d-none")
    })
})

/**
 * 右上ドロップダウンメニューの矢印の向きを変える
 */
const dropdownMenu = document.getElementsByClassName("dropdown-menu")[0]
const observer = new MutationObserver(() => {
    $(".dropdown-menu").hasClass("show") ?
    $("#navbarDropdown").addClass("active") : $("#navbarDropdown").removeClass("active")
})
observer.observe(dropdownMenu, {attributes: true})
