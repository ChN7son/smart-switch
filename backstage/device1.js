var allowSwitch=false;
function atime() {
	var xhr = new XMLHttpRequest();
	xhr.addEventListener("load", function(){
		let item = JSON.parse(xhr.responseText);
		if (item.status==1){
			let now = Date.now()/1000;
			let classVal = document.getElementById("atimeState").getAttribute("class");
			if (now - item.atime <= 180){
				//顯示上線中bg-success
				document.getElementById("atimeState2").innerHTML = "連線中";
				classVal = classVal.replace("bg-danger","bg-success");
				allowSwitch=true;
			} else {
				document.getElementById("atimeState2").innerHTML = "未連線";
				classVal = classVal.replace("bg-success","bg-danger");
				allowSwitch=false;
			}
			document.getElementById("atimeState").setAttribute("class",classVal);
		}
	}, true);
	//xhr.addEventListener("error", uploadFailed, false);
	xhr.open("POST", "atime.php");
	xhr.send(null);
}
function powerSwitch(n) {
	if (allowSwitch){
		let status = document.getElementById("switch_demo"+n).checked;
		if (status)
			status = 1;
		else
			status = 0;
	
		var fd = new FormData();
		fd.append("swop", 1);
		fd.append("id", n);
		fd.append("status", status);
	
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
        		if (xhr.readyState==4 && xhr.status==200) //如果呼叫成功，處理底下
				alert(xhr.responseText);
    		}
		xhr.open("POST", "switch1.php");
		xhr.send(fd);
	} else {
		alert("主機未連線");
	}
}
