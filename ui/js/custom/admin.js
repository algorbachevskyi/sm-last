$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    Dropzone.options.myAwesomeDropzone = {
        maxFilesize: 1,
        maxFiles: 6,
        parallelUploads: 6,
        acceptedFiles: 'image/*',
        autoProcessQueue: false,
        addRemoveLinks: 'dictRemoveFile',
        dictInvalidFileType : 'Файл повинен бути зображенням!',
        dictFileTooBig : 'Зображення завелике (максимум 1 MB)',
        init: function() {
            var submitButton = $("#submit-product");
            var
            myDropzone = this; // closure

            submitButton.on("click", function() {
//                event.preventDefault();
                myDropzone.processQueue(); // Tell Dropzone to process all queued files.

            });

            // You might want to show the submit button only when
            // files are dropped here:
            this.on("addedfile", function() {
                // Show submit button here and/or inform user to click it.
            });

            this.on("complete", function() {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    $('#product').submit();
                }
            });

        }
    };

});
