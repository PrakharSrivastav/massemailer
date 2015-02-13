$(function () {
    $("#upload_image_status_success ,#upload_image_status_warning").hide();

    $("#upload_template_image").click(function () {
        var allowed_exts = ["doc", "docx", "ppt", "pptx", "xls", "xlsx", "zip", "rar", "txt", "rtf", "pdf", "htm", "html", "jpg", "jpeg", "gif", "png", "tif", "img"];
        var file_name = $("#template_image").val();
        var image_name = $("#template_image_name").val();

        if (image_name.trim() === "") {
            $("#upload_image_status_warning").show();
            $("#upload_image_status_warning").html("<p><i class='fa fa-times'></i> Please provide a name to your Attachment</p>");
            return false;
        }

        if (file_name.trim() !== "") {
            var splitted_file_name = file_name.trim().split(".");
            var len = splitted_file_name.length;
            if (len > 0) {
                var ext = splitted_file_name[len - 1];
                if ($.inArray(ext.toLowerCase(), allowed_exts) !== -1) {
                    var ajaxRequest;
                    try {
                        ajaxRequest = new XMLHttpRequest();
                    }
                    catch (e) {
                        try {
                            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                        }
                        catch (e) {
                            try {
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            catch (e) {
                                alert("Problem with the browser");
                            }
                        }
                    }

                    $("#upload_template_image").prop('value', 'Uploading...');
                    $("#upload_image_status_warning").hide();

                    var formData = new FormData(document.getElementById("template_image_upload_form"));
                    formData.append("file_ext", "." + ext);
                    formData.append("target_file_name",image_name);
                    ajaxRequest.open('POST', base_url + 'templatecontroller/ajaxy', false);
                    ajaxRequest.send(formData);
                    $("#upload_image_status_success").html("<p><i class='fa fa-check'></i>" + ajaxRequest.responseText + "</p>");
                    $("#upload_image_status_success").show();
                    $("#upload_template_image").prop('value', 'Upload');
                }
                else {
                    $("#upload_image_status_warning").show();
                    $("#upload_image_status_warning").html("<p><i class='fa fa-times'></i> Please upload an allowable attachment. Please check the file extension and try again.</p>");
                    return false;
                }
            }
            else {
                $("#upload_image_status_warning").show();
                $("#upload_image_status_warning").html("<p><i class='fa fa-times'></i> Please select a file name with proper extension</p>");
                return false;
            }
        }
        else {
            $("#upload_image_status_warning").show();
            $("#upload_image_status_warning").html("<p><i class='fa fa-times'></i> Please select a file to upload</p>");
            return false;
        }
    });

});
