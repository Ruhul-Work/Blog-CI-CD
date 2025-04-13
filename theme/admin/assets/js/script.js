$(document).ready(function () {
    var $wrapper = $('.main-wrapper'); var $slimScrolls = $('.slimscroll'); var $pageWrapper = $('.page-wrapper'); feather.replace(); $(window).resize(function () { if ($('.page-wrapper').length > 0) { var height = $(window).height(); $(".page-wrapper").css("min-height", height); } }); if (localStorage.theme) document.documentElement.setAttribute("data-layout-mode", localStorage.theme); $('body').append('<div class="sidebar-overlay"></div>'); $(document).on('click', '#mobile_btn', function () { $wrapper.toggleClass('slide-nav'); $('.sidebar-overlay').toggleClass('opened'); $('html').addClass('menu-opened'); $('#task_window').removeClass('opened'); return false; }); $(".sidebar-overlay").on("click", function () { $('html').removeClass('menu-opened'); $(this).removeClass('opened'); $wrapper.removeClass('slide-nav'); $('.sidebar-overlay').removeClass('opened'); $('#task_window').removeClass('opened'); }); $(document).on("click", ".hideset", function () { $(this).parent().parent().parent().hide(); }); $(document).on("click", ".delete-set", function () { $(this).parent().parent().hide(); }); if ($(window).width() > 767) { if ($('.theiaStickySidebar').length > 0) { $('.theiaStickySidebar').theiaStickySidebar({ additionalMarginTop: 30 }); } }
    if ($('.product-slide').length > 0) { $('.product-slide').owlCarousel({ items: 1, margin: 30, dots: false, nav: true, loop: false, responsiveClass: true, responsive: { 0: { items: 1 }, 800: { items: 1 }, 1170: { items: 1 } } }); }
    $('.notes-tog').on('click', function () { $('.section-bulk-widget').toggleClass('section-notes-dashboard'); }); $('.notes-tog').on('click', function () { $('.budget-role-notes').toggleClass('budgeted-role-notes'); }); $('.notes-tog').on('click', function () { $('.notes-page-wrapper').toggleClass('notes-tag-left'); }); if ($('.notes-slider').length > 0) { $('.notes-slider').owlCarousel({ loop: true, margin: 24, dots: false, nav: true, smartSpeed: 2000, navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'], responsive: { 0: { items: 1 }, 768: { items: 2 }, 1300: { items: 3 } } }) }
    if ($('.owl-product').length > 0) { var owl = $('.owl-product'); owl.owlCarousel({ margin: 10, dots: false, nav: true, loop: false, touchDrag: false, mouseDrag: false, responsive: { 0: { items: 2 }, 768: { items: 4 }, 1170: { items: 8 } } }); }
    if ($('.datanew').length > 0) { $('.datanew').DataTable({ "bFilter": true, "sDom": 'fBtlpi', "ordering": true, "language": { search: ' ', sLengthMenu: '_MENU_', searchPlaceholder: "Search", info: "_START_ - _END_ of _TOTAL_ items", paginate: { next: ' <i class=" fa fa-angle-right"></i>', previous: '<i class="fa fa-angle-left"></i> ' }, }, initComplete: (settings, json) => { $('.dataTables_filter').appendTo('#tableSearch'); $('.dataTables_filter').appendTo('.search-input'); }, }); }
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader(); reader.onload = function (e) { $('#blah').attr('src', e.target.result); }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgInp").change(function () { readURL(this); }); if ($('.datatable').length > 0) { $('.datatable').DataTable({ "bFilter": false }); }
    setTimeout(function () { $('#global-loader'); setTimeout(function () { $("#global-loader").fadeOut("slow"); }, 100); }, 500); if ($('.datetimepicker').length > 0) { $('.datetimepicker').datetimepicker({ format: 'DD-MM-YYYY', icons: { up: "fas fa-angle-up", down: "fas fa-angle-down", next: 'fas fa-angle-right', previous: 'fas fa-angle-left' } }); }
    if ($('.toggle-password').length > 0) { $(document).on('click', '.toggle-password', function () { $(this).toggleClass("fa-eye fa-eye-slash"); var input = $(".pass-input"); if (input.attr("type") == "password") { input.attr("type", "text"); } else { input.attr("type", "password"); } }); }
    if ($('.toggle-passwords').length > 0) { $(document).on('click', '.toggle-passwords', function () { $(this).toggleClass("fa-eye fa-eye-slash"); var input = $(".pass-inputs"); if (input.attr("type") == "password") { input.attr("type", "text"); } else { input.attr("type", "password"); } }); }
    if ($('.toggle-passworda').length > 0) {
        $(document).on('click', '.toggle-passworda', function () {
            $(this).toggleClass("fa-eye fa-eye-slash"); var input = $(".pass-inputa"); if (input.attr("type") == "password") { input.attr("type", "text"); } else {
                setTimeout
                input.attr("type", "password");
            }
        });
    }
    if ($('.comming-soon-pg').length > 0) {
        let day = document.querySelector('.days'); let hour = document.querySelector('.hours'); let minute = document.querySelector('.minutes'); let second = document.querySelector('.seconds'); function setCountdown() { let countdownDate = new Date('mar 27, 2024 16:00:00').getTime(); let updateCount = setInterval(function () { let todayDate = new Date().getTime(); let distance = countdownDate - todayDate; let days = Math.floor(distance / (1000 * 60 * 60 * 24)); let hours = Math.floor(distance % (1000 * 60 * 60 * 24) / (1000 * 60 * 60)); let minutes = Math.floor(distance % (1000 * 60 * 60) / (1000 * 60)); let seconds = Math.floor(distance % (1000 * 60) / 1000); day.textContent = days; hour.textContent = hours; minute.textContent = minutes; second.textContent = seconds; if (distance < 0) { clearInterval(updateCount); document.querySelector(".comming-soon-pg").innerHTML = '<h1>EXPIRED</h1>' } }, 1000) }
        setCountdown()
    }
    if ($('.select').length > 0) { $('.select').select2({ minimumResultsForSearch: -1, width: '100%' }); }
    if ($('.selectSimple').length > 0) { $('.selectSimple').select2({ minimumResultsForSearch: 3, width: '100%',allowClear: true }); }

    if ($('.counter').length > 0) { $('.counter').counterUp({ delay: 20, time: 2000 }); }
    if ($('#timer-countdown').length > 0) { $('#timer-countdown').countdown({ from: 180, to: 0, movingUnit: 1000, timerEnd: undefined, outputPattern: '$day Day $hour : $minute : $second', autostart: true }); }
    if ($('#timer-countup').length > 0) { $('#timer-countup').countdown({ from: 0, to: 180 }); }
    if ($('#timer-countinbetween').length > 0) { $('#timer-countinbetween').countdown({ from: 30, to: 20 }); }
    if ($('#timer-countercallback').length > 0) { $('#timer-countercallback').countdown({ from: 10, to: 0, timerEnd: function () { this.css({ 'text-decoration': 'line-through' }).animate({ 'opacity': .5 }, 500); } }); }
    if ($('#timer-outputpattern').length > 0) { $('#timer-outputpattern').countdown({ outputPattern: '$day Days $hour Hour $minute Min $second Sec..', from: 60 * 60 * 24 * 3 }); }
    if ($('#summernote').length > 0) { $('#summernote').summernote({ height: 300, minHeight: null, maxHeight: null, focus: false }); }
    if ($('#summernote2').length > 0) { $('#summernote2').summernote({ height: 300, minHeight: null, maxHeight: null, focus: true }); }
    if ($('#summernote3').length > 0) { $('#summernote3').summernote({ placeholder: 'Type your message', height: 300, minHeight: null, maxHeight: null, focus: true }); }
    if ($('#summernote4').length > 0) { $('#summernote4').summernote({ height: 300, minHeight: null, maxHeight: null, focus: true }); }
    if ($('#summernote5').length > 0) { $('#summernote5').summernote({ height: 300, minHeight: null, maxHeight: null, focus: true }); }
    if ($slimScrolls.length > 0) { $slimScrolls.slimScroll({ height: 'auto', width: '100%', position: 'right', size: '7px', color: '#ccc', wheelStep: 10, touchScrollStep: 100 }); var wHeight = $(window).height() - 60; $slimScrolls.height(wHeight); $('.sidebar .slimScrollDiv').height(wHeight); $(window).resize(function () { var rHeight = $(window).height() - 60; $slimScrolls.height(rHeight); $('.sidebar .slimScrollDiv').height(rHeight); }); }
    var Sidemenu = function () { this.$menuItem = $('.sidebar-menu a'); }; function init() {
        var $this = Sidemenu; $('.sidebar-menu a').on('click', function (e) {
            if ($(this).parent().hasClass('submenu')) { e.preventDefault(); }
            if (!$(this).hasClass('subdrop')) { $('ul', $(this).parents('ul:first')).slideUp(250); $('a', $(this).parents('ul:first')).removeClass('subdrop'); $(this).next('ul').slideDown(350); $(this).addClass('subdrop'); } else if ($(this).hasClass('subdrop')) { $(this).removeClass('subdrop'); $(this).next('ul').slideUp(350); }
        }); $('.sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
    }
    init(); $(document).on('mouseover', function (e) {
        e.stopPropagation(); if ($('body').hasClass('mini-sidebar') && $('#toggle_btn').is(':visible')) {
            var targ = $(e.target).closest('.sidebar, .header-left').length; if (targ) { $('body').addClass('expand-menu'); $('.subdrop + ul').slideDown(); } else { $('body').removeClass('expand-menu'); $('.subdrop + ul').slideUp(); }
            return false;
        }
    }); setTimeout(function () { $(document).ready(function () { $('.table').parent().addClass('table-responsive'); }); }, 1000); if ($('.bookingrange').length > 0) {
        var start = moment().subtract(6, 'days'); var end = moment(); function booking_range(start, end) { $('.bookingrange span').html(start.format('M/D/YYYY') + ' - ' + end.format('M/D/YYYY')); }
        $('.bookingrange').daterangepicker({ startDate: start, endDate: end, ranges: { 'Today': [moment(), moment()], 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')], 'Last 7 Days': [moment().subtract(6, 'days'), moment()], 'Last 30 Days': [moment().subtract(29, 'days'), moment()], 'This Month': [moment().startOf('month'), moment().endOf('month')], 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')] } }, booking_range); booking_range(start, end);
    }
    $(document).on('click', '#toggle_btn', function () {
        if ($('body').hasClass('mini-sidebar')) { $('body').removeClass('mini-sidebar'); $(this).addClass('active'); $('.subdrop + ul'); localStorage.setItem('screenModeNightTokenState', 'night'); setTimeout(function () { $("body").removeClass("mini-sidebar"); $(".header-left").addClass("active"); }, 100); } else { $('body').addClass('mini-sidebar'); $(this).removeClass('active'); $('.subdrop + ul'); localStorage.removeItem('screenModeNightTokenState', 'night'); setTimeout(function () { $("body").addClass("mini-sidebar"); $(".header-left").removeClass("active"); }, 100); }
        return false;
    }); if (localStorage.getItem('screenModeNightTokenState') == 'night') { setTimeout(function () { $("body").removeClass("mini-sidebar"); $(".header-left").addClass("active"); }, 100); }
    var myDiv = document.querySelector('.sticky-sidebar-one'); $('.submenus').on('click', function () { $('body').addClass('sidebarrightmenu'); }); $('#searchdiv').on('click', function () { $('.searchinputs').addClass('show'); }); $('.search-addon span').on('click', function () { $('.searchinputs').removeClass('show'); }); $(document).on('click', '#filter_search', function () { $('#filter_inputs').slideToggle("slow"); }); $(document).on('click', '#filter_search1', function () { $('#filter_inputs1').slideToggle("slow"); }); $(document).on('click', '#filter_search2', function () { $('#filter_inputs2').slideToggle("slow"); }); $(document).on('click', '#filter_search3', function () { $('#filter_inputs3').slideToggle("slow"); }); $(document).on('click', '#filter_search', function () { $('#filter_search').toggleClass("setclose"); }); $(document).on('click', '#filter_search1', function () { $('#filter_search1').toggleClass("setclose"); }); $(document).on("click", ".productset", function () { $(this).toggleClass("active"); }); $(document).on("click", ".product-info", function () { $(this).toggleClass("active"); }); $(document).on("click", ".layout-box", function () { $('.layout-hide-box').toggleClass("layout-show-box"); }); $(document).on("click", ".select-option1", function () { $('.select-color-add').addClass("selected-color-add"); }); $('.bank-box').on('click', function () { $('.bank-box').removeClass('active'); $(this).addClass('active'); }); $('.theme-image').on('click', function () { $('.theme-image').removeClass('active'); $(this).addClass('active'); }); $('.themecolorset').on('click', function () { $('.themecolorset').removeClass('active'); $(this).addClass('active'); }); $('.inc.button').click(function () { var $this = $(this), $input = $this.prev('input'), $parent = $input.closest('div'), newValue = parseInt($input.val()) + 1; $parent.find('.inc').addClass('a' + newValue); $input.val(newValue); newValue += newValue; }); $('.dec.button').click(function () { var $this = $(this), $input = $this.next('input'), $parent = $input.closest('div'), newValue = parseInt($input.val()) - 1; console.log($parent); $parent.find('.inc').addClass('a' + newValue); $input.val(newValue); newValue += newValue; }); if ($('.custom-file-container').length > 0) {
        var firstUpload = new FileUploadWithPreview('myFirstImage')
        var secondUpload = new FileUploadWithPreview('mySecondImage')
    }
    $('.counters').each(function () { var $this = $(this), countTo = $this.attr('data-count'); $({ countNum: $this.text() }).animate({ countNum: countTo }, { duration: 2000, easing: 'linear', step: function () { $this.text(Math.floor(this.countNum)); }, complete: function () { $this.text(this.countNum); } }); }); if ($('.select-color-add').length > 0) {
        const colorSelect = document.getElementById('colorSelect'); const inputBox = document.getElementById('inputBox'); const inputShow = document.getElementById('input-show'); const variantTable = document.getElementById('variant-table'); colorSelect.addEventListener('change', function () {
            const selectedValue = colorSelect.value; inputShow.style.display = "block"
            variantTable.style.display = "block"
            inputBox.value = selectedValue;
        });
    }
    if ($('.toggle-password').length > 0) { $(document).on('click', '.toggle-password', function () { $(this).toggleClass("fa-eye fa-eye"); var input = $(".pass-input"); if (input.attr("type") == "text") { input.attr("type", "text"); } else { input.attr("type", "password"); } }); }
    if ($('.win-maximize').length > 0) { $('.win-maximize').on('click', function (e) { if (!document.fullscreenElement) { document.documentElement.requestFullscreen(); } else { if (document.exitFullscreen) { document.exitFullscreen(); } } }) }
    $(document).on('click', '#check_all', function () { $('.checkmail').click(); return false; }); if ($('.checkmail').length > 0) { $('.checkmail').each(function () { $(this).on('click', function () { if ($(this).closest('tr').hasClass('checked')) { $(this).closest('tr').removeClass('checked'); } else { $(this).closest('tr').addClass('checked'); } }); }); }
    if ($('.popover-list').length > 0) {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) { return new bootstrap.Popover(popoverTriggerEl) })
    }
    if ($('.clipboard').length > 0) { var clipboard = new Clipboard('.btn'); }
    var chatAppTarget = $('.chat-window'); (function () {
        if ($(window).width() > 991)
            chatAppTarget.removeClass('chat-slide'); $(document).on("click", ".chat-window .chat-users-list a.media", function () {
                if ($(window).width() <= 991) { chatAppTarget.addClass('chat-slide'); }
                return false;
            }); $(document).on("click", "#back_user_list", function () {
                if ($(window).width() <= 991) { chatAppTarget.removeClass('chat-slide'); }
                return false;
            });
    })(); $(document).on('click', '.mail-important', function () { $(this).find('i.fa').toggleClass('fa-star').toggleClass('fa-star-o'); }); var selectAllItems = "#select-all"; var checkboxItem = ":checkbox"; $(selectAllItems).click(function () { if (this.checked) { $(checkboxItem).each(function () { this.checked = true; }); } else { $(checkboxItem).each(function () { this.checked = false; }); } }); var selectAllItems = "#select-all2"; var checkboxItem = ":checkbox"; $(selectAllItems).click(function () { if (this.checked) { $(checkboxItem).each(function () { this.checked = true; }); } else { $(checkboxItem).each(function () { this.checked = false; }); } }); if ($('[data-bs-toggle="tooltip"]').length > 0) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })
    }
    var right_side_views = '<div class="right-side-views d-none">' +
        '<ul class="sticky-sidebar siderbar-view">' +
        '<li class="sidebar-icons">' +
        '<a class="toggle tipinfo open-layout open-siderbar" href="javascript:void(0);" data-toggle="tooltip" data-placement="left" data-bs-original-title="Tooltip on left">' +
        '<div class="tooltip-five ">' +
        '<img src="assets/img/icons/siderbar-icon2.svg" class="feather-five" alt="">' +
        '<span class="tooltiptext">Check Layout</span>' +
        '</div>' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '<div class="sidebar-layout">' +
        '<div class="sidebar-content">' +
        '<div class="sidebar-top">' +
        '<div class="container-fluid">' +
        '<div class="row align-items-center">' +
        '<div class="col-xl-6 col-sm-6 col-12">' +
        '<div class="sidebar-logo">' +
        '<a href="index" class="logo">' +
        '<img src="assets/img/logo.png" alt="Logo" class="img-flex">' +
        '</a>' +
        '</div>' +
        '</div>' +
        '<div class="col-xl-6 col-sm-6 col-12">' +
        '<a class="btn-closed" href="javascript:void(0);"><img class="img-fliud" src="assets/img/icons/sidebar-delete-icon.svg" alt="demo"></a>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="container-fluid">' +
        '<div class="row align-items-center">' +
        '<h5 class="sidebar-title">Choose layout</h5>' +
        '<div class="col-xl-12 col-sm-6 col-12">' +
        '<div class="sidebar-image align-center">' +
        '<img class="img-fliud" src="assets/img/demo-one.png" alt="demo">' +
        '</div>' +
        '<div class="row">' +
        '<div class="col-lg-6 layout">' +
        '<h5 class="layout-title">Dark Mode</h5>' +
        '</div>' +
        '<div class="col-lg-6 layout dark-mode">' +
        '<label class="toggle-switch" for="notification_switch3">' +
        '<span>' +
        '<input type="checkbox" class="toggle-switch-input" id="notification_switch3">' +
        '<span class="toggle-switch-label ms-auto">' +
        '	<span class="toggle-switch-indicator"></span>' +
        '</span>' +
        '</span>' +
        ' </label>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        $("body").append(right_side_views); $('.open-layout').on("click", function (s) { s.preventDefault(); $('.sidebar-layout').addClass('show-layout'); $('.sidebar-settings').removeClass('show-settings'); }); $('.btn-closed').on("click", function (s) { s.preventDefault(); $('.sidebar-layout').removeClass('show-layout'); }); $('.open-settings').on("click", function (s) { s.preventDefault(); $('.sidebar-settings').addClass('show-settings'); $('.sidebar-layout').removeClass('show-layout'); }); $('.btn-closed').on("click", function (s) { s.preventDefault(); $('.sidebar-settings').removeClass('show-settings'); }); $('.open-siderbar').on("click", function (s) { s.preventDefault(); $('.siderbar-view').addClass('show-sidebar'); }); $('.btn-closed').on("click", function (s) { s.preventDefault(); $('.siderbar-view').removeClass('show-sidebar'); }); if ($('.toggle-switch').length > 0) {
            const toggleSwitch = document.querySelector('.toggle-switch input[type="checkbox"]'); const currentTheme = localStorage.getItem('theme'); var app = document.getElementsByTagName("BODY")[0]; if (currentTheme) { app.setAttribute('data-theme', currentTheme); if (currentTheme === 'dark') { toggleSwitch.checked = true; } }
            function switchTheme(e) {
                if (e.target.checked) { app.setAttribute('data-theme', 'dark'); localStorage.setItem('theme', 'dark'); }
                else { app.setAttribute('data-theme', 'light'); localStorage.setItem('theme', 'light'); }
            }
            toggleSwitch.addEventListener('change', switchTheme, false);
        }
    if (window.location.hash == "#LightMode") { localStorage.setItem('theme', 'dark'); }
    else { if (window.location.hash == "#DarkMode") { localStorage.setItem('theme', 'light'); } }
    $('ul.tabs li').click(function () { var $this = $(this); var $theTab = $(this).attr('id'); console.log($theTab); if ($this.hasClass('active')) { } else { $this.closest('.tabs_wrapper').find('ul.tabs li, .tabs_container .tab_content').removeClass('active'); $('.tabs_container .tab_content[data-tab="' + $theTab + '"], ul.tabs li[id="' + $theTab + '"]').addClass('active'); } }); $('body').append('<div class="sidebar-filter"></div>'); setTimeout(function () { if ($('.customizer-links').length > 0) { $(".customizer-links").click(function () { $(".sidebar-filter").addClass("opened"); }); } }, 1000); setTimeout(function () { if ($('.sidebar-close').length > 0) { $(".sidebar-close").click(function () { $(".sidebar-filter").removeClass("opened"); }); } }, 1000); setTimeout(function () { if ($('.sidebar-filter').length > 0) { $(".sidebar-filter").click(function () { $(".sidebar-filter").removeClass("opened"); $(".sidebar-settings").removeClass("show-settings"); }); } }, 1000); '<div class="customizer-links">' +
        '<ul class="sticky-sidebar">' +
        '<li class="sidebar-icons">' +
        '<a href="#" class="add-setting" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Tooltip on left">' +
        '<img src="assets/img/icons/sidebar-icon-01.svg" class="feather-five" alt="">' +
        '</a>' +
        '</li>' +
        '<li class="sidebar-icons">' +
        '<a href="#" class="navigation-add" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Tooltip on left">' +
        '<img src="assets/img/icons/sidebar-icon-02.svg" class="feather-five" alt="">' +
        '</a>' +
        '</li>' +
        '<li class="sidebar-icons">' +
        '<a href="https://themeforest.net/item/dreamspos-pos-inventory-management-admin-dashboard-template/38834413" target="_blank" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Tooltip on left">' +
        '<img src="assets/img/icons/sidebar-icon-03.svg" class="feather-five" alt="">' +
        '</a>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '<div class="sidebar-settings preview-toggle">' +
        '<div class="sidebar-content sticky-sidebar-one">' +
        '<div class="sidebar-header">' +
        '<h5>Preview Settings</h5>' +
        '<a class="sidebar-close" href="#"><img src="assets/img/icons/close-icon.svg" alt=""></a>' +
        '</div>' +
        '<div class="sidebar-body">' +
        '<h6 class="theme-title">Choose Mode</h6>' +
        '<div class="switch-wrapper">' +
        '<div id="dark-mode-toggle">' +
        '<span class="light-mode active"> <img src="assets/img/icons/sun-icon.svg" class="me-2" alt=""> Light</span>' +
        '<span class="dark-mode"><i class="far fa-moon me-2"></i> Dark</span>' +
        '</div>' +
        '</div>' +
        '<div class="row  ">' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="1" class="check">' +
        '<label for="1" class="checktoggle"><a  href="index.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">LTR</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-ltr.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="1" class="check">' +
        '<label for="1" class="checktoggle"><a  href="../template-rtl/index.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">RTL</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-rtl.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row  ">' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="3" class="check">' +
        '<label for="3" class="checktoggle"><a  href="index-three.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">Boxed</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-04.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="3" class="check">' +
        '<label for="3" class="checktoggle"><a  href="index-four.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">Collapsed</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-01.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="sidebar-settings nav-toggle">' +
        '<div class="sidebar-content sticky-sidebar-one">' +
        '<div class="sidebar-header">' +
        '<h5>Navigation Settings</h5>' +
        '<a class="sidebar-close" href="#"><img src="assets/img/icons/close-icon.svg" alt=""></a>' +
        '</div>' +
        '<div class="sidebar-body">' +
        '<h6 class="theme-title">Navigation Type</h6>' +
        '<div class="row  ">' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="1" class="check">' +
        '<label for="1" class="checktoggle"><a  href="index.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">Vertical</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-03.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="2" class="check">' +
        '<label for="2" class="checktoggle"><a  href="index-one.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">Horizontal</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-01.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="3" class="check">' +
        '<label for="3" class="checktoggle"><a  href="index-four.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">Collapsed</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-01.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="3" class="check">' +
        '<label for="3" class="checktoggle"><a  href="index-three.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">Modern</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-04.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-xl-6 ere">' +
        '<div class="layout-wrap">' +
        '<div class="d-flex align-items-center">' +
        '<div class="status-toggle d-flex align-items-center me-2">' +
        '<input type="checkbox" id="3" class="check">' +
        '<label for="3" class="checktoggle"><a  href="index-two.html"class="layout-link">checkbox</a> </label>' +
        '</div>' +
        '<span class="status-text">Boxed</span>' +
        '</div>' +
        '<div class="layout-img">' +
        '<img class="img-fliud" src="assets/img/theme/layout-03.png" alt="layout">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>'; $('.add-setting').on("click", function (e) { e.preventDefault(); $('.preview-toggle.sidebar-settings').addClass('show-settings'); }); $('.navigation-add').on("click", function (e) { e.preventDefault(); $('.nav-toggle.sidebar-settings').addClass('show-settings'); document.body.style.overflow = 'hidden'; }); $('.sidebar-close').on("click", function () { $('.nav-toggle.sidebar-settings').removeClass('show-settings'); }); setTimeout(function () { $(".sidebar-close").click(function (e) { $(".sidebar-settings").removeClass("show-settings"); }); }, 1000); if ($('#dark-mode-toggle').length > 0) {
            $("#dark-mode-toggle").children(".light-mode").addClass("active"); let darkMode = localStorage.getItem('darkMode'); const darkModeToggle = document.querySelector('#dark-mode-toggle'); const enableDarkMode = () => { document.body.setAttribute('data-theme', 'dark'); $("#dark-mode-toggle").children(".dark-mode").addClass("active"); $("#dark-mode-toggle").children(".light-mode").removeClass("active"); localStorage.setItem('darkMode', 'enabled'); }
            const disableDarkMode = () => { document.body.removeAttribute('data-theme', 'dark'); $("#dark-mode-toggle").children(".dark-mode").removeClass("active"); $("#dark-mode-toggle").children(".light-mode").addClass("active"); localStorage.setItem('darkMode', null); }
            if (darkMode === 'enabled') { enableDarkMode(); }
            darkModeToggle.addEventListener('click', () => { darkMode = localStorage.getItem('darkMode'); if (darkMode !== 'enabled') { enableDarkMode(); } else { disableDarkMode(); } });
        }
    $('.digit-group').find('input').each(function () {
        $(this).attr('maxlength', 1); $(this).on('keyup', function (e) {
            var parent = $($(this).parent()); if (e.keyCode === 8 || e.keyCode === 37) { var prev = parent.find('input#' + $(this).data('previous')); if (prev.length) { $(prev).select(); } }
            else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) { var next = parent.find('input#' + $(this).data('next')); if (next.length) { $(next).select(); } else { if (parent.data('autosubmit')) { parent.submit(); } } }
        });
    }); $('.digit-group input').on('keyup', function () { var self = $(this); if (self.val() != '') { self.addClass('active'); } else { self.removeClass('active'); } }); if ($('input[name="datetimes"]').length > 0) { $('input[name="datetimes"]').daterangepicker({ timePicker: true, startDate: moment().startOf('hour'), endDate: moment().startOf('hour').add(32, 'hour'), locale: { format: 'M/DD hh:mm A' } }); }
    if ($('.top-online-contacts .swiper-container').length > 0) { var swiper = new Swiper('.top-online-contacts .swiper-container', { slidesPerView: 5, spaceBetween: 15, }); }
    $(".dream_profile_menu").on('click', function () {
        $('.right-side-contact').addClass('show-right-sidebar'); $('.right-side-contact').removeClass('hide-right-sidebar'); if ($(window).width() > 991 && $(window).width() < 1201) { $(".chat:not(.right-side-contact .chat)").css('margin-left', -chat_bar); $(".chat:not(.right_side_star .chat)").css('margin-left', -chat_bar); }
        if ($(window).width() < 992) { $('.chat:not(.right-side-contact .chat)').addClass('hide-chatbar'); $('.chat:not(.right_side_star .chat)').addClass('hide-chatbar'); }
    }); $(".close_profile").on('click', function () {
        $('.right-side-contact').addClass('hide-right-sidebar'); $('.right-side-contact').removeClass('show-right-sidebar'); if ($(window).width() > 991 && $(window).width() < 1201) { $(".chat").css('margin-left', 0); }
        if ($(window).width() < 992) { $('.chat').removeClass('hide-chatbar'); }
    }); if ($('.emoj-action').length > 0) { $(".emoj-action").on('click', function () { $('.emoj-group-list').toggle(); }); }
    if ($('.emoj-action-foot').length > 0) { $(".emoj-action-foot").on('click', function () { $('.emoj-group-list-foot').toggle(); }); }
    if ($('.custom-input').length > 0) { const inputRange = document.querySelector('.custom-input'); inputRange.addEventListener('input', function () { const progress = (inputRange.value - inputRange.min) / (inputRange.max - inputRange.min) * 100; inputRange.style.background = `linear-gradient(to top, var(--md-sys-color-on-surface-variant) 0%, var(--md-sys-color-on-surface-variant) ${progress}%, var(--md-sys-color-surface-variant) ${progress}%, var(--md-sys-color-surface-variant) 100%)`; }); }
    if ($('.mute-video').length > 0) {
        $(".mute-video").on('click', function () {
            if ($(this).hasClass("stop")) { $(this).removeClass("stop"); $(".mute-video i").removeClass("bx-video-off"); $(".mute-video i").addClass("bx-video"); $(".join-call .join-video").removeClass("video-hide"); $(".video-avatar").removeClass("active"); $(this).attr("data-bs-original-title", "Stop Camera"); $(".meeting .join-video.user-active").removeClass("video-hide"); $(".join-video.user-active .more-icon").removeClass("vid-view"); $(".action-info.vid-view li .mute-vid i").removeClass("feather-video-off"); $(".action-info.vid-view li .mute-vid i").addClass("feather-video"); }
            else { $(this).addClass("stop"); $(".mute-video i").removeClass("bx-video"); $(".mute-video i").addClass("bx-video-off"); $(".join-call .join-video").addClass("video-hide"); $(".video-avatar").addClass("active"); $(this).attr("data-bs-original-title", "Start Camera"); $(".meeting .join-video.user-active").addClass("video-hide"); $(".add-list .user-active .action-info").addClass("vid-view"); $(".action-info.vid-view li .mute-vid i").removeClass("feather-video"); $(".action-info.vid-view li .mute-vid i").addClass("feather-video-off"); }
        });
    }
    if ($('.mute-bt').length > 0) {
        $(".mute-bt").on('click', function () {
            if ($(this).hasClass("stop")) { $(this).removeClass("stop"); $(".mute-bt i").removeClass("bx-microphone-off"); $(".mute-bt i").addClass("bx-microphone"); $(this).attr("data-bs-original-title", "Mute Audio"); $(".join-video.user-active .more-icon").removeClass("mic-view"); $(".action-info.vid-view li .mute-mic i").removeClass("feather-mic-off"); $(".action-info.vid-view li .mute-mic i").addClass("feather-mic"); }
            else { $(this).addClass("stop"); $(".mute-bt i").removeClass("bx-microphone"); $(".mute-bt i").addClass("bx-microphone-off"); $(this).attr("data-bs-original-title", "Unmute Audio"); $(".join-video.user-active .more-icon").addClass("mic-view"); $(".add-list .user-active .action-info").addClass("vid-view"); $(".action-info.vid-view li .mute-mic i").removeClass("feather-mic"); $(".action-info.vid-view li .mute-mic i").addClass("feather-mic-off"); }
        });
    }
    if ($('.other-mic-off').length > 0) {
        $(".other-mic-off i").on('click', function () {
            if ($(this).parent().hasClass("stop")) { $(this).parent().removeClass("stop"); $(this).removeClass("bx-microphone-off"); $(this).addClass("bx-microphone"); }
            else { $(this).parent().addClass("stop"); $(this).removeClass("bx-microphone"); $(this).addClass("bx-microphone-off"); }
        });
    }
    if ($('.other-video-off').length > 0) {
        $(".other-video-off i").on('click', function () {
            if ($(this).parent().hasClass("stop")) { $(this).parent().removeClass("stop"); $(this).removeClass("bx-video-off"); $(this).addClass("bx-video"); }
            else { $(this).parent().addClass("stop"); $(this).removeClass("bx-video"); $(this).addClass("bx-video-off"); }
        });
    }
    if ($('.video-slide').length > 0) { $('.video-slide').owlCarousel({ items: 4, loop: true, margin: 24, nav: true, dots: false, autoplay: true, smartSpeed: 1000, navText: ['<i class="fa fa-angle-left" data-bs-toggle="tooltip" title="fa fa-angle-left"></i>', '<i class="fa fa-angle-right" data-bs-toggle="tooltip" title="fa fa-angle-right"></i>'], responsive: { 0: { items: 1 }, 500: { items: 1 }, 768: { items: 3 }, 991: { items: 3 }, 1200: { items: 4 }, 1401: { items: 4 } } }) }
    $(".close_profile").on("click", function () { $('.right-user-side').removeClass('open-message'); $('.chat-center-blk .card-comman').addClass('chat-center-space'); }); $(".profile-open").on("click", function () { $('.right-user-side').removeClass('add-setting'); $('.chat-center-blk .card-comman').removeClass('chat-center-space'); }); $("#call-chat").on("click", function () { $('.right-user-side').addClass('open-message'); $('.video-screen-inner').addClass('video-space'); }); $(".close_profile").on("click", function () { $('.right-user-side').removeClass('open-message'); $('.video-screen-inner').removeClass('video-space'); $('.right-side-party').removeClass('open-message'); $('.meeting-list').removeClass('add-meeting'); $('#chat-room').removeClass('open-chats'); $('.meeting-list').removeClass('add-meeting'); $('.call-user-side').addClass('add-setting'); }); $("#add-partispant").on("click", function () { $('.right-side-party').addClass('open-message'); $('#chat-room').removeClass('open-chats'); $('.meeting-list').addClass('add-meeting'); }); $("#show-message").on("click", function () { $('#chat-room').addClass('open-chats'); $('.right-side-party').removeClass('open-message'); $('.meeting-list').addClass('add-meeting'); }); $('.chat-search-btn').on('click', function () { $('.chat-search').addClass('visible-chat'); }); $('.close-btn-chat').on('click', function () { $('.chat-search').removeClass('visible-chat'); }); $(".chat-search .form-control").on("keyup", function () { var value = $(this).val().toLowerCase(); $(".chat .chat-body .messages .chats").filter(function () { $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1) }); });
}); function toggleFullscreen(elem) { elem = elem || document.documentElement; if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) { if (elem.requestFullscreen) { elem.requestFullscreen(); } else if (elem.msRequestFullscreen) { elem.msRequestFullscreen(); } else if (elem.mozRequestFullScreen) { elem.mozRequestFullScreen(); } else if (elem.webkitRequestFullscreen) { elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT); } } else { if (document.exitFullscreen) { document.exitFullscreen(); } else if (document.msExitFullscreen) { document.msExitFullscreen(); } else if (document.mozCancelFullScreen) { document.mozCancelFullScreen(); } else if (document.webkitExitFullscreen) { document.webkitExitFullscreen(); } } }
$(".quantity-btn").on("click", function () {
    var $button = $(this); var oldValue = $button.closest('.product-quantity').find("input.quntity-input").val(); if ($button.text() == "+") { var newVal = parseFloat(oldValue) + 1; } else { if (oldValue > 0) { var newVal = parseFloat(oldValue) - 1; } else { newVal = 0; } }
    $button.closest('.product-quantity').find("input.quntity-input").val(newVal);
});




$(document).on("click", ".remove-product", function () { $(this).parent().parent().hide(); }); if ($('.timepicker').length > 0) { $('.timepicker').datetimepicker({ format: 'HH:mm A', icons: { up: "fas fa-angle-up", down: "fas fa-angle-down", next: 'fas fa-angle-right', previous: 'fas fa-angle-left' } }); }
$(".add-extra").on('click', function () {
    var servicecontent = '<div class="row">' +
        '<div class="col-lg-4 col-sm-6 col-12">' +
        '<div class="form-group add-product">' +
        '<div class="add-newplus">' +
        '<label>Category</label>' +
        '</div>' +
        '<select class="select">' +
        '<option>Choose</option>' +
        '<option>Computers</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '<div class="col-lg-4 col-sm-6 col-12">' +
        '<div class="form-group add-product">' +
        '<label>Choose Category</label>' +
        '<select class="select">' +
        '<option>Choose</option>' +
        '<option>Computers</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '<div class="col-lg-4 col-sm-6 col-12">' +
        '<div class="d-flex align-items-center">' +
        '<div class="form-group w-100 add-product">' +
        '<label>Sub Category</label>' +
        '<select class="select">' +
        '<option>Choose</option>' +
        '<option>Computers</option>' +
        '</select>' +
        '</div>' +
        '<div class="input-blocks">' +
        '<a href="#" class="btn btn-danger-outline trash"><i class="far fa-trash-alt"></i></a>' +
        '</div>' +
        '</div>' +
        '</div>'; setTimeout(function () { $('.select'); setTimeout(function () { $('.select').select2({ minimumResultsForSearch: -1, width: '100%' }); }, 100); }, 100); $(".addservice-info").append(servicecontent); return false;
}); $(".add-extra-item-two").on('click', function () {
    var servicecontent = '<div class="row">' +
        '<div class="col-lg-4 col-sm-6 col-12">' +
        '<div class="form-group add-product">' +
        '<div class="add-newplus">' +
        '<label>Brand</label>' +
        '</div>' +
        '<select class="select">' +
        '<option>Choose</option>' +
        '<option>Computers</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '<div class="col-lg-4 col-sm-6 col-12">' +
        '<div class="form-group add-product">' +
        '<label>Unit</label>' +
        '<select class="select">' +
        '<option>Choose</option>' +
        '<option>Computers</option>' +
        '</select>' +
        '</div>' +
        '</div>' +
        '<div class="col-lg-4 col-sm-6 col-12">' +
        '<div class="d-flex align-items-center">' +
        '<div class="form-group w-100 add-product">' +
        '<label>Selling Type</label>' +
        '<select class="select">' +
        '<option>Choose</option>' +
        '<option>Computers</option>' +
        '</select>' +
        '</div>' +
        '<div class="input-blocks">' +
        '<a href="#" class="btn btn-danger-outline trash"><i class="far fa-trash-alt"></i></a>' +
        '</div>' +
        '</div>' +
        '</div>'; setTimeout(function () { $('.select'); setTimeout(function () { $('.select').select2({ minimumResultsForSearch: -1, width: '100%' }); }, 100); }, 100); $(".add-product-new").append(servicecontent); return false;
}); $(document).on("click", ".remove-color", function () { $(this).parent().parent().parent().hide(); }); if ($('#btnFullscreen').length > 0) { document.getElementById('btnFullscreen').addEventListener('click', function () { toggleFullscreen(); }); }
$(document).ready(function () {
    if ($('#collapse-header').length > 0) { document.getElementById('collapse-header').onclick = function () { this.classList.toggle('active'); document.body.classList.toggle('header-collapse'); } }
    if ($('#file-delete').length > 0) { $("#file-delete").on("click", function () { $('.deleted-table').addClass('d-none'); $('.deleted-info').addClass('d-block'); }); }
    if ($('.pos-category').length > 0) { $('.pos-category').owlCarousel({ items: 6, loop: false, margin: 8, nav: true, dots: false, autoplay: false, smartSpeed: 1000, navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'], responsive: { 0: { items: 2 }, 500: { items: 3 }, 768: { items: 4 }, 991: { items: 5 }, 1200: { items: 6 }, 1401: { items: 6 } } }) }
    if ($('.folders-carousel').length > 0) { $('.folders-carousel').owlCarousel({ loop: true, margin: 15, items: 2, nav: true, dots: false, navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'], responsive: { 0: { items: 1 }, 768: { items: 2 }, 1400: { items: 3 } } }); }
    if ($('.files-carousel').length > 0) { $('.files-carousel').owlCarousel({ items: 3, loop: true, margin: 15, nav: true, dots: false, smartSpeed: 1000, navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'], responsive: { 0: { items: 1 }, 768: { items: 2 }, 1200: { items: 3 } } }) }
    if ($('.video-section').length > 0) {
        $('.video-section').owlCarousel({ loop: true, margin: 15, items: 3, nav: true, dots: false, navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'], responsive: { 0: { items: 1 }, 768: { items: 2 }, 1200: { items: 3 } } }); var playerSettings = { controls: ['play-large'], fullscreen: { enabled: false }, resetOnEnd: true, hideControls: true, clickToPlay: true, keyboard: false, }
        const players = Plyr.setup('.js-player', playerSettings); players.forEach(function (instance, index) { instance.on('play', function () { players.forEach(function (instance1, index1) { if (instance != instance1) { instance1.pause(); } }); }); }); $('.video-section').on('translated.owl.carousel', function (event) { players.forEach(function (instance, index1) { instance.pause(); }); });
    }
    if ($('.video-section').length > 0 || ('.files-carousel').length > 0 || ('.folders-carousel').length > 0) { $('.video-section, .files-carousel, .folders-carousel').each(function () { let carousel = $(this); carousel.on('show.bs.dropdown', '[data-bs-toggle=dropdown]', function () { let dropdown = bootstrap.Dropdown.getInstance(this); $(dropdown._menu).insertAfter(carousel); $(this).next('.dropdown-menu').insertAfter(carousel); }); }) }
    $(".inc").on('click', function () { updateValue(this, 1); }); $(".dec").on('click', function () { updateValue(this, -1); }); function updateValue(obj, delta) { var item = $(obj).parent().find("input"); var newValue = parseInt(item.val(), 10) + delta; item.val(Math.max(newValue, 0)); }
    if ($('.popup-toggle').length > 0) { $(".popup-toggle").click(function () { $(".toggle-sidebar").addClass("open-sidebar"); }); $(".sidebar-closes").click(function () { $(".toggle-sidebar").removeClass("open-sidebar"); }); }
    setTimeout(function () { $('#upload-file').modal('hide') }, 4000); setTimeout(function () { $('#upload-folder').modal('hide') }, 4000); $(".upload-modal").on("hidden.bs.modal", function () { $(".upload-message").modal('show'); setTimeout(function () { $('.upload-message').modal('hide') }, 3000); }); let DIV_CARD = ".card"; let cardFullscreenBtn = document.querySelectorAll('[data-bs-toggle="card-fullscreen"]'); cardFullscreenBtn.forEach((ele) => { ele.addEventListener("click", function (e) { let $this = this; let card = $this.closest(DIV_CARD); card.classList.toggle("card-fullscreen"); card.classList.remove("card-collapsed"); e.preventDefault(); return false; }); }); let DIV_CARD_CLOSE = ".card"; let cardRemoveBtn = document.querySelectorAll('[data-bs-toggle="card-remove"]'); cardRemoveBtn.forEach((ele) => { ele.addEventListener("click", function (e) { e.preventDefault(); let $this = this; let card = $this.closest(DIV_CARD_CLOSE); card.remove(); return false; }); });
});


!function(a){"use strict";a.fn.spartanMultiImagePicker=function(i){var e=0,n=0,t=0,d={fieldName:"",groupClassName:"col-md-4 col-sm-4 col-xs-6",rowHeight:"200px",dropFileLabel:"Drop file here",placeholderImage:{image:"data:image/png;base64,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",width:"64px"},maxCount:"",maxFileSize:"",allowedExt:"png|jpg|jpeg|gif",onAddRow:function(){},onRenderedPreview:function(){},onRemoveRow:function(){},onExtensionErr:function(){},onSizeErr:function(){},directUpload:{loaderIcon:'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">\n                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />\n                            </svg>',status:!1,url:"",success:function(){},error:function(){}}},r=a.extend({},d,i);function o(i,t){n=e;var d=i.groupClassName,r=i.rowHeight,o=i.fieldName,s=i.placeholderImage,l=i.dropFileLabel,p=s.image,A="64px",c='<i class="fas fa-sync fa-spin"></i>';void 0!==i.directUpload.loaderIcon&&(c=i.directUpload.loaderIcon),void 0!==s.width&&(A=s.width);var g=`<div class="${d} spartan_item_wrapper" data-spartanindexrow="${e}" style="margin-bottom : 20px; ">`+'<div style="position: relative;">'+`<div class="spartan_item_loader" data-spartanindexloader="${e}" style=" position: absolute; width: 100%; height: ${r}; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE">`+`${c}`+"</div>"+`<label class="file_upload" style="width: 100%; height: ${r}; border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;">`+`<a href="javascript:void(0)" data-spartanindexremove="${e}" style="position: absolute !important; right : 3px; top: 3px; display : none; background : #ED3C20; border-radius: 3px; width: 30px; height: 30px; line-height : 30px; text-align: center; text-decoration : none; color : #FFF;" class="spartan_remove_row">\n                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">\n                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />\n                                            </svg>\n                                        </a>`+`<img style="width: ${A}; margin: 0 auto; vertical-align: middle;" data-spartanindexi="${e}" src="${p}" class="spartan_image_placeholder" /> `+`<p data-spartanlbldropfile="${e}" style="color : #5FAAE1; display: none; width : auto; ">${l}</p>`+`<img style="width: 100%; vertical-align: middle; display:none;" class="img_" data-spartanindeximage="${e}">`+`<input class="form-control spartan_image_input" accept="image/*" data-spartanindexinput="${e}" style="display : none"  name="${o}" type="file">`+"</label> </div></div>",f=a.parseHTML(g);a(t).append(f);++e;i.onAddRow.call(this,e)}function s(i,n,d){var r=a(n).data("spartanindexinput");if(n.files&&n.files[0]){var s=n.files[0],l=i.allowedExt,p=i.maxFileSize,A=s.type;if(!new RegExp(`(.*?).(${l})$`).test(A)&&""!=l)return 1==a(d).find('img[data-spartanindeximage="'+r+'"]').is(":visible")&&a(d).find('img[data-spartanindexi="'+r+'"]').hide(),i.onExtensionErr.call(this,r,s),!1;if(""==p||""!=p&&s.size/1e3<=p){var c=new FileReader;c.onload=function(e){a(d).find('img[data-spartanindexi="'+r+'"]').hide(),a(d).find('a[data-spartanindexremove="'+r+'"]').show(),a(d).find('img[data-spartanindeximage="'+r+'"]').attr("src",e.target.result),a(d).find('img[data-spartanindeximage="'+r+'"]').show(),i.onRenderedPreview.call(this,r),1==i.directUpload.status&&function(i,e,n){var t=a(e).data("spartanindexinput"),d=new FormData,r=e.files[0],o=i.directUpload.additionalParam;a(n).find('[data-spartanindexloader="'+t+'"]').css("display","flex"),d.append("file",r),void 0!==o&&a.each(o,function(a,i){d.append(a,i)});a.ajax({url:i.directUpload.url,type:"POST",data:d,cache:!1,processData:!1,contentType:!1,success:function(e,d,r){a(n).find('[data-spartanindexloader="'+t+'"]').css("display","none"),void 0!==i.directUpload.success&&i.directUpload.success(this,e,d,r)},error:function(e,d,r){a(n).find('[data-spartanindexloader="'+t+'"]').css("display","none"),void 0!==i.directUpload.error&&i.directUpload.error(this,e,d,r)}})}(i,n,d)},c.readAsDataURL(n.files[0]);var g=!1;0==a(d).find('img[data-spartanindeximage="'+r+'"]').is(":visible")&&(t++,g=!0),r==e-1&&g&&(""==i.maxCount?o(i,d):""!=i.maxCount&&t<i.maxCount&&o(i,d))}else if(""!=p&&s.size/1e3>p)return 1==a(d).find('img[data-spartanindeximage="'+r+'"]').is(":visible")&&a(d).find('img[data-spartanindexi="'+r+'"]').hide(),i.onSizeErr.call(this,r,s),!1}}return this.each(function(){var i=this;o(r,i),a(this).on("change",".spartan_image_input",function(){s(r,this,i)}),a(this).on("click",".spartan_remove_row",function(){!function(i,e,d){var r=a(e).data("spartanindexremove");a(d).find('[data-spartanindexrow="'+r+'"]').remove(),n!=r&&1!=a(d).find('img[data-spartanindeximage="'+n+'"]').is(":visible")||o(i,d),t--,i.onRemoveRow.call(this,r)}(r,this,i)}),a(this).on("dragenter dragover dragstart",".spartan_item_wrapper",function(i){var e,n;i.stopPropagation(),i.preventDefault(),n=a(e=this).data("spartanindexrow"),a(e).find(".file_upload").css({"border-color":"#5FAAE1",background:"#DBE9F3"}),0==a(e).find('img[data-spartanindeximage="'+n+'"]').is(":visible")&&(a(e).find('p[data-spartanlbldropfile="'+n+'"]').show(),a(e).find('img[data-spartanindexi="'+n+'"]').hide())}),a(this).on("dragleave",".spartan_item_wrapper",function(){var i,e;e=a(i=this).data("spartanindexrow"),a(i).find(".file_upload").css({"border-color":"#ddd",background:"none"}),0==a(i).find('img[data-spartanindeximage="'+e+'"]').is(":visible")&&(a(i).find('p[data-spartanlbldropfile="'+e+'"]').hide(),a(i).find('img[data-spartanindexi="'+e+'"]').show())}),a(this).on("drop",".spartan_item_wrapper",function(e){var n,t,d,o,l;e.stopPropagation(),e.preventDefault(),t=i,d=e,o=a(n=this).data("spartanindexrow"),(l=a(t).find('.spartan_image_input[data-spartanindexinput="'+o+'"]')).files=d.originalEvent.dataTransfer.files,a(n).find(".file_upload").css({"border-color":"#ddd",background:"none"}),a(n).find('p[data-spartanlbldropfile="'+o+'"]').hide(),a(n).find('img[data-spartanindexi="'+o+'"]').show(),s(r,l,t)})})}}(jQuery);