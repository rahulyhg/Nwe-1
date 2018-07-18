
$(document).ready(function($) {

	"use strict";

	[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {
		new SelectFx(el);
	} );

	jQuery('.selectpicker').selectpicker;


	$('#menuToggle').on('click', function(event) {
		$('body').toggleClass('open');
	});

	$('.search-trigger').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		$('.search-trigger').parent('.header-left').addClass('open');
	});

	$('.search-close').on('click', function(event) {
		event.preventDefault();
		event.stopPropagation();
		$('.search-trigger').parent('.header-left').removeClass('open');
	});

    $('.print_job_wage').text($('.print_job_wage').text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
    $('input[name="job_wage"]').keyup(function(){
        $(this).parent().find('.print_job_wage').text($(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
    })
	// $('.user-area> a').on('click', function(event) {
	// 	event.preventDefault();
	// 	event.stopPropagation();
	// 	$('.user-menu').parent().removeClass('open');
	// 	$('.user-menu').parent().toggleClass('open');
	// });
	$('body').delegate('.chosen-image','change',function(){
		var $this = $(this);
		var files = event.srcElement.files;
      	if(files[0]){
	        var reader = new FileReader();
            var rFilter = /^(image\/bmp|image\/cis-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x-cmu-raster|image\/x-cmx|image\/x-icon|image\/x-portable-anymap|image\/x-portable-bitmap|image\/x-portable-graymap|image\/x-portable-pixmap|image\/x-rgb|image\/x-xbitmap|image\/x-xpixmap|image\/x-xwindowdump)$/i;
            reader.onload = function(event) {
                if (!rFilter.test(files[0].type)) {
                    alert('error chosen file');
                    $this.val('');
                    return;
                }
	            $this.parent().find('img').attr('src', event.target.result);
	        }
	        reader.readAsDataURL(event.target.files[0]);
      	}else{
            if($this.parent().find('img').attr('old-src')){
                $this.parent().find('img').attr('src', $this.parent().find('img').attr('old-src'));
            }else{
                $this.parent().find('img').attr('src', '/imgs/no_image.jpg');
            }

      	}
	})

    $('.btn-chosen-image').click(function(){
        $(this).parent().find('[file-input]').trigger('click');
    })

    $('[file-input]').change(function(){
        var $this = $(this);
        var group = $this.attr('file-input');
        var employer_id = $this.attr('employer-id');

        if($this[0].files.length == 0) return;

        var formData = new FormData();
        var ins = $this[0].files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", $this[0].files[x]);
        }
        $.ajax({
            url: '/employer/gallery/update/'+employer_id,
            method: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if(data.status == 1){
                    $.each(data.result, function( index, value ) {

                        $('[file-output="'+group+'"]').append(
                            '<div class="col-md-3">' +
                                '<div class="card">' +
                                    '<img class="card-img-top" src="/'+value+'">' +
                                    '<input type="hidden" name="gallery[]" value="'+value+'">' +
                                    '<i class="photo-close fa fa-trash-o" employer-id="'+employer_id+'"></i>'+
                                '</div>' +
                            '</div>'
                        );
                    });
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {
                $this.val('');
            }
        })
    })

    $('body').delegate('.photo-close','click',function(){
        var $this = $(this);
        var employer_id = $this.attr('employer-id');
        $.ajax({
            url: '/employer/gallery/delete/'+employer_id,
            method: 'POST',
            data: {
                image: $this.parent().find('input[name="gallery[]"]').val()
            },

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if(data.status == 1){
                    $this.parent().parent().remove();
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    })

    $('.show-hide').click(function(){
        if($(this).attr('status') == "show"){
            $(this).parent().parent().find('.card-body').hide('slow');
            $(this).attr('status','hide');
            $(this).text('Show');
        }else{
            $(this).parent().parent().find('.card-body').show('slow');
            $(this).attr('status','show');
            $(this).text('Hide');
        }
    });

    $('.add-row').click(function(){
        console.log(1);
        var $this = $(this);
        var id = $this.attr('tab-id');
        var makeid = make_id();
        var row = '<tr>';
        row +='<td><i class="fa fa-arrows"></i></td>';
        row +='<td><label> ' +
        '<img class="page-feature-icon" src="/imgs/no_image.jpg" style="max-width: 50px"> ' +
        '<input type="file" name="tabs['+id+'][rows]['+makeid+'][icon]" accept="image/*" class="chosen-image" style="opacity: 0;position: absolute"> ' +
        '</label></td>';
        row +='<td><input type="text" name="tabs['+id+'][rows]['+makeid+'][name]" class="form-control"></td>';
        row +='<td><input type="text" name="tabs['+id+'][rows]['+makeid+'][description]" class="form-control"></td>';
        row +='<td><input type="text" name="tabs['+id+'][rows]['+makeid+'][date_start]" class="form-control yearpicker-new"></td>';
        row +='<td><input type="text" name="tabs['+id+'][rows]['+makeid+'][date_end]" class="form-control yearpicker-new"></td>';
        row +='<td><a href="javascript:void(0)" out-data="text"><i class="fa fa-trash-o"></i></a></td>'
        row +='</tr>';
        $('#tabs-table-'+id+' tbody').append(row);
        $(".yearpicker-new").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            startDate: '1950',
            endDate: new Date()
        });
    })
        if($(".select-row").length > 0){
        $(".select-row").chosen({
            //disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });
    }
    $('.select-row').on('change', function(change, deselected) { //selected OR deselected
        //do something
        var $this = $(this);
        var id = $this.attr('tab-id');
        var option = $this.find('option:selected');
        var makeid = make_id();
        var row = '<tr>';
        row +='<td><i class="fa fa-arrows"></i></td>';
        row +='<td><label> ' +
        '<img class="page-feature-icon" src="'+option.attr('src')+'" style="max-width: 50px"> ' +
        '<input type="hidden" name="tabs['+id+'][rows]['+makeid+'][option_id]" value="'+$this.val()+'"> ' +
        '</label></td>';
        row +='<td><input type="text" disabled class="form-control" value="'+option.text()+'"></td>';
        row +='<td><input type="text" name="tabs['+id+'][rows]['+makeid+'][description]" class="form-control"></td>';
        row +='<td><input type="text" name="tabs['+id+'][rows]['+makeid+'][date_start]" class="form-control yearpicker-new"></td>';
        row +='<td><input type="text" name="tabs['+id+'][rows]['+makeid+'][date_end]" class="form-control yearpicker-new"></td>';
        row +='<td><a href="javascript:void(0)" out-data="select"><i class="fa fa-trash-o"></i></a></td>'
        row +='</tr>';
        $('#tabs-table-'+id+' tbody').append(row);
        option.attr('disabled','');
        $this.val('').trigger("chosen:updated");
        $(".yearpicker-new").datepicker({
            autoclose: true,
            format: " yyyy",
            viewMode: "years",
            minViewMode: "years",
            startDate: '1950',
            endDate: new Date()
        });
    });

    $('body').delegate('[out-data]','click',function(){
        if($(this).attr('out-data') == "select"){
            var option_id = $(this).parent().parent().find('td:nth-child(2) input').val();
            $(this).parent().parent().parent().parent().parent().find('.select-row option[value="'+option_id+'"]').removeAttr('disabled');
            $(this).parent().parent().parent().parent().parent().find('.select-row').trigger("chosen:updated");
        }
        $(this).parent().parent().remove();
    })

    $('body').delegate('[in-data]','click',function(){
        var $this = $(this);
        $.ajax({
            url: $this.attr('a-href'),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if(data.status == 1){
                    if($this.attr('in-data') == "select"){
                        var option_id = $this.parent().parent().find('td:nth-child(2) input').val();
                        $this.parent().parent().parent().parent().parent().find('.select-row option[value="'+option_id+'"]').removeAttr('disabled');
                        $this.parent().parent().parent().parent().parent().find('.select-row').trigger("chosen:updated");
                    }
                    $this.parent().parent().remove();
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })

    })

    $('body').delegate('[job-delete],[employer-delete],[option-delete],[delete-href]','click', function(){
        var $this = $(this);
        var person = prompt("Please enter pass:");
        if (person  == "123456") {

            $.ajax({
                url: $this.attr('a-href'),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if(data.status == 1){

                        table.draw();
                    }
                },
                error: function(xhr) { // if error occured

                },
                complete: function() {

                }
            })
        }
    })

    setTimeout(function(){
        $('.sufee-alert').hide('slow');
    },2000);
    if($('.yearpicker').length > 0){
        $('.yearpicker').datepicker({
            autoclose: true,
            format: " yyyy",
            viewMode: "years",
            minViewMode: "years",
            startDate: '1950',
            endDate: new Date()
        });
    }
    if($('.datepicker').length > 0){
        $('.datepicker').datepicker({

            format: "dd/mm/yyyy",
            //viewMode: "years",
            //minViewMode: "years",
            //startDate: '1950',
            //endDate: new Date()
        });
    }
    $('form').bind("keypress", function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
    //$('.ck').summernote();
});
function make_id() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 9; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}
