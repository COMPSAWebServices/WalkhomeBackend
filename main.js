var walks = {};
/*	13:{
		"status":2,
		"up":"Stauffer",
		"drop":"Leggett",
		"time":"23:34",
		"phone":"6132030017",
		"team":3
	},
	14:{
		"status":4,
		"up":"B",
		"drop":"C",
		"time":"12:43",
		"phone":"5555555555",
		"team":2
	},
	15:{
		"status":1,
		"up":"D",
		"drop":"E",
		"time":"19:41",
		"phone":"6138411873",
		"team":1
	}
}*/
var completeStatus = 5;
function populateWalks(newWalks){
	if (newWalks) {
		for (key in newWalks) {
			id = newWalks[key].id;
			newWalks[key].request_time = newWalks[key].request_time.substr(newWalks[key].request_time.length - 8)
			walks[id] = newWalks[key];

			if (document.getElementById('walk-row-'+id)) {
				updateRow(id);
			}else{
				addRow(id);
			}
		}
	}else{
		for (key in walks) {
			addRow(key);
		}
	}

}

function walkClicked(id){
	//update side view
	var walk = walks[id];
	//set status
	switch (parseInt(walk.status)){
		case 1:
			document.getElementById("update-radio-recived").checked = true
			break; 
		case 2:
			document.getElementById("update-radio-out").checked = true
			break;
		case 3:
			document.getElementById("update-radio-walking").checked = true
			break;
		case 4:
			document.getElementById("update-radio-completed").checked = true
			break;
		case 5:
			document.getElementById("update-radio-in").checked = true
			break;
	}
	//set fields
	document.getElementById("update-pick-input").value = walk.pick_up_location;
	document.getElementById("update-drop-input").value = walk.drop_off_location;
	document.getElementById("update-time-input").value = walk.request_time;
	document.getElementById("update-phone-input").value = walk.phone_number;
	//set walker team selector
	document.getElementById("update-walkers-select").value = walk.team;
	document.getElementById("update-walk").setAttribute("onclick","updateWalk("+id+")");
	//var row = getElementById('walk-row-'+id);
	//var currentColour = row.backgroundColor;
	//row.backgroundColor = currentColour - 25;
}
function checkWalk(walk, errorID){
	var error = "";
	if (walk.phone_number.length != 7 && walk.phone_number.length != 11 && walk.phone_number.length != 10) {
		error = "input proper phone number";
	}
	if (walk.pick_up_location == "" || walk.drop_off_location == "") {
		error = "Fill in pick up / drop off locations";
	};
	document.getElementById(errorID).innerHTML = error;
	return error == "";
}
function updateError(error){
	document.getElementById("update-walk-error").innerHTML = error;
}
function cleanText(input){
	return input.replace(/[^0-9a-zA-Z_\- ]/gi, '');
}
function cleanPhone(input){
	return input.replace(/[^0-9]/gi, '');
}
function addWalk(){
	var walk = {};
	var pick_up_location =  document.getElementById("pick-up-input").value;
	var drop_off_location =  document.getElementById("drop-off-input").value;
	var phone_number =  document.getElementById("phone-number-input").value;
	var request_time =  document.getElementById("time-input").value;
	walk.team =  document.getElementById("walkers-select").value;
	walk.request_time = createTime(request_time);
	walk.status = 1;
	walk.pick_up_location = cleanText(pick_up_location);
	walk.drop_off_location = cleanText(drop_off_location);
	walk.phone_number = cleanPhone(phone_number);

	walk.function = "addWalk";

	if (checkWalk(walk, "create-walk-error")) {
		$.ajax({                                      
		    url: 'api.php',       
		    type: "GET",
		    data: walk 
		}).done(function( data ) {
			data = JSON.parse(data);
			console.log(data);
		    if (data.status = 200) {
		    	walk.request_time = walk.request_time.substr(walk.request_time.length - 8);
				walks[data.id] = walk;
				addRow(data.id);
		    }else{
		    	console.log(data);
		    }
		});
		document.getElementById("pick-up-input").value = "";
		document.getElementById("drop-off-input").value = "";
		document.getElementById("phone-number-input").value = "";
		startTime();
	}
}

function updateWalk(id){
	var walk = walks[id];
	var phoneEditied = false;
	console.log(walk)
	walk.status = getUpdateStatus();
	var pick_up_location = document.getElementById("update-pick-input").value;
	var drop_off_location = document.getElementById("update-drop-input").value;
	var phone_number = document.getElementById("update-phone-input").value;
	var time = document.getElementById("update-time-input").value;
	walk.team = document.getElementById("update-walkers-select").value;


	walk.request_time = createTime(time);

	walk.pick_up_location = cleanText(pick_up_location);
	walk.drop_off_location = cleanText(drop_off_location);
	phone_number = cleanPhone(phone_number);
	if (walk.phone_number != phone_number) phoneEditied=true;
	walk.phone_number = phone_number;

	walk.id = id;
	walk.function = "updateWalk";

	$.ajax({                                      
	    url: 'api.php',       
	    type: "GET",
	    data: walk
	}).done(function( data ) {
		data = JSON.parse(data);
	    if (data.status = 200) {
	    	walk.request_time = walk.request_time.substr(walk.request_time.length - 8);
			console.log("successful update");
			walks[id] = walk;
			updateRow(id);
	    }else{
	    	console.log(data);
	    }
	});
}

function updateRow(id){
	var walk = walks[id];
	var row = document.getElementById('walk-row-'+id);
	if (!row) return;
	row.innerHTML = walkRowContent(walk.pick_up_location, walk.drop_off_location, walk.phone_number, walk.request_time, walk.team, walk.status)
	row.className = "status-"+walk.status;
	console.log(row);
	console.log(walkRowContent(walk.pick_up_location, walk.drop_off_location, walk.phone_number, walk.request_time, walk.team, walk.status));
}

function addRow(id){
	var walk = walks[id];
	var row = walkRow(walk.pick_up_location, walk.drop_off_location, walk.phone_number, walk.request_time, walk.team, walk.status, id);
	var title = document.getElementById('table-title-row');
	title.parentNode.removeChild(title);
	var table = document.getElementById("walk-table");
	var tableContent = table.innerHTML;
	table.innerHTML = walkTitle() + row + tableContent;
}

function getUpdateStatus(){
	if(document.getElementById("update-radio-recived").checked) return 1;
	if(document.getElementById("update-radio-out").checked) return 2;
	if(document.getElementById("update-radio-walking").checked) return 3;
	if(document.getElementById("update-radio-completed").checked) return 4;
	if(document.getElementById("update-radio-in").checked) return 5;
	return 0;
}
function walkTitle(){
	return "<tr id='table-title-row'><th>Status</th><th>Pick Up</th><th>Drop Off</th><th>Phone Number</th><th>Request Time</th><th>Walking Team</th></tr>"
}
function walkRow(up, off, phone, time, team, status, id){
	return "<tr id='walk-row-"+id+"' onClick='walkClicked("+id+")' class='status-"+status+"'>"+
	walkRowContent(up, off, phone, time, team, status)+
	"</tr>";
}
function walkRowContent(up, off, phone, time, team, status){
	var hours = parseInt(time.substring(0, 2));
	if (hours == 0) {
		hours = 12
	}else if(hours > 12){
		hours = hours - 12
	}
	return "<td>"+
		statusToText(""+status)+
		"</td><td>"+
		up+
		"</td><td>"+
		off+
		"</td><td>"+
		phone+
		"</td><td>"+
		hours+time.substring(2,time.length)+
		"</td><td>"+
		team+
        "</td>"
}
function statusToText(num){
	switch(num) {
	    case "1":
	        return "Recived";
	        break;
	    case "2":
	        return "Walkers Out";
	        break;
	    case "3":
	        return "Walking";
	        break;
	    case "4":
	        return "Walk Complete";
	        break;
	    case "5":
	        return "Walkers In";
	        break;
	    default:
	        return num;
	}
}
var timer;
var updateTimer;
var lastRequestTime = createYesterdayTime();
function startTime() {
    var today = new Date();
    var h = today.getHours();
    if (h<10) h = "0"+h;
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('time-input').value = h + ":" + m + ":" + s;
    timer = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};
    return i;
}
function stopTime() {
	if (timer) {
        clearTimeout(timer);
        timer = 0;
    }
    if (document.getElementById('time-input').value =="") startTime();
}

function createTime(time){
	var d = new Date();
	y = (1900 + d.getYear());
	m = d.getMonth() + 1
	if (m<10) m = "0"+m;
	da = d.getDate()
	if (da<10) da = "0"+da;
	
	if (time) {
		return y+"-"+m+"-"+da+" "+time;
	}else{
		h = d.getHours()
		if (h<10) h = "0"+h;
		mi = d.getMinutes()
		if (mi<10) mi = "0"+mi;
		s = d.getSeconds()
		if (s<10) s = "0"+s;
		return y+"-"+m+"-"+da+" "+h+":"+mi+":"+s;
	}
}

function createYesterdayTime(){
	var today = new Date();
	var yesterday = new Date(today);
	yesterday.setDate(today.getDate() - 1);

	var d = today;
	y = (1900 + d.getYear());
	m = d.getMonth() + 1
	if (m<10) m = "0"+m;
	da = d.getDate() - 1;
	if (da<10) da = "0"+da;
	h = d.getHours()
	if (h<10) h = "0"+h;
	mi = d.getMinutes()
	if (mi<10) mi = "0"+mi;
	s = d.getSeconds()
	if (s<10) s = "0"+s;
	return y+"-"+m+"-"+da+" "+h+":"+mi+":"+s;
}

function updateWalks(){
	updateTimer = setTimeout(updateWalks, 15000);
	var d = new Date();
	$.ajax({                                      
	    url: 'api.php',       
	    type: "GET",
	    data: { "function":"getActiveWalks","lastUpdate":  lastRequestTime} 
	}).done(function( data ) {
		data = JSON.parse(data);
	    if (data.status = 200) {
			lastRequestTime = createTime();
			populateWalks(data.walks);
	    	console.log(data);
			for (var i = 0; i < data.removedWalks.length; i++) {
				var id = data.removedWalks[i].id;
				if (walks[id]){
					walks[id].status = completeStatus;
					updateRow(id);
				}
				console.log("finishing: "+id);
			};
	    }else{
	    	console.log(data);
	    }
	});
}
function stopUpdating(){
	if (updateTimer) {
        clearTimeout(updateTimer);
        updateTimer = 0;
    }
}
function main(){
	startTime();
	updateWalks();
}