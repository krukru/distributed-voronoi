self.addEventListener("message", function(event) {
	workworkwork(event.data);
}, false);

function workworkwork(data) {
	result = [];
	points = data.points;
	length = points.length;
	pointThickness = 5;
	for (i = data.y; i < data.y + data.rows; i++) {
		for (j = data.x; j < data.x + data.columns; j++) {
			nearestPoint = 0;
			nearestDistance = euclidDist(points[0], j, i);
			for (k = 1; k < length; k++) {
				newDist = euclidDist(points[k], j, i);
				if (newDist < nearestDistance) {
					nearestDistance = newDist;
					nearestPoint = k;
				}
			}
			if (nearestDistance < pointThickness) {
				result.push(-1);
			} else {
				result.push(nearestPoint);
			}
		}
	}
	postMessage(result);
}

function euclidDist(targetPoint, x, y) {
	return Math.sqrt(Math.pow(targetPoint.x - x, 2) + Math.pow(targetPoint.y - y, 2));
}
