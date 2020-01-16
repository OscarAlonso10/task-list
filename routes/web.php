<?php

use App\Task;
use Illuminate\Http\Request;

Route::delete('/task/{task}', function (Task $task) {
    $task->delete();

    return redirect('/');
});

Route::put('/task/{task}', function (Task $task) {
    $task->done = True;
    $task->update();

    return redirect('/');
});

Route::get('/', function () {
    $tasks = Task::orderBy('created_at', 'asc')->get();
    $taskDone = $tasks->filter(function($value){
        return $value["done"] == True;
    });
    $taskUndone = $tasks->filter(function($value){
        return $value["done"] == False;
    });

    return view('tasks', [
        "taskDone" => $taskDone,
        "taskUndone" => $taskUndone
    ]);
});

Route::post('/task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
        
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Task;
    $task->name = $request->name;
    $task->done = False;
    $task->save();

    return redirect('/');

});