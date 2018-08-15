jQuery(document).ready(function($) {
	if($('.widget.widget_wp_products_wg_widget').length){
		if(GetURLParameter('target')){
			var target = GetURLParameter('target');
		}else{
			if(getCookie('wp_products_target')){
				target=getCookie('wp_products_target');
			}else{
				target='default';
			}
		}
		$.ajax({
			url: ajax_object.ajax_url,
			type: 'POST',
			data: {
				action: 'wp_products_li',
				target: target,
				count: $('.widget.widget_wp_products_wg_widget ul').data('count'),
				order: $('.widget.widget_wp_products_wg_widget ul').data('order')
			},
			success: function(response) {
				var output = $.parseJSON(response);
				if(target!='default' && output.msg!='target_not_correct' && (!getCookie('wp_products_target') || getCookie('wp_products_target')!=target)){
					setCookie('wp_products_target', target, {expires: 60, path: '/'});
				}
				$('.widget.widget_wp_products_wg_widget ul').html(output.html);
				console.log(output.msg);
			},
			error: function(xhr, status, error) {
				console.log(status);
				console.log(error);
				console.log(xhr);
			}
		});
	}
});

function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
    	var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam){
            return sParameterName[1];
        }else{
        	return undefined;
        }
    }
}

function setCookie(name, value, props) {
    props = props || {}
    var exp = props.expires
    if (typeof exp == "number" && exp) {
        var d = new Date()
        d.setTime(d.getTime() + exp*1000)
        exp = props.expires = d
    }
    if(exp && exp.toUTCString) { props.expires = exp.toUTCString() }
    value = encodeURIComponent(value)
    var updatedCookie = name + "=" + value
    for(var propName in props){
        updatedCookie += "; " + propName
        var propValue = props[propName]
        if(propValue !== true){ updatedCookie += "=" + propValue }
    }
    document.cookie = updatedCookie
}

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ))
    return matches ? decodeURIComponent(matches[1]) : undefined
}

function deleteCookie(name) {
    setCookie(name, null, { expires: -1 })
}

