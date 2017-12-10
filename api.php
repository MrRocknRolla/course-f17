<?php
/**
 * Created by PhpStorm.
 * User: iamcaptaincode
 * Date: 10/13/2016
 * Time: 8:56 AM
 */

require_once 'config.php';
require_once 'vendor/autoload.php';
use Course\Http\Methods as Methods;
use Course\Controllers\TimeFrameController as TimeFrameController;
use Course\Controllers\CourseController as CourseController;

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r)  use ($baseURI) {
    /** TOKENS CLOSURES */
    $handlePostToken = function ($args) {
        $tokenController = new \Course\Controllers\TokensController();
        //Is the data via a form?
        if (!empty($_POST['username'])) {
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = $_POST['password'] ?? "";
        } else {
            //Attempt to parse json input
            $json = (object) json_decode(file_get_contents('php://input'));
            if (count((array)$json) >= 2) {
                $username = filter_var($json->username, FILTER_SANITIZE_STRING);
                $password = $json->password;
            } else {
                http_response_code(\Course\Http\StatusCodes::BAD_REQUEST);
                exit();
            }
        }
        return $tokenController->buildToken($username, $password);

    };

    /*TEST CLOSURE*/
    $testCourseModel = function ($args) {
        $Course = new CourseController();

        $val = $Course->test(3);

        return $val;
    };


    $getAllCourses = function ($args)
    {
        $Course = new CourseController();

        $val = $Course->getAllCourses($args);

        return $val;


    };

    /**Routes **/
    /** Course CLOSURE **/
    $handleCourse = function ($args) {
        $CourseController = new CourseController();
        return $CourseController->getCourseById($args['id']);
    };


    $updateCourse = function ($args) {
        $CourseCtrl = new CourseController();
    $json = (object) json_decode(file_get_contents('php://input'));

        return $CourseCtrl->update($args['id'], $json);
    };

    $createCourse = function ($args) {
        $CourseCtrl = new CourseController();
        $json = $_POST;
        if (empty($json)) {
            $json = (object) json_decode(file_get_contents('php://input'));
        }

        return $CourseCtrl->create($json);
    };

    $deleteCourse = function ($args) {
        $CourseCtrl = new CourseController();
        return $CourseCtrl->delete($args['id']);
    };

    /*** AWARD CLOSURES */
    $handelGetAward = function ($args) {
        $awardController = new \Course\Controllers\AwardController();
        $awards = $awardController->getAllAwards($args);
        return $awards;
    };

    $handelGetAwardByUserID = function ($args) {
        $awardController = new \Course\Controllers\AwardController();
        $awards = $awardController->getAwardByUserID($args);
        return $awards;
    };

    $handelGetAwardByCourseID = function ($args) {
        $awardController = new \Course\Controllers\AwardController();
        $awards = $awardController->getAwardByCourseID($args);
        return $awards;

    };

    $handlePostAward = function ($args) {
        $json = json_decode(file_get_contents('php://input'));
        $awardController = new \Course\Controllers\AwardController();
        $CourseId = $json->CourseId;
        $userId = $json->userId;
        $timeframeId = $json->timeframeId;
        $awardAmount = $json->awardAmount;
        $decisionDate = $json->decisionDate;
        $decision = $json->decision;
        $headers = apache_request_headers();
        $authorization = $headers['Authorization'];

        return $awardController->buildAward($CourseId, $userId, $timeframeId, $awardAmount,  $decisionDate,  $decision, $authorization);

    };

    $handlePutAward = function ($args) {
        $json = json_decode(file_get_contents('php://input'));
        $awardController = new \Course\Controllers\AwardController();
        $id = $json->id;
        $CourseId = $json->CourseId;
        $userId = $json->userId;
        $timeframeId = $json->timeframeId;
        $awardAmount = $json->awardAmount;
        $decisionDate = $json->decisionDate;
        $decision = $json->decision;
        $headers = apache_request_headers();
        $authorization = $headers['Authorization'];

        return $awardController->updateAward($id,$CourseId, $userId, $timeframeId, $awardAmount,  $decisionDate,  $decision, $authorization);
    };

    $handleGetAllStudentAwards = function ($args){
        $awardController = new Course\Controllers\AwardController();
        return $awardController->getAllOfAStudentsAwards($args['userId']);
    };

    $handleGetStudentAward = function ($args){
        $awardController = new Course\Controllers\AwardController();
        return $awardController->getSpecificAward($args['id']);
    };

    $handleUpdateDecision = function ($args){
        $awardController = new Course\Controllers\AwardController();
        $json = json_decode(file_get_contents('php://input'));
        $id = $args['id'];
        $decision = $json->decision;
        return $awardController->setAwardDecision($id, $decision);
    };

    $handleGetApplication = function($args) {
        $applicationController = new \Course\Controllers\ApplicationsController() ;
        return $applicationController->GET($args);
    };

    $handlePostApplication = function($args) {
        $applicationController = new \Course\Controllers\ApplicationsController() ;
        return $applicationController->Post($args);
    };

    $handlePutApplication = function($args) {
        $applicationController = new \Course\Controllers\ApplicationsController() ;
        return $applicationController->Put($args);
    };

    /** TIMEFRAME CLOSURES */
    $handlePostTimeFrame = function ($args){
        $timeFrameController = new \Course\Controllers\TimeFrameController();

        //Attempt to parse json input
        $timeFrameController = new \Course\Controllers\TimeFrameController();

        //Attempt to parse json input
        $json = (object) json_decode(file_get_contents('php://input') , true);
        if (is_null($json)) {
            http_response_code(\Course\Http\StatusCodes::BAD_REQUEST);
            exit();
        }

        return $timeFrameController->createDateObject($json);
    };
    $handleDeleteTimeFrame = function ($args)
    {
        //Attempt to parse json input
        $timeFrameController = new TimeFrameController();

        //Attempt to parse json input
        $json = (object) json_decode(file_get_contents('php://input'), true);
        if (is_null($json)) {
            http_response_code(\Course\Http\StatusCodes::BAD_REQUEST);
            exit();
        }

        return $timeFrameController->deleteTimeframeObject($json);
    };
    $handlePutTimeFrame = function ($args)
    {
        $timeFrameController = new \Course\Controllers\TimeFrameController();

        //Attempt to parse json input
        $json = (object) json_decode(file_get_contents('php://input'), true);
        if (is_null($json)) {
            http_response_code(\Course\Http\StatusCodes::BAD_REQUEST);
            exit();
        }

        return $timeFrameController->updateDateObject($json);
    };
    $handleGetTimeFrame = function($args)
    {
        $timeFrameController = new \Course\Controllers\TimeFrameController();

        //Attempt to parse json input
        $json = (object) json_decode(file_get_contents('php://input'), true);
        if (is_null($json)) {
            http_response_code(\Course\Http\StatusCodes::BAD_REQUEST);
            exit();
        }

        return $timeFrameController->getAllDateObjects($json);
    };
    $handleGetStartDate = function($args)
    {
        $timeFrameController = new \Course\Controllers\TimeFrameController();
        if(isset($args['id']))
        {
            return $timeFrameController->getStartDateObject($args['id']);
        }
    };
    $handleGetEndDate = function($args)
    {
        $timeFrameController = new \Course\Controllers\TimeFrameController();
        if(isset($args['id']))
        {
            return $timeFrameController->getEndDateObject($args['id']);
        }
    };
    $handleGetFullDate = function($args)
    {
        $timeFrameController = new \Course\Controllers\TimeFrameController();
        if(isset($args['id']))
        {
            return $timeFrameController->getDateObject($args['id']);
        }
    };
    $handleGetWithinDate = function($args)
    {
        $timeFrameController = new \Course\Controllers\TimeFrameController();
        if(isset($args['id']))
        {
            return $timeFrameController->getWithinDateObject($args['id']);
        }
    };
    /** TOKEN ROUTE */
    $r->addRoute(Methods::POST, $baseURI . '/tokens', $handlePostToken);

    /** RATINGS CLOSURES */
    // route for Course-rest-f17/v1/ratings
    $handleGetAllRating = function ($args){

        return (new Course\Controllers\RatingController)->getAllRating();
    };
    $r->addRoute(Methods::GET,$baseURI.'/ratings',$handleGetAllRating);

    // route for Course-rest-f17/v1/Course/CourseID/ratings/StudentID
    $handleGetSingleRating = function ($args){
        return (new Course\Controllers\RatingController)->getCompositeRating($args['sid'],$args['id']);
    };
    $r->addRoute(Methods::GET,$baseURI.'/Course/{sid:\d+}/ratings/{id:\d+}',$handleGetSingleRating);

    // route for Course-rest-f17/v1/ratings/faculty/FacultyID/student/StudentID
    //this route to get student past score
    $handleGetStudentRating = function ($args){
        $fid = Course\Controllers\RatingController::getFacultyID();
        return (new Course\Controllers\RatingController)->getStudentPastScore($fid,$args['sid']);
    };
    //$r->addRoute(Methods::GET,$baseURI.'/ratings/faculty/{fid:\d+}/student/{sid:\d+}',$handleGetStudentRating);

    $r->addRoute(Methods::GET,$baseURI.'/ratings/student/{sid:\d+}',$handleGetStudentRating);

    $handleUpdateStudentRating = function ($args){
        if (!empty($_POST['score'])) {
            $score = filter_var($_POST['score'], FILTER_SANITIZE_STRING);
        } else {

            $json = (object) json_decode(file_get_contents('php://input'));
            $score = $json->score;

        }
        $fid = Course\Controllers\RatingController::getFacultyID();
        return (new Course\Controllers\RatingController)->updateStudentRating($fid,$args['sid'],$score);
    };

    $r->addRoute(Methods::PUT,$baseURI.'/ratings/student/{sid:\d+}',$handleUpdateStudentRating);

    $r->addRoute(Methods::POST,$baseURI.'/ratings/student/{sid:\d+}',$handleUpdateStudentRating);
//    $r->addRoute(Methods::PUT,$baseURI.'/ratings/faculty/{fid:\d+}/student/{sid:\d+}',$handleUpdateStudentRating);
//
//    $r->addRoute(Methods::POST,$baseURI.'/ratings/faculty/{fid:\d+}/student/{sid:\d+}',$handleUpdateStudentRating);



    //application route
     $handleGetApplicationRating = function ($args){
         //return (new Course\Controllers\RatingController)->getApplicationScore($args['sid'], $args['fid'],$args['ssid'],$args['ratingid']);
         $fid = Course\Controllers\RatingController::getFacultyID();
        return (new Course\Controllers\RatingController)->getApplicationScore($args['sid'], $fid,$args['ssid'],$args['ratingid']);
    };
    //$r->addRoute(Methods::GET,$baseURI.'/ratings/faculty/{fid:\d+}/student/{sid:\d+}/Course/{ssid:\d+}/type/{ratingid:\d+}',$handleGetApplicationRating);
    $r->addRoute(Methods::GET,$baseURI.'/ratings/student/{sid:\d+}/Course/{ssid:\d+}/type/{ratingid:\d+}',$handleGetApplicationRating);



    $handleUpdateApplicationRating = function ($args) {
        if (!empty($_POST['score']) && !empty($_POST['ratingTypeId'])) {
            $score = filter_var($_POST['score'], FILTER_SANITIZE_STRING);
            $ratingType = filter_var($_POST['ratingTypeId'], FILTER_SANITIZE_STRING);

        } else {

            $json = (object) json_decode(file_get_contents('php://input'));
            $score = $json->score;
            $ratingType = $json->ratingTypeId;

        }
        $fid = Course\Controllers\RatingController::getFacultyID();
        return (new Course\Controllers\RatingController)->setApplicationScore($args['sid'], $fid,$args['ssid'],$ratingType,$score);

    };
//    $r->addRoute(Methods::PUT, $baseURI.'/ratings/faculty/{fid:\d+}/student/{sid:\d+}/Course/{ssid:\d+}',$handleUpdateApplicationRating);
//    $r->addRoute(Methods::POST, $baseURI.'/ratings/faculty/{fid:\d+}/student/{sid:\d+}/Course/{ssid:\d+}',$handleUpdateApplicationRating);
    $r->addRoute(Methods::PUT, $baseURI.'/ratings/student/{sid:\d+}/Course/{ssid:\d+}',$handleUpdateApplicationRating);
    $r->addRoute(Methods::POST, $baseURI.'/ratings/student/{sid:\d+}/Course/{ssid:\d+}',$handleUpdateApplicationRating);






    /** USER CLOSURES */
    $handleFullUpdateUser = function ($args) {
        return (new \Course\Controllers\UserController)->fullUpdateUser($args);
    };
    $handleDeleteUser = function ($args) {
        return (new \Course\Controllers\UserController)->deleteUser($args);
    };

    $handlePartialUpdateUser = function($args) {
        return (new \Course\Controllers\UserController)->partialUpdateUser($args);
    };

    $handleGetAllStudents = function(){
        return (new \Course\Controllers\UserController)->getAllStudents();
    };

    $handleGetAllFaculty = function() {
        return (new \Course\Controllers\UserController)->getAllFaculty();
    };

    $handleGetUser = function($args){
      return (new \Course\Controllers\UserController)->getUser($args);
    };

    $handleAddUser = function(){
        return (new \Course\Controllers\UserController)->addUser();
    };

    /** USER ROUTE */
    $r->addRoute(Methods::PUT, $baseURI.'/users/{id:\d+}', $handleFullUpdateUser);
    $r->addRoute(Methods::PATCH, $baseURI.'/users/{id:\d+}', $handlePartialUpdateUser);
    $r->addRoute(Methods::GET, $baseURI.'/users/students', $handleGetAllStudents);
    $r->addRoute(Methods::GET, $baseURI.'/users/faculties', $handleGetAllFaculty);
    $r->addRoute(Methods::GET,$baseURI.'/users/{id:\d+}', $handleGetUser);
    $r->addRoute(Methods::POST,$baseURI.'/users', $handleAddUser);
    $r->addRoute(Methods::DELETE, $baseURI.'/users/{id:\d+}', $handleDeleteUser);

    /** Course ROUTE */
    $r->addRoute(Methods::GET, $baseURI . '/Courses/{id:\d+}', $handleCourse);
    $r->addRoute(Methods::GET, $baseURI. '/Courses', $getAllCourses);

    $r->addRoute(Methods::POST, $baseURI . '/Courses', $createCourse);
    $r->addRoute(Methods::PATCH, $baseURI . '/Courses/{id:\d+}', $updateCourse);
    $r->addRoute(Methods::DELETE, $baseURI . '/Courses/{id:\d+}', $deleteCourse);

    $r->addRoute(Methods::POST, $baseURI . '/awards', $handlePostAward);
    $r->addRoute(Methods::PUT, $baseURI . '/awards', $handlePutAward);
    /** AWARD ROUTE */
    $r->addRoute(Methods::GET, $baseURI . '/awards', $handelGetAward);
    $r->addRoute(Methods::GET, $baseURI . '/awards/student/{id:\d+}/faculty', $handelGetAwardByUserID);
    $r->addRoute(Methods::GET, $baseURI . '/awards/Course/{id:\d+}', $handelGetAwardByCourseID);

    $r->addRoute(Methods::GET, $baseURI . '/awards/student/{userId:\d+}/', $handleGetAllStudentAwards);
    $r->addRoute(Methods::GET, $baseURI . '/awards/{id:\d+}/', $handleGetStudentAward);
    $r->addRoute(Methods::PUT, $baseURI . '/awards/{id:\d+}/decision/',$handleUpdateDecision);

    /** TIMEFRAME ROUTES */
    $r->addRoute(Methods::POST, $baseURI . '/timeframe', $handlePostTimeFrame);
    $r->addRoute( Methods::DELETE, $baseURI . '/timeframe', $handleDeleteTimeFrame);
    $r->addRoute(Methods::PUT, $baseURI . '/timeframe', $handlePutTimeFrame);
    $r->addRoute(Methods::GET, $baseURI . '/timeframe', $handleGetTimeFrame);
    $r->addRoute(Methods::GET, $baseURI . '/timeframe/{id:\d+}', $handleGetFullDate);
    $r->addRoute(Methods::GET, $baseURI . '/timeframe/{id:\d+}/start', $handleGetStartDate);
    $r->addRoute(Methods::GET, $baseURI . '/timeframe/{id:\d+}/end', $handleGetEndDate);
    $r->addRoute(Methods::GET, $baseURI . '/timeframe/{id:\d+}/check', $handleGetWithinDate);

    $r->addRoute(Methods::GET, $baseURI . '/applications/{id:\d+}', $handleGetApplication);
    $r->addRoute(Methods::GET, $baseURI . '/applications', $handleGetApplication);
    $r->addRoute(Methods::POST, $baseURI . '/applications', $handlePostApplication);
    $r->addRoute(Methods::PUT, $baseURI . '/applications', $handlePutApplication);
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$pos = strpos($uri, '?');
if ($pos !== false) {
    $uri = substr($uri, 0, $pos);
}
$uri = rtrim($uri, "/");

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($method, $uri);

switch($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(Course\Http\StatusCodes::NOT_FOUND);
        //Handle 404
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(Course\Http\StatusCodes::METHOD_NOT_ALLOWED);
        //Handle 403
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler  = $routeInfo[1];
        $vars = $routeInfo[2];

        $response = $handler($vars);
        echo json_encode($response);
        break;
}





