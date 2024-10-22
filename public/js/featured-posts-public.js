var ppp = frontend_ajax_object.ppp;
var pageNumber = 1;
var ajaxurl = frontend_ajax_object.ajaxurl;
var load_more = true;
var sync_call = true;

function load_posts(pageNumber) {
    var str = '&pageNumber=' + pageNumber + '&ppp=' + ppp + '&action=fp_more_post_ajax';
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(request.response);
            var response_html = response.html;
            load_more = response.load_more;
            document.getElementById("ajax-post-data").innerHTML = document.getElementById("ajax-post-data").innerHTML + response_html;
            sync_call = true;
        }
        else
        {
            sync_call = false;
        }
    };
    request.overrideMimeType("application/json");
    request.open("POST", ajaxurl, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(str);
};

document.addEventListener('scroll', function() {
    var wrap = document.getElementById('ajax-post-data');
    var contentHeight = wrap.offsetHeight;
    var yOffset = window.pageYOffset; 
    var y = yOffset + window.innerHeight;

    if(y >= (contentHeight))
    {
        if(load_more === true )
        {
            if(sync_call == true)
            {
                pageNumber = pageNumber + 1;
                load_posts(pageNumber);        
            }
        }
    }
});