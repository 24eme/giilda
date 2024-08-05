function metabase_redirect_to_external_auth(metabase_webpath, metabase_external_auth_page) {
	var href = document.location.href;
	if (href.indexOf('/metabase/public/') != -1) {
		return;
	}
	var xhttp = new XMLHttpRequest();
	xhttp.open("GET", metabase_webpath + "/api/user/current", true);
	xhttp.send();
	xhttp.onload = function() {
		if(xhttp.status != 401) {
			return;
		}
		var url = new URL(document.location.href);
		if(!url.search.match(/noautologin/)) {
        		url.searchParams.append('noautologin', '1');
            document.location.href = metabase_external_auth_page + '?redirect=' + encodeURIComponent(url);
		}
	}
}

function metabase_login_and_redirect(username, password, remember = true, metabase_webpath = null) {
  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "/metabase/api/session", false);
  xhttp.setRequestHeader("Content-type", "application/json");
  xhttp.send('{"password":"' + password + '","username":"' + username + '","remember":'+ remember +'}');

  var url = new URL(document.location.href);
  var urlRedirect = url.searchParams.get('redirect');

  if(!urlRedirect && metabase_webpath) {
    document.location.href = metabase_webpath;

    return;
  }

  if(!urlRedirect) {

    window.history.back();

    return;
  }

  var urlRedirect = new URL(urlRedirect);
  document.location.href = urlRedirect.href;
}
