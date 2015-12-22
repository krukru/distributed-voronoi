var w;
var task;

function requestTask() {
	document.title = "Please wait, working...";
	document.getElementById("result").innerHTML = "";
	$.get("request.php", function(data) {
		task = JSON.parse(data);
		startWorker(task);
	});
}

function startWorker(jsonData) {
	if(typeof(Worker) !== "undefined") {
		if(typeof(w) == "undefined") {
			w = new Worker("js/worker.js");
			w.postMessage(jsonData);
		}
		w.onmessage = taskCompleted;
	} else {
		window.location = "no-worky.html";
	}
}

function taskCompleted(event) {
	stopWorker();
	response = task;
	response.solution = event.data;
	encodedResponse = LZString.compressToBase64(JSON.stringify(response));
	console.log(encodedResponse.length);
	$.post("paint.php", {data : encodedResponse })
		.done(function(data) {
			document.title = "Finished!";
			alert("That's it, thanks for your time. You can check the progress at the link that is now displayed under the buttons");
			document.getElementById("result").innerHTML = "<a href='status.php' target='_blank'>Link to the results</a><br><br><button class='btn-blue' onclick='requestTask();'>Hey that was super fast, let's try again!</button>";
		});
}

function stopWorker() { 
	w.terminate();
	w = undefined;
}
