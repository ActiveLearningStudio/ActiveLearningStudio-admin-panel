// toggle text same like class
$.fn.extend({
    toggleText: function (a, b) {
        return this.text((this.text()).trim() === b ? a : b);
    }
});

// generic ajax call
function ajaxCall(callParams, dataParams, callback) {
    $.ajax({
        type: callParams.Type,
        url: callParams.Url,
        quietMillis: 100,
        dataType: callParams.DataType,
        data: dataParams,
        cache: true,
        success: function (response) {
            if (callback) {
                callback(response);
            }
            if (response.message) {
                showMessage(response.message);
            }
        },
        error: function (response) {
            response = JSON.parse(response.responseText);
            if (response.errors) {
                showErrors(response.errors);
            } else {
                alert('Something went wrong, try again later!');
            }
        }
    });
}

/**
 * Generic function for showing the validation errors
 * @param errors
 */
function showErrors(errors) {
    var errors_li = '';
    $.each(errors, function (key, val) {
        if (typeof val === 'string') {
            errors_li += '<li>' + val + '</li>';
        } else {
            errors_li += '<li>' + val[0] + '</li>';
        }
    });
    showMessage(errors_li, 'error');
    // err_sel.append('<div class="alert alert-danger"><ul>' + errors_li + '</ul></div>').scrollTop();
    // $(window).scrollTop(success_sel.scrollTop()); // scroll to the message
}

/**
 * Generic function for showing the success/error message
 * @param message
 * @param type
 * @param title
 * @param addClass
 * @param icon
 */
function showMessage(message, type = 'success', title = 'SUCCESS', addClass = 'bg-success', icon = 'fa fa-check') {
    $("#toastsContainerTopRight").remove();
    if (type === 'error') {
        addClass = 'bg-danger';
        title = 'ERROR';
        icon = 'fa fa-exclamation-triangle';
    }
    $(document).Toasts('create', {
        title: title,
        class: addClass,
        icon: icon,
        autohide: true,
        delay: 8000,
        body: message,
    });
    // success_sel.append('<div class="alert alert-success"><p>' + message + '</p></div>');
    // $(window).scrollTop(success_sel.scrollTop()); // scroll to the message
}

/**
 * Reset ajax call and data params
 */
function resetAjaxParams(type = 'Get') {
    callParams = {};
    dataParams = {};
    callParams.Type = type;
    callParams.DataType = "JSON";
}

/**
 * Reset the form data
 * @param target
 */
function resetForm(target) {
    $(target).trigger("reset");
    $(".form-control").removeClass("is-valid");
    $(".valid-feedback").remove();
}

/**
 * Delete the data
 * @param url
 * @param id
 */
function destroy(url, id) {
    resetAjaxParams("DELETE");
    callParams.Url = url;
    ajaxCall(callParams, dataParams, function (result) {
        $(".action-group").find("[data-id='" + id + "']").closest('tr').fadeOut('slow');
    });
}

/**
 * Form submission on any URL with serialization data pass
 * @param target
 * @param url
 * @param callback
 */
function serializedSubmitForm(target, url, callback) {
    $(target).on('submit', function (e) {
        e.preventDefault();
        resetAjaxParams("POST");
        callParams.Url = url;
        // Set Data parameters
        dataParams = $(this).serialize();
        ajaxCall(callParams, dataParams, function (response) {
            if ($(target).find("input[name='_method']").val() !== 'PUT') {
                resetForm(target);
            }
            if (callback) {
                callback(response);
            }
        });
    });
}

/**
 * Multi Part form submission without processing
 * @param target
 * @param url
 * @param callback
 */
function multiPartFormSubmission(target, url, callback) {
    $(target).on('submit', function (e) {
        e.preventDefault();
        $(target).find('.hide-div').addClass('d-none'); // find div which needs to be hide before Ajax-Call
        $.ajax({
            url: url,
            method: "POST",
            processData: false, // needed for image/file upload
            contentType: false, // needed for image/file upload
            data: new FormData(this),
            dataType: 'json',
            success: function (result) {
                if (result.message) {
                    showMessage(result.message);
                    if ($(target).find("input[name='_method']").val() !== 'PUT') {
                        resetForm(target);
                    }
                }
                if (callback) {
                    callback(result);
                }
            },
            error: function (response) {
                response = JSON.parse(response.responseText);
                if (response.errors) {
                    showErrors(response.errors);
                } else {
                    alert('Something went wrong, try again later!');
                }
            }
        });
    });
}

/**
 * Generic Select2 Initialization function
 * @param target
 * @param url
 * @param textProp
 * @param idProp
 */
function initializeSelect2(target, url, textProp = ['title'], idProp = 'id') {
    $(target).select2({
        theme: 'bootstrap4',
        // allowClear: true,  currently not working - need to debug
        minimumInputLength: 0,
        ajax: {
            url: url,
            dataType: 'json',
            type: "GET",
            delay: 700,
            data: function (params) {
                // Query parameters will be ?search=[term]&type=public&page=1
                return {
                    q: params.term,
                    type: 'public',
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                let result = data.data;
                return {
                    results: $.map(result, function (item) {
                        let text = null;
                        if (textProp[1]) {
                            text = item[textProp[0]] + " - (" + item[textProp[1]] + ")";
                        }
                        return {
                            text: text ? text : item[textProp[0]],
                            id: item[idProp]
                        }
                    }),
                    pagination: {
                        more: data.links.next
                    }
                };
            }
        }
    });
}

/**
 * image preview
 * @param input
 */
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image-preview').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

/**
 * Decode HTML Entities
 * @param html
 * @returns {string}
 */
function decodeHTML(html) {
    let txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

/**
 * Update elastic search index for project
 * @param ele
 * @param id
 * @param index
 */
function updateIndex(ele, id, index) {
    resetAjaxParams();
    callParams.Url = api_url + api_v + "/admin/projects/" + id + "/indexes/" + index;
    ajaxCall(callParams, dataParams, function (response) {
        updatePreviewModal(ele);
    });
}

/**
 * Update text and elements of preview modal after indexing
 */
function updatePreviewModal(ele) {
    let previewProject = $("#preview-project");
    if (previewProject.length) {
        let approveText = $("#approve-text");
        approveText.text("NOT APPROVED");
        if ($(ele).hasClass('approve')) {
            approveText.text("APPROVED");
        }
        $(ele).hide();
    }
}

/**
 * Generic event handlers
 */
$(function () {
    // form cancel button redirect
    $(".cancel").on("click", function (e) {
        e.preventDefault();
        window.location.href = $(this).data('redirect');
    });

    // on image upload
    $("#image").change(function () {
        readURL(this);
    });

    // load preview modal data dynamically
    $("body").on("click", ".modal-preview", function (e) {
        e.preventDefault();
        resetAjaxParams();
        let target = $(this).data('target');
        callParams.Url = $(this).data('href');
        ajaxCall(callParams, dataParams, function (result) {
            $(target).modal('show');
            if (!result.html) {
                $(target).find('.modal-body').html('Data not found!');
                return false;
            }
            $(target).find('.modal-body').html(result.html);
        });
    });
});
