var site = {

    header: null
    ,loading: null

    ,setVisualLoad: function (status) {
        if (status) {
            $('body').css('cursor', 'progress');
            site.loading.show();
        } else {
            $('body').css('cursor', 'default');
            site.loading.hide();
        }
    }
};

$(function(){
    site.header = $('#header');
    site.loading = $('#loading');

    //tooltip
    $('#content').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })
});