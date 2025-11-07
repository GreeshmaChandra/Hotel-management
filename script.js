// small helper functions
function ajaxPost(url, data, cb){
  const xhr = new XMLHttpRequest();
  xhr.open('POST', url);
  xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xhr.onload = ()=> cb(xhr.responseText);
  xhr.send(data);
}

// confirm before deleting (used if you add delete features later)
function confirmAction(msg){
  return confirm(msg || 'Are you sure?');
}
