$(document).ready(function() {
    var extractor  = $('#extractor'); //url to extract from text field

    //Added debouce function to restrict calling event on every keyup
    extractor.keyup(debounce(function () {
        var match_url = /\b(https?):\/\/([\-A-Z0-9.]+)(\/[\-A-Z0-9+&@#\/%=~_|!:,.;]*)?(\?[A-Z0-9+&@#\/%=~_|!:,.;]*)?/i;

        //continue if matched url is found in text field
        if (match_url.test(extractor.val())) {
            $("#divUrlFetchInfo").html('');
            $("#divUrlLoader").removeClass('hidelement');
            $("#submit_url").addClass('hidelement');

            var extracted_url = extractor.val().match(match_url)[0];
            var url = "/urlextractor/extract_url";

            $.ajax({
                type: "POST",
                url: url,
                data: 'extractor='+extracted_url,
                success: function(data)
                {
                    var data = $.parseJSON(data);
                    var content = '<div class="extracted_url float-left"><img src="'+data.meta_og_img+'" class="vpb_real_images"></div><div class="extracted_content float-left text-left"><h4><a href="'+extracted_url+'" target="_blank">'+data.meta_og_title+'</a></h4><p>'+data.meta_og_desc+'</p></div><input type="hidden" name="url" value="'+extracted_url+'"><input type="hidden" name="url_title" value="'+data.meta_og_title+'"><input type="hidden" name="url_desc" value="'+data.meta_og_desc+'"><input type="hidden" name="url_image" value="'+data.meta_og_img+'">';

                    //load results in the element
                    $("#divUrlFetchInfo").html(content);
                    $("#submit_url").removeClass('hidelement');
                    $("#divUrlLoader").addClass('hidelement');
                }
            });

        }
    },1000))

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
            }
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

});

function fetchUrlInfo(urlToFetch){
    $("#divUrlFetchInfo").html('');
    $("#submit_url").addClass('hidelement');
    $("#divUrlLoader").removeClass('hidelement');
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
            $("#divUrlFetchInfo").html(content);
            $("#submit_url").removeClass('hidden');
            $("#divUrlLoader").addClass('hidelement');
        }
    });
}

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};