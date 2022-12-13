<?php

namespace UF1\Models;

use UF1\Enums\ActivityPaymentMethod;
use UF1\Enums\ActivityType;
use UF1\Config\Database;

/**
 * Class Activity model of database table activity
 * @package UF1\Models
 */
class Activity
{
    private int $id;
    private int $user_id;
    private string $title;
    private string $date;
    private string $city;
    private ActivityType $type;
    private ActivityPaymentMethod $payment_method;
    private string $description;
    private string $created_at;
    private string $updated_at;

    private $db;

    /**
     * Activity constructor. se conecta a la base de datos
     */
    public function __construct() {
        $this->db = Database::Connect();
    }

    /**
     * Funciona como constructor interno de la clase, lo hacemos de esta manera para que
     * en el controlador tengamos que usar los setters para crear una actividad
     */
    public function builder($rs): Activity{
        foreach ($rs as $key => $value) {
            if(ActivityType::tryFrom($value)){
                $this->$key = ActivityType::tryFrom($value);
            } else if(ActivityPaymentMethod::tryFrom($value)){
                $this->$key = ActivityPaymentMethod::tryFrom($value);
            } else {
                $this->$key = $value;
            }
        }
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $this->db->real_escape_string($title);
    }

    public function getDate(): string
    {
        $date = strtotime($this->date);
        return date('Y-m-d', $date);
    }

    public function setDate(string $date): void
    {
        $this->date = $this->db->real_escape_string($date);
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $this->db->real_escape_string($city);
    }

    /**
     * @return ActivityType type
     */
    public function getType(): string
    {
        return $this->type->value;
    }

    /**
     * @param ActivityType $type
     */
    public function setType(ActivityType $type): void
    {
        $this->type = $type;
    }

    /**
     * @return ActivityPaymentMethod paymentMethod
     */
    public function getPaymentMethod(): string
    {
        return $this->payment_method->value;
    }

    public function setPaymentMethod(ActivityPaymentMethod $paymentMethod): void
    {
        $this->payment_method = $paymentMethod;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $this->db->real_escape_string($description);
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $date): void
    {
        $this->created_at = $date;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * Método para traer una actividad por su id
     */
    public function getActivityById(string|int $id): Activity|null {
        $stmt = $this->db->prepare('SELECT * FROM activities WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $rs = $stmt->get_result();
        return $rs && $rs->num_rows == 1
            ? $this->builder($rs->fetch_object())
            : null;
    }

    /**
     * Metodo que devuelve las actividades de un usuario
     * @return array
     */
    public function getActivitiesByUserId(int $id): Array|null {
        $stmt = $this->db->prepare('SELECT * FROM activities WHERE user_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $activities = [];
        if($rs = $stmt->get_result()){
            while ($activity = $rs->fetch_object()){
                $activities[] = (new Activity())->builder($activity);
            }
        }
        return $activities;
    }

    /**
     * Metodo para guardar una actividad ligado a un usuario en la base de datos
     */
    public function save(): Activity|bool
    {
        try {
            $type = $this->getType();
            $payment_method = $this->getPaymentMethod();
            $stmt = $this->db->prepare("INSERT INTO activities VALUES(NULL,?,?,?,?,?,?,?,NOW(),NOW())");
            $stmt->bind_param('issssss', $this->user_id, $this->title, $this->city, $type, $payment_method, $this->description, $this->date);
            $stmt->execute();
            $lasActivityId = $this->db->insert_id;
            return $this->getActivityById($lasActivityId);
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * Método para actualizar una actividad
     */
    public function update(string|int $id = null): Activity|bool
    {
        $id = $id ?? $this->id;
        try {
            $type = $this->getType();
            $payment_method = $this->getPaymentMethod();
            $stmt = $this->db->prepare("UPDATE activities SET title = ?, city = ?, type = ?, payment_method = ?, description = ?, date = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param('sssssss', $this->title, $this->city, $type, $payment_method, $this->description, $this->date, $id);
            $stmt->execute();
            return $this->getActivityById($id);
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * Método para eliminar una actividad
     */
    public function delete(string|int $id = null): bool
    {
        $id = $id ?? $this->id;
        try {
            $stmt = $this->db->prepare('DELETE FROM activities WHERE id = ?');
            $stmt->bind_param('s', $id);
            return $stmt->execute();
        }
        catch (Exception $e) {
            return false;
        }
    }
}