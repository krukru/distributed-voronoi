var w;
var task;

function requestTask() {
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
	$.post("paint.php", {data : JSON.stringify(response)})
		.done(function(data) {
			alert("That's it, you can see how it's progressing by clicking on the link bellow the button");
			document.getElementById("result").innerHTML = "link";
		});
}

function stopWorker() { 
	w.terminate();
	w = undefined;
}
