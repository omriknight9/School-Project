<?php 

require 'lib/Course.php';
require 'lib/Student.php';
require 'lib/Administrator.php';
require 'lib/DB.php';

$app->get('/', function ($request, $response) {
	return $response->withRedirect('/login');

});

$app->post('/administrator/login', function ($request, $response, $args) {
	Administrator::inspect();
	$body = $request->getParsedBody();
	$administrator_id = $args['administrator_id'];

	$result = Administrator::connect($body['login_email'], $body['login_password']);
	if ($result) {
		return $response->withRedirect('/home');
	} else {
		return $response->withRedirect('/login');
		// return $this->view->render($response, 'login.html');
	}

});

$app->get('/login', function ($request, $response) {
	var_dump($_SESSION);
	return $this->view->render($response, 'login.html');
});

$app->get('/home', function ($request, $response) {
	$courses = Course::getAll();
	$students = Student::getAll();
	return $this->view->render($response, 'home.html', [
		'courses' => $courses,
		'students' => $students
	]);
});


$app->get('/course/{course_id:\d+}', function ($request, $response, $args) {
	$course_id = $args['course_id'];
	$specific_course = Course::getOne($course_id);
	$courses = Course::getAll();
	$students = Student::getAll();
	$students_arr = [];
	$find_students = Course::findStudents($course_id);
	for ($i=0; $i < count($find_students); $i++) { 
		$students_arr []= Student::getOne($find_students[$i]['student_id']);	
	}

	return $this->view->render($response, 'course.html', [
		'specific_course' => $specific_course,
		'courses' => $courses,
		'students' => $students,
		'students_arr' => $students_arr
	]);
});

$app->get('/student/{student_id:\d+}', function ($request, $response, $args) {
	$student_id = $args['student_id'];
	$specific_student = Student::getOne($student_id);
	$courses = Course::getAll();
	$students = Student::getAll();
	$course_arr = [];
	$find_courses = Student::findCourses($student_id);
	for ($i=0; $i < count($find_courses); $i++) { 
		$course_arr []= Course::getOne($find_courses[$i]['course_id']);
	}
	return $this->view->render($response, 'student.html', [
		'specific_student' => $specific_student,
		'courses' => $courses,
		'students' => $students,
		'course_arr' => $course_arr
	]);
});

$app->get('/student/add', function ($request, $response) {
	$students = Student::getAll();
	$courses = Course::getAll();
	$courses2 = Course::getAll();
	$course_arr = [];
	return $this->view->render($response, 'students_add.html', [
		'courses' => $courses,
		'courses2' => $courses2,
		'students' => $students,
		'course_arr' => $course_arr
	]);
});


$app->get('/course/add', function ($request, $response) {
	$courses = Course::getAll();
	$students = Student::getAll();
	$students2 = Student::getAll();
	$students_arr = [];
	return $this->view->render($response, 'course_add.html', [
		'students' => $students,
		'students2' => $students2,
		'courses' => $courses,
		'students_arr' => $students_arr
	]);
});



$app->get('/student/update/{student_id:\d+}', function ($request, $response, $args) {
	$student_id = $args['student_id'];
	$specific_student = Student::getOne($student_id);
	$students = Student::getAll();
	$courses = Course::getAll();
	$courses2 = Course::getAll();
	$course_arr = [];
	$find_courses = Student::findCourses($student_id);
	for ($i=0; $i < count($find_courses); $i++) { 
		$course_arr []= Course::getOne($find_courses[$i]['course_id']);
	}
	return $this->view->render($response, 'students_edit.html', [
		'specific_student' => $specific_student,
		'courses' => $courses,
		'courses2' => $courses2,
		'students' => $students,
		'course_arr' => $course_arr,
		'find_courses' => $find_courses
	]);
});

$app->post('/student/delete/{student_id:\d+}', function ($request, $response, $args) {
	$student_id = $args['student_id'];
	Student::removeFromEnrollment($student_id);
	Student::delete($student_id);
	return $response->withRedirect('/home');

});

$app->post('/course/delete/{course_id:\d+}', function ($request, $response, $args) {
	$course_id = $args['course_id'];
	Course::removeFromEnrollment($course_id);
	Course::delete($course_id);
	return $response->withRedirect('/home');

});

$app->get('/course/update/{course_id:\d+}', function ($request, $response, $args) {
	$course_id = $args['course_id'];
	$specific_course = Course::getOne($course_id);
	$courses = Course::getAll();
	$students = Student::getAll();
	$students2 = Student::getAll();
	$students_arr = [];
	$find_students = Course::findStudents($course_id);
	for ($i=0; $i < count($find_students); $i++) { 
		$students_arr []= Student::getOne($find_students[$i]['student_id']);	
	}

	return $this->view->render($response, 'course_edit.html', [
		'specific_course' => $specific_course,
		'courses' => $courses,
		'students' => $students,
		'students2' => $students2,
		'students_arr' => $students_arr,
		'find_students' => $find_students
	]);
});


$app->post('/student/new_student', function ($request, $response, $args) {
	$body = $request->getParsedBody();
	$new_student = new Student($body['inputName'], $body['inputPhone'], $body['inputEmail'], 'images/students/' .  $_FILES['student_image_link']['name']);
	$new_student_id = $new_student->save();
	$courses_id = $body['courses_id'];
	foreach ($courses_id as $course_id) {
		Student::saveToEnrollment($new_student_id, $course_id);
	}

	$upload_dir = 'C:\xampp\htdocs\omri\Omri\Class Project 2\views\images\students/';
	$upload_file = $upload_dir . basename($_FILES['student_image_link']['name']);
	if (move_uploaded_file($_FILES['student_image_link']['tmp_name'], $upload_file)) {
		echo "localhost:3000/student/{new_student_id}";
	} else {
		die('Virus Attack');
	}
	return $response->withRedirect('/student/' . $new_student_id);

});


$app->post('/course/new_course', function ($request, $response, $args) {
	$body = $request->getParsedBody();
	$new_course = new Course($body['inputName'], $body['inputDescription'], 'images/courses/' .  $_FILES['course_image_link']['name']);
	$new_course_id = $new_course->save();

	$students_id = $body['students_id'];
	foreach ($students_id as $student_id) {
		Course::saveToEnrollment($student_id, $new_course_id);
	}

	$upload_dir = 'C:\xampp\htdocs\omri\Omri\Class Project 2\views\images\courses/';
	$upload_file = $upload_dir . basename($_FILES['course_image_link']['name']);
	if (move_uploaded_file($_FILES['course_image_link']['tmp_name'], $upload_file)) {
		echo "localhost:3000/course/{new_course_id}";
	} else {
		die('Virus Attack');
	}

	return $response->withRedirect('/course/' . $new_course_id);

});

$app->post('/student/updated_student/{student_id:\d+}', function ($request, $response, $args) {
	$body = $request->getParsedBody();
	$student_id = $args['student_id'];
	$courses_id = $body['courses_id'];
	Student::update($student_id, $body['inputEditName'], $body['inputEditPhone'], $body['inputEditEmail'], 'images/students/' .  $_FILES['student_image_link']['name']);
	Student::removeFromEnrollment($student_id);
	foreach ($courses_id as $course_id) {
		Student::saveToEnrollment($student_id, $course_id);
	}
	$upload_dir = 'C:\xampp\htdocs\omri\Omri\Class Project 2\views\images\students/';
	$upload_file = $upload_dir . basename($_FILES['student_image_link']['name']);
	if (move_uploaded_file($_FILES['student_image_link']['tmp_name'], $upload_file)) {
		echo "localhost:3000/home";
	} else {
		die('Virus Attack');
	}

	return $response->withRedirect('/student/' . $student_id);

});

$app->post('/course/updated_course/{course_id:\d+}', function ($request, $response, $args) {
	$body = $request->getParsedBody();
	$course_id = $args['course_id'];
	$students_id = $body['students_id'];
	Course::update($course_id, $body['inputEditName'], $body['inputEditDescription'], 'images/courses/' .  $_FILES['course_image_link']['name']);
	Course::removeFromEnrollment($course_id);
	foreach ($students_id as $student_id) {
		Course::saveToEnrollment($student_id, $course_id);
	}
	$upload_dir = 'C:\xampp\htdocs\omri\Omri\Class Project 2\views\images\courses/';
	$upload_file = $upload_dir . basename($_FILES['course_image_link']['name']);
	if (move_uploaded_file($_FILES['course_image_link']['tmp_name'], $upload_file)) {
		echo "localhost:3000/home";
	} else {
		die('Virus Attack');
	}

	return $response->withRedirect('/course/' . $course_id);

});



