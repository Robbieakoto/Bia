<!DOCTYPE html>

<html>

		<head>
		     <title>Bia - Question Moderation System</title>
			 <meta charset="UTF-8">
			 <link rel="stylesheet" type="text/css" href="css/style.css">
			 <meta name="description" content="A question moderation System">
			 <meta name="keywords" content="Bia, Question system, spacelab">
			 <meta name="author" content="SpaceLab">
			 <meta name="viewport" content="width=device-width, initial-scale=1.0">
			 <link rel="stylesheet" type="text/css" href="css/style.css">					 
		</head>

		<body onload="checkcookie(); update();">
			<div id="whitebg"></div>
			
			<div id="loginbox">
				<h1>Enter Your Fullname:</h1>
				<p><input type="text" name="name" id="name" placeholder="Enter Your Fullname" class="msginput"></p>
				<br>
				<h1>Enter Name of Your School / Organization:</h1>
				<p><input type="text" name="school" id="school" placeholder="Enter Name of Your School / Organization" class="msginput"></p>
				
				<p class="buttonp">
					<button onclick="login()">Go</button>
				</p>
			</div>
			
			<div class="msg-container">
				<div class="header">Bia</div>
				<div class="msg-area" id="msg-area"></div>
				
				<div class="bottom"><input type="text" name="msginput" class="msginput" id="msginput" onkeydown="if (event.keyCode == 13) sendmsg()" value="" placeholder="Enter your question here ... (Press enter to send message)"></div>
			</div>
			
			
			<script type="text/javascript">
				var msginput = document.getElementById("msginput");
				var msgarea = document.getElementById("msg-area");
				
				function login() {
					var user = document.getElementById("name").value;
					var school = document.getElementById("school").value;
				
					document.cookie="messengerUname=" + user;
					document.cookie="school=" + school;
					checkcookie()
				}
				
				function showlogin() {
					document.getElementById("whitebg").style.display = "inline-block";
					document.getElementById("loginbox").style.display = "inline-block";
				}
				
				function hideLogin() {
					document.getElementById("whitebg").style.display = "none";
					document.getElementById("loginbox").style.display = "none";
				}
				
				function checkcookie() {
					if (document.cookie.indexOf("messengerUname") == -1) {
						showlogin();
					} else {
						hideLogin();
					}
				}
				
				function getcookie(cname) {
					var name = cname + "=";
					var ca = document.cookie.split(';');
					
					for(var i=0; i<ca.length; i++) {
						var c = ca[i];
						while (c.charAt(0)==' ') c = c.substring(1);
						if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
					}
					return "";
				}
				
				function escapehtml(text) {
				  return text
					  .replace(/&/g, "&amp;")
					  .replace(/</g, "&lt;")
					  .replace(/>/g, "&gt;")
					  .replace(/"/g, "&quot;")
					  .replace(/'/g, "&#039;");
				}
				
				function update() {
					var xmlhttp=new XMLHttpRequest();
					var username = getcookie("messengerUname");
					var output = "";
					
					xmlhttp.onreadystatechange=function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
								var response = xmlhttp.responseText.split("\n")
								var rl = response.length
								var item = "";
								
								for (var i = 0; i < rl; i++) {
									item = response[i].split("\\")
									if (item[1] != undefined) {
										if (item[0] == username) {
											output += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\">" + item[2] + "</div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by " + item[0] + " (" +item[1]+ ")</div> </div>";
										} else {
											output += "<div class=\"msgc\"> <div class=\"msg\">" + item[2] + "</div> <div class=\"msgarr\"></div> <div class=\"msgsentby\">Sent by " + item[0] +" (" +item[1]+ ")</div> </div>";
										}
									}
								}
								
								msgarea.innerHTML = output;
								msgarea.scrollTop = msgarea.scrollHeight;
						}
					}
						  xmlhttp.open("GET","get-messages.php?username=" + username,true);
						  xmlhttp.send();
				}
				
				function sendmsg() {
					var message = msginput.value;
					if (message != "") {

						var username = getcookie("messengerUname");
						var school = getcookie("school");
						var xmlhttp=new XMLHttpRequest();
						
						xmlhttp.onreadystatechange=function() {
							if (xmlhttp.readyState==4 && xmlhttp.status==200) {
								message = escapehtml(message)
								msgarea.innerHTML += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\">" + message + "</div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by " + username + "</div> </div>";
								msginput.value = "";
							}
						}
						  xmlhttp.open("GET","update-messages.php?username=" + username + "&message=" + message + "&school=" + school,true);
						  xmlhttp.send();
					}
				}
				
				setInterval(function(){ update() }, 3000);
		    </script>
		</body>



</html>