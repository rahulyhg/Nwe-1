$(document).ready(function($) {

    var lat = 21.0277644;
    var lng = 105.83415979999995;
    var zoom = 12;
    var map;
    var local = false;
    var markers = [];
    var infowindows = [];
    // frontend


    if ($('#ex2').length > 0) {
        var slider = new Slider('#ex2', {
            tooltip: 'always',
        });

        $('#ex2').slider().on('change slideStop slideStart', function(ev) {
            var text = $('.tooltip-main .tooltip-inner').text();
            text = text.split(' : ');
            text_first = text[0].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
            text_last = text[1].replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
            $('.tooltip-main .tooltip-inner').text(text_first + ' : ' + text_last);
            //console.log(text_first, text_last);
        });
    }
    $('#modal-job').on('hidden.bs.modal', function(e) {
        //window.history.replaceState({}, '', '/');
        history.go(-1);
    })

    $('body').delegate('.rate-star span', 'click', function() {
        rateStar($(this));
    })
    $('body').delegate('[data-target="#modal-review"]', 'click', function() {
        if ($(this).hasClass('user-review')) {
            var $this = $(this).parents('.job-detail');
            $('.review-title h5').text($this.find('.name').text());
            $('.review-profile h5').text($this.find('.employer-info h5').text());
            $('.review-profile .logo').css('background-image', $this.find('.employer-info .thumbnail').css('background-image'));
            $('[name="review_job_id"]').val($this.attr('jobId'));
        } else {
            var $this = $(this).parents('.applicant-item');
            $('.review-profile h5').text($this.find('.name').text());
            $('.review-profile .logo').css('background-image', $this.find('.thumbnail').css('background-image'));
            $('.review-title h5').text($('h3.name').text());
            $('[name="review_user_id"]').val($this.attr('applicant-user'));
        }

        $('[name="review_content"]').val('');
        $('.rate-star span').eq(0).trigger('click');
        $('[name="reason[]"]:checked').trigger('click');
    })


    $('#lookup .form-group .title').click(function() {
        $('#lookup .form-group').toggleClass('active');
    });

    $('.applicant-item .applicant-toggle').click(function() {
        $('.applicant-panel').toggleClass('active');
    });

    $('.parse-price').each(function() {
        if ($(this).text() != "") {
            $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + " đ");
        }
    })

    $('body').delegate('[nav-type]', 'click', function() {
        var nav_type = $(this).attr('nav-type');
        $('.applicant-item').show();
        //console.log(nav_type);
        $('.applicant-item').each(function() {
            //console.log(nav_type);
            if (nav_type == 'pending' && $(this).attr('applicant-status') != nav_type) {

                $(this).hide();
            }
            if (nav_type == 'interview' && ($(this).attr('applicant-status') != nav_type || ($(this).attr('applicant-status') == nav_type && $(this).find('[status="interview"]').hasClass('status-fail')))) {

                $(this).hide();

            }
            if (nav_type == 'work' && ($(this).attr('applicant-status') != nav_type || ($(this).attr('applicant-status') == nav_type && $(this).find('[status="work"]').hasClass('status-fail')))) {

                $(this).hide();

            }
            if (nav_type == 'false' && !$(this).find('[status]').hasClass('status-fail')) {

                $(this).hide();

            }
        })
    })

    $('.print_job_wage').text($('.print_job_wage').text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
    $('input[name="job_wage"]').keyup(function() {
        $(this).parent().find('.print_job_wage').text($(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
    })
    // scroll To

    $(document).on('click', 'a.scrollTo[href^="#"]', function(e) {
        // target element id
        var id = $(this).attr('href');
        // target element
        var $id = $(id);
        if ($id.length === 0) {
            return;
        }
        // prevent standard hash navigation (avoid blinking in IE)
        e.preventDefault();
        // top position relative to the document
        var pos = $id.offset().top - 100;
        // animated top scrolling
        $('body, html').animate({ scrollTop: pos }, "easeOutBounce", );
    });


    // function

    var time;
    var statusForm;
    var pagination = 0;
    var renderSearch = false;
    if (location.pathname == "/xung-quanh") {
        local = true;
        getJobs("", 'html');
        $('.sidebar').on("scroll", function() {
            var elem = $('.sidebar');
            if (parseInt(elem[0].scrollHeight - elem.scrollTop()) <= parseInt(elem.outerHeight() + 3)) {
                if ($('.jobs-listing .job-item').length % 10 == 0) {
                    // pagination++;
                    getJobs($('.search input').val());
                }
            }
        });
    }
    if (location.pathname == "/employer/jobs") {
        getJobs("", 'html', true);
        $('.jobs-listing').on("scroll", function() {
            var elem = $('.jobs-listing');
            if (parseInt(elem[0].scrollHeight - elem.scrollTop()) <= parseInt(elem.outerHeight())) {
                if ($('.jobs-listing .job-item').length % 10 == 0) {
                    // pagination++;
                    getJobs($('.search input').val(), '', true);
                }
            }
        });
        initJobHash();
    }
    if (location.pathname == "/") {
        initAutocomplete();
        $(window).on("scroll", function() {
            var elem = $('body');

            if (parseInt(elem[0].scrollHeight - $(window).scrollTop()) <= parseInt($(window).outerHeight())) {
                // We're at the bottom.
                if ($('.jobs-listing .job-item').length % 10 == 0) {
                    getFilter(
                        $('[name="job_search"]').val(),
                        $('[name="job_form"]').val(),
                        $('[name="job_type"]').val(),
                        $('[name="job_city"]').val(),
                        $('[name="job_wage"]').val(),
                        $('[name="job_wage_type"]').val()
                    );
                }

            }

        });
    }

    if (location.hash == "#register") {
        statusForm = 2;
        $('[status="cv_none"]').removeClass('hide');
        $('[status="cv_check"]').addClass('hide');
        $('#modal-send_cv').modal('show');
        location.hash = "";
    }

    $(window).on('hashchange', function(e) {
        if (location.pathname == "/employer/jobs") {
            initJobHash();
        }
    });

    function initJobHash() {

        var url = window.location.hash;
        if (url != "") {
            var items = url.replace('#', '');
            var array = items.split('&');
            if (array[1]) {
                getCvsByJobId(array[0], array[1]);
            } else {
                getCvsByJobId(array[0], 2);
            }

        }
    }

    $.get("/tiva/js/city.json", function(data) {
        $.each(data, function(index, value) {
            $('select[name="job_city"]').append($('<option>').attr('data-id', index).attr('value', value.name).text(value.name));
        })

    });

    $('.search input').keyup(function() {
        var $this = $(this);
        if (time) {
            clearTimeout(time);
        }
        if (location.pathname == "/xung-quanh") {
            time = setTimeout(function() { pagination = 0;
                getJobs($this.val(), 'html');
                $('.jobs-listing').scrollTop(0); }, 500);
        }
        if (location.pathname == "/employer/jobs") {
            time = setTimeout(function() { pagination = 0;
                getJobs($this.val(), 'html', true);
                $('.jobs-listing').scrollTop(0); }, 500);
        }

        //console.log($(this).val());
    })

    $('.btn-search').click(function() {
        pagination = 0;
        getFilter(
            $('[name="job_search"]').val(),
            $('[name="job_form"]').val(),
            $('[name="job_type"]').val(),
            $('[name="job_city"]').val(),
            $('[name="job_wage"]').val(),
            $('[name="job_wage_type"]').val(),
            'html'
        );
        $(window).scrollTop(0);
    });

    $('.btn-chosen-image').click(function() {
        $(this).parent().find('[file-input]').trigger('click');
    })

    $('[file-input]').change(function() {
        var $this = $(this);
        var group = $this.attr('file-input');
        var employer_id = $this.attr('employer-id');

        if ($this[0].files.length == 0) return;

        var formData = new FormData();
        var ins = $this[0].files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("files[]", $this[0].files[x]);
        }
        $.ajax({
            url: '/employer/gallery-update/' + employer_id,
            method: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.status == 1) {
                    $.each(data.result, function(index, value) {

                        $('[file-output="' + group + '"]').append(
                            '<div class="card">' +
                            '<img class="card-img-top" src="/' + value + '">' +
                            '<input type="hidden" name="gallery[]" value="' + value + '">' +
                            '<i class="photo-close fa fa-trash-o" employer-id="' + employer_id + '"></i>' +
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

    $('body').delegate('.photo-close', 'click', function() {
        var $this = $(this);
        var employer_id = $this.attr('employer-id');
        $.ajax({
            url: '/employer/gallery-delete/' + employer_id,
            method: 'POST',
            data: {
                image: $this.parent().find('input[name="gallery[]"]').val()
            },

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.status == 1) {
                    $this.parent().remove();
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    })

    $('body').delegate('.job-item', 'click', function() {

        if (location.pathname == "/employer/jobs") {
            getCvsByJobId($(this).attr('job-id'));
            location.hash = "#";
        } else {
            getJobById($(this).attr('job-id'));
        }
    })

    $('body').delegate('[job-delete]', 'click', function() {
        var $this = $(this);
        alertify.confirm('Xóa công việc', 'Bạn thực sự muốn xóa?', function() {
            $.ajax({
                url: '/employer/job/delete/' + $this.attr('job-delete'),
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 1) {
                        // pagination = 0;
                        // getJobs($('.search input').val(),'',true);
                        $('[job-id="' + $this.attr('job-delete') + '"]').remove();
                        $this.parents('.job-detail').html('');
                    }
                },
                error: function(xhr) { // if error occured

                },
                complete: function() {

                }
            })
        }, function() {

        });
    })

    $('body').delegate('[cv-update-href]', 'click', function() {
        putUpdateCv($(this).attr('cv-update-href'), $(this).attr('cv-update-status'), $(this));
    })

    $('body').delegate('[cv-active-href]', 'click', function() {
        putActiveCv($(this).attr('cv-active-href'), $(this).attr('cv-update-active'), $(this));
    })

    $('body').delegate('[cv-delete-href]', 'click', function() {
        putDeleteCv($(this).attr('cv-delete-href'), $(this));
    })

    $('body').delegate('.btn-cv', 'click', function() {
        $('#modal-send_cv').attr('job-id', $(this).attr('job-id'));
        statusForm = 1;
        $('[status="cv_none"]').addClass('hide');
        $('[status="cv_check"]').removeClass('hide');
    })


    $('body').delegate('.btn-post-cv', 'click', function() {
        // if(statusForm == 1){
        postCv($(this).attr('job-id'));
        //}
        // if(statusForm == 2){
        //     regCv();
        // }
    })

    $('.btn-update-cv').click(function() {
        updateCv();
    })

    $('.add-row').click(function() {
        var $this = $(this);
        var id = $this.attr('tab-id');
        var makeid = make_id();
        var row = '<tr>';
        //row +='<td><i class="fa fa-arrows"></i></td>';
        //row +='<td><label> ' +
        //'<img class="page-feature-icon" src="/imgs/no_image.jpg" style="max-width: 50px"> ' +
        //'<input type="file" name="tabs['+id+'][rows]['+makeid+'][icon]" accept="image/*" class="chosen-image" style="opacity: 0;position: absolute"> ' +
        //'</label></td>';
        row += '<td><input type="text" name="tabs[' + id + '][rows][' + makeid + '][name]" class="form-content"></td>';
        row += '<td><input type="text" name="tabs[' + id + '][rows][' + makeid + '][description]" class="form-content"></td>';
        row += '<td><input type="text" name="tabs[' + id + '][rows][' + makeid + '][date_start]" class="form-content yearpicker-new"></td>';
        row += '<td><input type="text" name="tabs[' + id + '][rows][' + makeid + '][date_end]" class="form-content yearpicker-new"></td>';
        row += '<td><a href="javascript:void(0)" out-data="text"><i class="icon fas fa-trash"></i></i></a></td>'
        row += '</tr>';
        $('#option-table-' + id + ' tbody').append(row);
        $(".yearpicker-new").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            startDate: '1950',
            endDate: new Date(),
            autoclose: true
        });
    })
    if ($(".select-row").length > 0) {
        $(".select-row").chosen({
            //disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "30%"
        });
    }
    if ($(".standardSelect").length > 0) {
        $(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });
    }
    if ($('.datepicker').length > 0) {
        $('.datepicker').datepicker({

            format: "dd/mm/yyyy",
            //viewMode: "years",
            //minViewMode: "years",
            //startDate: '1950',
            //endDate: new Date(),
            autoclose: true
        });
    }
    if ($(".yearpicker-new").length > 0) {
        $(".yearpicker-new").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            startDate: '1950',
            endDate: new Date(),
            autoclose: true
        });
    }

    $('.select-row').on('change', function(change, deselected) { //selected OR deselected
        //do something
        var $this = $(this);
        var id = $this.attr('tab-id');
        var option = $this.find('option:selected');
        var makeid = make_id();
        var row = '<tr>';

        row += '<td style="display: none"><label> ' +
            '<input type="hidden" name="tabs[' + id + '][rows][' + makeid + '][option_id]" value="' + $this.val() + '"> ' +
            '</label></td>';
        row += '<td><input type="text" disabled class="" value="' + option.text() + '"></td>';
        row += '<td><input type="text" name="tabs[' + id + '][rows][' + makeid + '][description]" class=""></td>';
        row += '<td><input type="text" name="tabs[' + id + '][rows][' + makeid + '][date_start]" class="yearpicker-new"></td>';
        row += '<td><input type="text" name="tabs[' + id + '][rows][' + makeid + '][date_end]" class="yearpicker-new"></td>';
        row += '<td><a href="javascript:void(0)" out-data="select">Xoá</a></td>'
        row += '</tr>';
        $('#option-table-' + id + ' tbody').append(row);
        option.attr('disabled', '');
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

    $('body').delegate('[out-data]', 'click', function() {
        if ($(this).attr('out-data') == "select") {
            var option_id = $(this).parent().parent().find('td:nth-child(1) input').val();
            $(this).parent().parent().parent().parent().parent().parent().find('.select-row option[value="' + option_id + '"]').removeAttr('disabled');
            $(this).parent().parent().parent().parent().parent().parent().find('.select-row').trigger("chosen:updated");
        }
        $(this).parent().parent().remove();
    })

    $('body').delegate('[in-data]', 'click', function() {
        var $this = $(this);
        $.ajax({
            url: $this.attr('a-href'),
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (data.status == 1) {
                    if ($this.attr('in-data') == "select") {
                        var option_id = $this.parent().parent().find('td:nth-child(1) input').val();
                        $this.parent().parent().parent().parent().parent().parent().find('.select-row option[value="' + option_id + '"]').removeAttr('disabled');
                        $this.parent().parent().parent().parent().parent().parent().find('.select-row').trigger("chosen:updated");
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

    $('body').delegate('.chosen-image', 'change', function() {
        var $this = $(this);
        var files = event.srcElement.files;
        if (files[0]) {
            var reader = new FileReader();
            var rFilter = /^(image\/bmp|image\/cis-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x-cmu-raster|image\/x-cmx|image\/x-icon|image\/x-portable-anymap|image\/x-portable-bitmap|image\/x-portable-graymap|image\/x-portable-pixmap|image\/x-rgb|image\/x-xbitmap|image\/x-xpixmap|image\/x-xwindowdump)$/i;
            reader.onload = function(event) {
                if (!rFilter.test(files[0].type)) {
                    alert('error chosen file');
                    $this.val('');
                    return;
                }
                $this.parent().parent().find('img').attr('src', event.target.result);
                $this.parent().find('img').attr('src', event.target.result);
            }
            reader.readAsDataURL(event.target.files[0]);
        } else {
            if ($this.parent().parent().find('img').attr('old-src')) {
                $this.parent().parent().find('img').attr('src', $this.parent().find('img').attr('old-src'));
            } else {
                $this.parent().parent().find('img').attr('src', '/imgs/no_image.jpg');
            }
            if ($this.parent().find('img').attr('old-src')) {
                $this.parent().find('img').attr('src', $this.parent().find('img').attr('old-src'));
            } else {
                $this.parent().find('img').attr('src', '/imgs/no_image.jpg');
            }
        }
    })

    $('[btn-login]').click(function() {
        var $this = $(this);
        $.ajax({
            url: '/user/login',
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                email: $this.parent().find('[name="email"]').val(),
                password: $this.parent().find('[name="password"]').val(),
                role: $(this).attr('btn-login')
            },
            success: function(data) {
                if (data.status == 1) {
                    location.href = '/';
                } else {
                    if (typeof data.errors.message === 'string') {
                        alertify.error(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alertify.error(data.errors.message[Object.keys(data.errors.message)[0]][0]);
                    }
                    //console.log(data.errors.message,data.errors.message[Object.keys(data.errors.message)[0]][0]);
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    });

    $('body').delegate('.review-submit', 'click', function() {

        var reason = $('[name="reason[]"]:checked').map(function() {
            return this.value;
        }).get();
        var star = $('.rate-star span.active').length;

        $.ajax({
            url: '/employer/review-job',
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                star: star,
                reason: reason,
                content: $('[name="review_content"]').val(),
                user_id: $('[name="review_user_id"]').val(),
                job_id: $('[name="review_job_id"]').val(),
            },
            success: function(data) {
                if (data.status == 1) {
                    $('#modal-review [data-dismiss="modal"]').trigger('click');
                    $('#modal-review [data-dismiss="modal"]').trigger('click');
                    $('[applicant-user="' + $('[name="review_user_id"]').val() + '"] [data-target="#modal-review"]').attr('disabled', '');
                    $('[applicant-user="' + $('[name="review_user_id"]').val() + '"] [data-target="#modal-review"]').text('Đã đánh giá');
                    alertify.success('Gửi thành công!')
                } else {
                    if (typeof data.errors.message === 'string') {
                        alertify.error(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alertify.error(data.errors.message[Object.keys(data.errors.message)[0]][0]);
                    }
                    //console.log(data.errors.message,data.errors.message[Object.keys(data.errors.message)[0]][0]);
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    })

    $('body').delegate('.review-user-submit', 'click', function() {

        var reason = $('[name="reason[]"]:checked').map(function() {
            return this.value;
        }).get();
        var star = $('.rate-star span.active').length;

        $.ajax({
            url: '/user/review-job',
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                star: star,
                reason: reason,
                content: $('[name="review_content"]').val(),
                user_id: $('[name="review_user_id"]').val(),
                job_id: $('[name="review_job_id"]').val(),
            },
            success: function(data) {
                if (data.status == 1) {
                    $('#modal-review [data-dismiss="modal"]').trigger('click');
                    $('#modal-review [data-dismiss="modal"]').trigger('click');
                    $('[jobId="' + $('[name="review_job_id"]').val() + '"] [data-target="#modal-review"]').attr('disabled', '');
                    $('[jobId="' + $('[name="review_job_id"]').val() + '"] [data-target="#modal-review"]').text('Đã đánh giá');
                    alertify.success('Gửi thành công!')
                } else {
                    if (typeof data.errors.message === 'string') {
                        alertify.error(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alertify.error(data.errors.message[Object.keys(data.errors.message)[0]][0]);
                    }
                    //console.log(data.errors.message,data.errors.message[Object.keys(data.errors.message)[0]][0]);
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    })

    $('[name="job_city"]').change(function() {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': $(this).val() }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var _latlng = results[0].geometry.location;
                lat = _latlng.lat();
                lng = _latlng.lng();
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    })

    $('body').delegate('.load-map', 'click', function() {
        _geocoder(map.getCenter().lat(), map.getCenter().lng());
    })

    function initAutocomplete() {

        map = new google.maps.Map(document.getElementById('map-wrapper'), {
            center: { lat: lat, lng: lng },
            zoom: zoom,
            mapTypeId: 'roadmap'
        });

        setMarkers(map);
        //console.log(lat, lng);
        // Try HTML5 geolocation.
        if (navigator.geolocation && !local) {
            local = true;
            navigator.geolocation.getCurrentPosition(function(position) {
                //console.log(position);
                // lat = position.coords.latitude;
                // lng = position.coords.longitude;

                //map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));

                _geocoder(position.coords.latitude, position.coords.longitude);

            }, function() {

            });
        } else {

        }

    }

    function _geocoder(_lat, _lng) {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(_lat, _lng);
        geocoder.geocode({ 'latLng': latlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                //console.log(results)
                if (results[1]) {
                    //formatted address
                    //console.log(results[0].formatted_address)
                    //find country name
                    for (var i = 0; i < results[0].address_components.length; i++) {
                        for (var b = 0; b < results[0].address_components[i].types.length; b++) {

                            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                            if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                                //this is the object you are looking for
                                city = results[0].address_components[i];
                                break;
                            }
                        }
                    }
                    //city data
                    //console.log(city.short_name + " " + city.long_name)

                    $('[name="job_city"]').parents('.bootstrap-select').find('.filter-option').text($('[name="job_city"] option:contains("' + city.short_name + '")').text());
                    $('[name="job_city"]').val($('[name="job_city"] option:contains("' + city.short_name + '")').val());

                    var _city = $('[name="job_city"] option:contains("' + city.short_name + '")').text();

                    geocoder.geocode({ 'address': _city }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var _latlng = results[0].geometry.location;
                            lat = _latlng.lat();
                            lng = _latlng.lng();
                            //console.log(lat, lng);
                            $('.btn-search').trigger('click');
                        } else {
                            alert('Geocode was not successful for the following reason: ' + status);
                        }
                    });

                } else {
                    //console.log("No results found");
                }
            } else {
                //console.log("Geocoder failed due to: " + status);
            }
        });
    }

    function setMarkers(map) {
        infowindows = [];
        markers = [];
        var bounds = new google.maps.LatLngBounds();
        $('.jobs-listing > .job-item').each(function() {
            var job_id = $(this).attr('job-id');
            var job_lat = parseFloat($(this).attr('job-lat'));
            var job_lng = parseFloat($(this).attr('job-lng'));
            var job_wage = $(this).attr('job-wage');

            var content = "";
            var content_html = $(this).prop('outerHTML');
            //if(job_wage != ""){
            if ($(this).attr('job-wage') != "") {
                job_wage = $(this).attr('job-wage').replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + " đ";
            } else {
                job_wage = "";
            }

            //content_html = $(content_html).html(job_wage).removeClass('type-box').prop('outerHTML');
            content_html = $(content_html).html('<span>' + $(this).find('.title span').text() + '</span><p> - ' + job_wage + '</p>').removeClass('type-box').prop('outerHTML');
            //}

            if (!isNaN(job_lat) && !isNaN(job_lng)) {
                var marker = new google.maps.Marker({
                    position: { lat: job_lat, lng: job_lng },
                    map: map
                });
                bounds.extend(marker.position);

                markers.push(marker);
                var infowindow = new google.maps.InfoWindow();
                infowindow.setContent(content_html);
                //infowindow.open(map,marker);
                infowindows.push(infowindow);
                google.maps.event.addListener(marker, 'click', (function(marker, content, infowindow) {
                    return function() {
                        getJobById(job_id);

                    };
                })(marker, content, infowindow));
                google.maps.event.addListener(marker, 'mouseover', (function(marker, content, infowindow) {
                    return function() {

                        infowindow.setContent(content_html);
                        infowindow.open(map, marker);

                    };
                })(marker, content, infowindow));
                google.maps.event.addListener(marker, 'mouseout', (function(marker, content, infowindow) {
                    return function() {
                        infowindow.close();
                    };
                })(marker, content, infowindow));
            }
        })
        if (markers.length > 0) {
            map.fitBounds(bounds);
        }

        var markerCluster = new MarkerClusterer(map, markers, { imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m' });
    }

    function getJobs(search, type, employer) {

        if (renderSearch == true) {
            return;
        }
        renderSearch = true;
        $.ajax({
            url: '/ajax/job/search',
            method: 'GET',
            data: {
                search: search,
                pagination: pagination,
                employer: employer
            },
            success: function(data) {

                if (type == "html") {
                    $('.jobs-listing').html(data);
                } else {
                    $('.jobs-listing').append(data);
                }
                if (data != '<input type="hidden" id="totalJob" value="">' && employer != true) {
                    initAutocomplete();
                }
                $('.parse-price').each(function() {
                    if ($(this).text() != "") {
                        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + " đ");
                        $(this).removeClass('parse-price');
                    }
                })
                pagination++;
                renderSearch = false;
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function getFilter(job_search, job_form, job_type, job_city, job_wage, job_wage_type, type) {

        if (renderSearch == true) {
            return;
        }
        renderSearch = true;
        $.ajax({
            url: '/ajax/job/filter',
            method: 'GET',
            data: {
                job_search: job_search,
                job_form: job_form,
                job_type: job_type,
                job_city: job_city,
                job_wage: job_wage,
                job_wage_type: job_wage_type,
                pagination: pagination
            },
            success: function(data) {
                if (type == "html") {
                    $('.jobs-listing').html(data);
                } else {
                    $('.jobs-listing').append(data);
                }
                var total = $('#totalJob').val();
                $('.result > .title').text('Có ' + total + ' Công việc phù hợp').removeClass('hide');
                initAutocomplete();
                $('.parse-price').each(function() {
                    if ($(this).text() != "") {
                        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + " đ");
                        $(this).removeClass('parse-price');
                    }
                })
                pagination++;
                renderSearch = false;
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function getJobById(id) {
        $.ajax({
            url: '/ajax/job/' + id,
            method: 'GET',
            success: function(data) {
                $('#modal-job .modal-content').html(data);
                $('#modal-job').modal('show');
                $('.parse-price').each(function() {
                    if ($(this).text() != "") {
                        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + " đ");
                    }
                })
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function getCvsByJobId(id, number) {
        $.ajax({
            url: '/ajax/job-cvs/' + id,
            method: 'GET',
            success: function(data) {
                $('.job-detail').html(data);
                $('.parse-price').each(function() {
                    if ($(this).text() != "") {
                        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") + " đ");
                    }
                })
                if (number) {
                    $('#job-info li:nth-child(' + number + ') a').trigger('click');
                    // $('#job-applicants').tab('show');
                    $('.job-item[job-id="' + id + '"] .info-location strong').text($('.applicant-item').length);
                    location.hash = "#";
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function putUpdateCv(url, status, element) {
        $.ajax({
            url: url,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status: status,
            },
            success: function(data) {
                //$('#modal-job .modal-content').html(data);
                if (data.status == 1) {

                    element.parents('.applicant-item').find('.progress [status="' + status + '"]').addClass('status-ongoing');
                    element.parents('.button-group').html('');
                } else {
                    if (typeof data.errors.message === 'string') {
                        alert(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alert(data.errors.message[Object.keys(data.errors.message)[0]]);
                    }
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function putActiveCv(url, active, element) {
        $.ajax({
            url: url,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                active: active,
            },
            success: function(data) {
                //$('#modal-job .modal-content').html(data);
                if (data.status == 1) {
                    if (data.cv.status == 'pending' && data.cv.active_interview == "2") {
                        data.cv.status = 'interview';
                    }
                    if (data.cv.status == 'interview' && data.cv.active_trial_work == "2") {
                        data.cv.status = 'trial_work';
                    }
                    if (data.cv.status == 'trial_work' && data.cv.active_work == "2") {
                        data.cv.status = 'work';
                    }
                    if (data.cv.status == 'work' && data.cv.active_complete_work == "2") {
                        data.cv.status = 'complete_work';
                    }
                    if (active == "1") {
                        if (element.parents('.applicant-item').length > 0) {
                            element.parents('.applicant-item').find('.progress [status="' + data.cv.status + '"]').removeClass('status-ongoing').addClass('status-pass');
                        } else {
                            element.parents('.job-detail').find('.progress [status="' + data.cv.status + '"]').removeClass('status-ongoing').addClass('status-pass');
                        }
                    } else {
                        if (element.parents('.applicant-item').length > 0) {
                            element.parents('.applicant-item').find('.progress [status="' + data.cv.status + '"]').removeClass('status-ongoing').addClass('status-fail');
                        } else {
                            element.parents('.job-detail').find('.progress [status="' + data.cv.status + '"]').removeClass('status-ongoing').addClass('status-fail');
                        }
                    }
                    element.parents('.button-group').html('');
                } else {
                    if (typeof data.errors.message === 'string') {
                        alert(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alert(data.errors.message[Object.keys(data.errors.message)[0]]);
                    }
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function putDeleteCv(url, element) {
        $.ajax({
            url: url,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                //$('#modal-job .modal-content').html(data);
                if (data.status == 1) {
                    element.parents('tr').remove();
                } else {
                    if (typeof data.errors.message === 'string') {
                        alert(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alert(data.errors.message[Object.keys(data.errors.message)[0]]);
                    }
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function postCv(job_id) {

        $.ajax({
            url: '/ajax/post-cv/',
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                job_id: job_id
            },
            success: function(data) {
                //$('#modal-job .modal-content').html(data);
                if (data.status == 1) {
                    alertify.success('Nộp cv thành công!');
                    $('.btn-post-cv').text('Bạn đã nộp cv').attr('disabled', 'disabled');
                    $('#modal-send_cv .close').trigger('click');
                } else {
                    if (typeof data.errors.message === 'string') {
                        alertify.error(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alertify.error(data.errors.message[Object.keys(data.errors.message)[0]]);
                    }
                    if (data.status == 401) {
                        $('[status="cv_none"]').removeClass('hide');
                        $('[status="cv_check"]').addClass('hide');
                        $('#modal-send_cv input[name="email"]').val($('[name="user-email"]').val());
                        statusForm = 2;
                    }
                }
            },
            error: function(xhr, error) {
                //console.log(xhr.status);
                if (xhr.status == 401) {
                    $('#modal-job').modal('hide');
                    alert('Bạn chưa đăng nhập');
                    $('#modal-login').modal('show');
                }
            },
            complete: function() {

            }
        })
    }

    function regCv() {

        $.ajax({
            url: 'ajax/reg-cv',
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('#form-reg').serialize(),
            success: function(data) {
                //$('#modal-job .modal-content').html(data);
                if (data.status == 1) {
                    alert('Đăng ký thành công. Bây giờ bạn có thể nộp hồ sơ bằng cách điền email và ấn gửi!');
                    $('[status="cv_none"]').addClass('hide');
                    $('[status="cv_check"]').removeClass('hide');
                    statusForm = 1;
                } else {
                    if (typeof data.errors.message === 'string') {
                        alert(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alert(data.errors.message[Object.keys(data.errors.message)[0]]);
                    }
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function updateCv() {

        $.ajax({
            url: 'ajax/update-cv',
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('#form-update').serialize(),
            success: function(data) {
                //$('#modal-job .modal-content').html(data);
                if (data.status == 1) {
                    alert('Cập nhật thành công!');

                } else {
                    if (typeof data.errors.message === 'string') {
                        alert(data.errors.message);
                    }
                    if (typeof data.errors.message === 'object') {
                        alert(data.errors.message[Object.keys(data.errors.message)[0]]);
                    }
                }
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {

            }
        })
    }

    function make_id() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 9; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function rateStar(element) {
        //console.log(element.index());
        $('.rate-star span').each(function() {
            if ($(this).index() <= element.index()) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        })
    }

});