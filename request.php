<?php

$pointsKey = 'test.points';
$tasksKey = 'test.tasks';

$pendingTasksKey = 'test.pending';
$unsolvedTasksKey = 'test.unsolved';

$pendingTasks = apc_fetch($pendingTasksKey);
$unsolvedTasks = apc_fetch($unsolvedTasksKey);
$points = apc_fetch($pointsKey);
if (empty($unsolvedTasks) == false) {
	$task = array_pop($unsolvedTasks);
	apc_store($unsolvedTasksKey, $unsolvedTasks);
	$pendingTasks[] = $task;
	apc_store($pendingTasksKey, $pendingTasks);
	giveTask($task, $points);
} else if (empty($pendingTasks) == false) {
	$randomTaskIndex = rand(0, count($pendingTasks) - 1);
	giveTask($pendingTasks[$randomTaskIndex], $points);
} else {
	echo 'No more tasks to give, hurrey!';
}

function giveTask(array $task, $points) {
	$data = array_merge($task, ['points' => $points]);
	echo json_encode($data);
}

