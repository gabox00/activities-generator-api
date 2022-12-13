<?php

namespace UF1\Controllers;

use stdClass;
use UF1\Enums\ActivityPaymentMethod;
use UF1\Enums\ActivityType;
use UF1\Models\Activity;

/**
 * Class ActivityController
 * @package UF1\Controllers
 */
class ActivityController
{

    /**
     * @Method GET
     * Metodo que devuelve todas las actividades
     */
    public function index(): array{
        $user_id = $_GET['user_id'] ?? null;
        //intentar convertir el id a int y si no se puede, devolver error
        $user_id = intval($user_id);
        if($user_id == 0){
            http_response_code(400);
            return [
                'ok' => false,
                'error' => 'El id del usuario no es valido'
            ];
        }

        $activity = new Activity();
        $activities = $activity->getActivitiesByUserId($user_id);
        $activitiesStdClass = [];
        foreach ($activities as $activity){
            $activitiesStdClass[] = new stdClass();
            $position = count($activitiesStdClass) - 1;
            $activitiesStdClass[$position]->id = $activity->getId();
            $activitiesStdClass[$position]->title = $activity->getTitle();
            $activitiesStdClass[$position]->date = $activity->getDate();
            $activitiesStdClass[$position]->city = $activity->getCity();
            $activitiesStdClass[$position]->type = $activity->getType();
            $activitiesStdClass[$position]->paymentMethod = $activity->getPaymentMethod();
            $activitiesStdClass[$position]->description = $activity->getDescription();
        }
        return $activitiesStdClass;
    }

    /**
     * @Method POST
     * Metodo que crea una actividad, recoge los datos del formulario POST
     * los valida y crea la actividad
     * Tambien se encarga de guardar el error y guardar la actividad en la sesion del usuario
     */
    public function create(): stdClass|array
    {
        $json = file_get_contents('php://input');
        $request = json_decode($json);

        $user_id = $request?->user_id;
        $title = $request?->title;
        $date = $request?->date;
        $city = $request?->city;
        $type = ActivityType::tryFrom($request?->type);
        $paymentMethod = ActivityPaymentMethod::tryFrom($request?->paymentMethod);
        $description = $request?->description;

        $errorMsg = 'Error al crear la actividad';
        $error = [
            'ok' => false,
            'error' => $errorMsg
        ];

        if(empty($user_id) || empty($title) || empty($date) || empty($city) || empty($type) || empty($paymentMethod) || empty($description)) {
            http_response_code(400);
            return $error;
        }

        $activity = new Activity();
        $activity->setUserId($user_id);
        $activity->setTitle($title);
        $activity->setDate($date);
        $activity->setCity($city);
        $activity->setType($type);
        $activity->setPaymentMethod($paymentMethod);
        $activity->setDescription($description);
        $activity = $activity->save();

        if($activity) {
            $newActivity = new stdClass();
            $newActivity->id = $activity->getId();
            $newActivity->title = $activity->getTitle();
            $newActivity->date = $activity->getDate();
            $newActivity->city = $activity->getCity();
            $newActivity->type = $activity->getType();
            $newActivity->paymentMethod = $activity->getPaymentMethod();
            $newActivity->description = $activity->getDescription();

            http_response_code(201);
            return $newActivity;
        }

        http_response_code(400);
        return $error;
    }

    /**
     * @Method DELETE
     * Metodo que borra una actividad, recoge el id de la actividad por GET y la borra
     * Tambien se encarga de guardar el error y eliminar la actividad de la sesion del usuario
     */
    public function delete(): bool|array
    {
        $errorMsg = 'Error al eliminar la actividad';
        $error = [
            'ok' => false,
            'error' => $errorMsg
        ];

        $id = $_GET['id'] ?? null;
        if(!$id){
            http_response_code(400);
            return $error;
        }

        $activityModel = new Activity();
        $activity = $activityModel->getActivityById($id);
        if($activity && $activity->delete()){
            return true;
        }

        http_response_code(400);
        return $error;
    }

    /**
     * @Method PUT
     * Metodo que edita una actividad, recoge los datos del formulario POST
     * los valida y edita la actividad
     * Tambien se encarga de guardar el error y editar la actividad en la sesion del usuario
     */
    public function update(): stdClass|array
    {
        $errorMsg = 'Error al editar la actividad';
        $error = [
            'ok' => false,
            'error' => $errorMsg
        ];

        $id = $_GET['id'] ?? null;
        if(!$id){
            http_response_code(400);
            $error['error'] = 'Error al editar la actividad, el id no es valido';
            return $error;
        }

        $json = file_get_contents('php://input');
        $request = json_decode($json);

        $title = $request?->title;
        $date = $request?->date;
        $city = $request?->city;
        $type = ActivityType::tryFrom($request?->type);
        $paymentMethod = ActivityPaymentMethod::tryFrom($request?->paymentMethod);
        $description = $request?->description;

        if(empty($id) || empty($title) || empty($date) || empty($city) || empty($type) || empty($paymentMethod) || empty($description)) {
            http_response_code(400);
            $error['error'] = 'Error al editar la actividad, algun campo está vacío';
            return $error;
        }

        $activity = new Activity();
        $activity = $activity->getActivityById($id);
        $activity->setTitle($title);
        $activity->setDate($date);
        $activity->setCity($city);
        $activity->setType($type);
        $activity->setPaymentMethod($paymentMethod);
        $activity->setDescription($description);

        if($activity = $activity->update()) {
            $updatedActivity = new stdClass();
            $updatedActivity->id = $activity->getId();
            $updatedActivity->title = $activity->getTitle();
            $updatedActivity->date = $activity->getDate();
            $updatedActivity->city = $activity->getCity();
            $updatedActivity->type = $activity->getType();
            $updatedActivity->paymentMethod = $activity->getPaymentMethod();
            $updatedActivity->description = $activity->getDescription();
            return $updatedActivity;
        }

        http_response_code(400);
        return $error;
    }
}
