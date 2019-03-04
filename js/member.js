function ajaxCall(params,url,id) {
	
  	request = new ajaxRequest()
  	request.open("POST", url, true)
  	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")

  	request.onreadystatechange = function()
  	{
    	if(this.readyState == 4)
      		if(this.status  == 200)
        		if(this.responseText != null)
          			O(id).innerHTML = this.responseText
  	}
  	request.send(params)

}

function ajaxRequest() {
	try {
		var request = new XMLHttpRequest()
	} 
	catch (e1) {
		try {
    		var request = new ActiveXObject("Msxml2.XMLHTTP")
  		}
		catch (e2) {
    		try {
      			request = new ActiveXObject("Microsoft.XMLHTTP")
    		} 
			catch (e3) {
      			request = false
    		}
  		}
	}
	return request
}

function O(i) { return typeof i == 'object' ? i : document.getElementById(i) }
function S(i) { return O(i).style                                            }
function C(i) { return document.getElementsByClassName(i)                    }

function acceptRequest(user, pass, id) {
	params = 'user=' + user + '&pass=' + pass + '&aid=' + id
	ajaxCall(params, "php/accept.php", "accept")
}

function addFriend(user, pass, id) {
	params = 'user=' + user + '&pass=' + pass + '&sid=' + id
	ajaxCall(params, "php/accept.php", "add")
}

function sendMessage(user, pass, cid, message) {
	O('message').value = ''
	O('message').focus()
	params = 'user=' + user + '&pass=' + pass + '&cid=' + cid + '&message=' + message
	request = new ajaxRequest()
	request.open("POST", "php/send_message.php", true)
  	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")

  	request.onreadystatechange = function()
  	{
    	if(this.readyState == 4)
      		if(this.status  == 200)
        		if(this.responseText != null);
  	}
  	request.send(params)
  	receiveMessage(user, pass, cid)
  	scroll = O('chat')
	scroll.scrollTop = scroll.scrollHeight
	scroll.scrollTop = scroll.scrollHeight
}

function receiveMessage(user, pass, cid) {
	params = 'user=' + user + '&pass=' + pass + '&cid=' + cid
	ajaxCall(params, "php/receive_message.php", "chat")
}

function getOnlineUsers(user, pass) {
	params = 'user=' + user + '&pass=' + pass
	ajaxCall(params, "php/online_users.php", "online")
}

function createGroup(user, pass) {
	params = 'user=' + user + '&pass=' + pass
	for (i = 0; i < arguments.length; i++) {
		 params += '&member_id=' + arguments[i]
	}
	ajaxCall(params, "php/create_group.php", "add")
}