$(document).ready(function() {
    var extractor  = $('#extractor'); //url to extract from text field

    extractor.keyup(function () {
        var match_url = /\b(https?):\/\/([\-A-Z0-9.]+)(\/[\-A-Z0-9+&@#\/%=~_|!:,.;]*)?(\?[A-Z0-9+&@#\/%=~_|!:,.;]*)?/i;

        //continue if matched url is found in text field
        if (match_url.test(extractor.val())) {
            var extracted_url = extractor.val().match(match_url)[0];
            console.log(extracted_url);
            var url = "/urlextractor/extract_url";

            $.ajax({
                type: "POST",
                url: url,
                data: $("#urlextractform").serialize(), // serializes the form's elements.
                success: function(data)
                {
                    var data = $.parseJSON(data);
                    var content = '<div class="extracted_url float-left"><img src="'+data.meta_og_img+'" class="vpb_real_images"></div><div class="extracted_content float-left text-left"><h4><a href="'+extracted_url+'" target="_blank">'+data.meta_og_title+'</a></h4><p>'+data.meta_og_desc+'</p></div><input type="hidden" name="url" value="'+extracted_url+'"><input type="hidden" name="url_title" value="'+data.meta_og_title+'"><input type="hidden" name="url_desc" value="'+data.meta_og_desc+'"><input type="hidden" name="url_image" value="'+data.meta_og_img+'">';

                    //load results in the element
                    $("#divUrlFetchInfo").html('');
                    $("#divUrlFetchInfo").html(content);
                    $("#submit_url").removeClass('hidden');
                }
            });

        }
    })

    $("#submit_url").click(function(e) {
        var url = "/urlextractor/storeUrlInfo";

        $.ajax({
            type: "POST",
            url: url,
            data: $("#url_info").serialize(), // serializes the form's elements.
            success: function (data) {
                var response = data;
                console.log(data);
                location.reload();
                /*if (response.status == 'badrequest') {
                     $("#flashMessage").html('');
                     $("#flashMessage").removeClass('hidden');
                     $("#flashMessage").html(`
                     <div class="panel panel-danger">
                     <div class="panel-heading">
                     <div class="panel-title">
                     ` + response.message + `
                     </div>
                     </div>
                     </div>`);
                 } else {
                     $("#flashMessage").html('');
                     $("#flashMessage").removeClass('hidden');
                     $("#flashMessage").html(`
                     <div class="panel panel-success">
                     <div class="panel-heading">
                     <div class="panel-title">
                     Password reset successfully.
                     </div>
                     </div>
                     </div>`);
                 }*/
            }
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

});

function fetchUrlInfo(urlToFetch){
    var url = "/urlextractor/extract_url";
    $.ajax({
        type: "POST",
        url: url,
        data: 'extractor='+urlToFetch,
        success: function(data)
        {
            var data = $.parseJSON(data);
            var content = '<div class="extracted_url float-left"><img src="'+data.meta_og_img+'" class="vpb_real_images"></div><div class="extracted_content float-left text-left"><h4><a href="'+urlToFetch+'" target="_blank">'+data.meta_og_title+'</a></h4><p>'+data.meta_og_desc+'</p></div><input type="hidden" name="url" value="'+urlToFetch+'"><input type="hidden" name="url_title" value="'+data.meta_og_title+'"><input type="hidden" name="url_desc" value="'+data.meta_og_desc+'"><input type="hidden" name="url_image" value="'+data.meta_og_img+'">';

            //load results in the element
            $("#divUrlFetchInfo").html('');
            $("#divUrlFetchInfo").html(content);
            $("#submit_url").removeClass('hidden');
        }
    });
}