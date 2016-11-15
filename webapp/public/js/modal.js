(function(a) {
    a.createModal = function(b) {
        defaults = {
            title: "",
            message: "Your Message Goes Here!",
            closeButton: true,
            scrollable: false
        };
        var b = a.extend({}, defaults, b);
        var c = (b.scrollable === true) ? 'style="max-height: 420px;overflow-y: auto;"' : "";
        html = '<div class="modal fade" id="myModal">';
        html += '<div class="modal-dialog" style="width: 100%;height: 100%;margin: 0;padding: 0;">';
        html += '<div class="modal-content" style="height: auto;min-height: 100%;border-radius: 0;">';
        html += '<div class="modal-header">';
        html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
        if (b.title.length > 0) {
            html += '<h4 class="modal-title">' + b.title + "</h4>"
        }
        html += "</div>";
        html += '<div class="modal-body" ' + c + ">";
        html += b.message;
        html += "</div>";
        html += '<div class="modal-footer">';
        if (b.closeButton === true) {
            html += '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'
        }
        html += "</div>";
        html += "</div>";
        html += "</div>";
        html += "</div>";
        a("body").prepend(html);
        a("#myModal").modal().on("hidden.bs.modal", function() {
            a(this).remove()
        })
    }

    a.createSmallModal = function(b) {
        defaults = {
            title: "",
            message: "Your Message Goes Here!",
            closeButton: true,
            scrollable: false
        };
        var b = a.extend({}, defaults, b);
        var c = (b.scrollable === true) ? 'style="max-height: 420px;overflow-y: auto;"' : "";
        html = '<div class="modal fade" id="myModal">';
        html += '<div class="modal-dialog modal-sm">';
        html += '<div class="modal-content">';
        html += '<div class="modal-header">';
        html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
        if (b.title.length > 0) {
            html += '<h4 class="modal-title">' + b.title + "</h4>"
        }
        html += "</div>";
        html += '<div class="modal-body" ' + c + ">";
        html += b.message;
        html += "</div>";
        html += '<div class="modal-footer">';
        if (b.closeButton === true) {
            html += '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'
        }
        html += "</div>";
        html += "</div>";
        html += "</div>";
        html += "</div>";
        a("body").prepend(html);
        a("#myModal").modal().on("hidden.bs.modal", function() {
            a(this).remove()
        })
    }

    a.createConfirm = function(b) {
        defaults = {
            title: "",
            message: "Your Message Goes Here!",
            closeButton: false,
            cancelButton: true,
            deleteButton: true,
            confirmButton: false,
            scrollable: false
        };
        var b = a.extend({}, defaults, b);
        var c = (b.scrollable === true) ? 'style="max-height: 420px;overflow-y: auto;"' : "";
        html = '<div class="modal fade" id="confirm-modal">';
        html += '<div class="modal-dialog modal-sm">';
        html += '<div class="modal-content">';
        html += '<div class="modal-header">';
        html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
        if (b.title.length > 0) {
            html += '<h4 class="modal-title">' + b.title + "</h4>"
        }
        html += "</div>";
        html += '<div class="modal-body" ' + c + ">";
        html += b.message;
        html += "</div>";
        html += '<div class="modal-footer">';
        if (b.cancelButton === true) {
            html += '<button type="button" data-dismiss="modal" id="cancel" class="btn">Cancel</button>'
        }
        if (b.deleteButton === true) {
            html += '<button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>'
        }
        if (b.confirmButton === true) {
            html += '<button type="button" data-dismiss="modal" class="btn btn-primary" id="confirm">Confirm</button>'
        }
        if (b.closeButton === true) {
            html += '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'
        }
        html += "</div>";
        html += "</div>";
        html += "</div>";
        html += "</div>";
        a("body").prepend(html);
        a("#confirm-modal").modal().on("hidden.bs.modal", function() {
            a(this).remove()
        })
    }

    a.positionFooter = function positionFooter() {               
       var footerHeight = 0,
       footerTop = 0,
       $footer = $("#site-footer");

        footerHeight = $footer.height();
        footerTop = ($(window).scrollTop()+$(window).height()-footerHeight)+"px";

       if ( ($(document.body).height()+footerHeight) < $(window).height()) {

           $footer.addClass('absolute-bottom-footer');
           // $footer.css({
           //      position: "absolute"
           // })
       } else {
           $footer.removeClass('absolute-bottom-footer');
           // $footer.css({
           //      position: "static"
           // })
       }
                       
    }
})(jQuery);