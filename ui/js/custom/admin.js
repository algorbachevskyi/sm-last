$(function() {

    $('#side-menu').metisMenu();
    activate('activeTable');

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {

    // errors hendler:
//    var productValidator = new FormValidator('product-form', [
//    {
//        name: 'name',
//        display: 'Назва',
//        rules: 'required'
//    }, {
//        name: 'count',
//        display: 'Кількість',
//        rules: 'is_natural_no_zero'
//    }, {
//        name: 'price',
//        display: 'Ціна',
//        rules: 'is_natural_no_zero'
//    }, {
//        name: 'about',
//        display: 'Опис',
//        rules: 'required'
//    }], function(errors, event) {
//        if (errors.length > 0) {
//            for (var i=0; i<errors.length; i++) {
//                $('#' + errors[i].id).parent().addClass('has-error');
//            }
//        }
//    });
//

    $('.form-group').on('click',function(){
        var errorClass = ' has-error';
        this.className = this.className.replace(errorClass,"");
    });

    $("#count").keypress(function (e) {
        $("#count").parent().removeClass('has-error');
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#count").parent().addClass('has-error');
            return false;
        }
    });

    $("#price").keypress(function (e) {
        $("#price").parent().removeClass('has-error');
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#price").parent().addClass('has-error');
            return false;
        }
    });

    // configs:
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

    // file upload (dropzone):
    Dropzone.options.myAwesomeDropzone = {
        maxFilesize: 2,
        maxFiles: 6,
        parallelUploads: 6,
        acceptedFiles: 'image/*',
        autoProcessQueue: false,
        addRemoveLinks: 'dictRemoveFile',
        dictInvalidFileType : 'Файл повинен бути зображенням!',
        dictFileTooBig : 'Зображення завелике (максимум 2 MB)',
        init: function() {
            var submitButton = $("#submit-product");
            myDropzone = this; // closure

            submitButton.on("click", function() {
                event.preventDefault();
                if (validProductForm()) {
                    if (myDropzone.getQueuedFiles().length === 0) {
                        $('#product').submit();
                    } else {
                        var files = [];
                        for (var i=0; i<myDropzone.getQueuedFiles().length; i++) {
                            files.push(myDropzone.getQueuedFiles()[i].name);
                        }

                        $('#product-files').val(files);
                        myDropzone.processQueue();
                    }
                }
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

    var validProductForm = function() {

        if($('#name').val() !== '' &&
            $('#count').val() !== '' &&
            $('#price').val() !== '' &&
            $('#about').val() !== ''
            ) return true;

        return false;
    }

});

var activate = function(tableId) {

    $('.order-table').hide();
    $('#' + tableId).show();

};