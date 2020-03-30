<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Database\Context;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\Request;
use Nette\Utils\DateTime;
use Nette\Utils\Json;

final class TodoPresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    /*
     * Default presenter
     * URL: http://localhost/todo-app/www/todo
     */
    public function renderDefault()
    {
        // Získá všechny uživatele
        $todos = array_map(function ($item) {
            return [
                "id" => $item["id"],
                "name" => $item["name"],
                "desc" => $item["desc"],
                "is_completed" => $item["is_completed"],
                "deadline" => $item["deadline"]
            ];
        }, array_values($this->database->table("todos")->fetchAll()));

        $this->template->todos = $todos;
    }

    /*
     * URL: http://localhost/todo-app/www/todo/get-todos
     * Endpointa vrátí všechny todos
     */
    public function actionGetTodos()
    {
        // Získá všechny uživatele
        $todos = array_map(function ($item) {
            return [
                "id" => $item["id"],
                "name" => $item["name"],
                "desc" => $item["desc"],
                "is_completed" => $item["is_completed"],
                "deadline" => $item["deadline"]
            ];
        }, array_values($this->database->table("todos")->fetchAll()));

        // Vrátí odpověď
        $this->sendResponse(new JsonResponse($todos));
    }
    // URL: http://localhost/todo-app/www/todo/save-todo
    public function actionSaveTodo()
    {
        $data = Json::decode($this->getHttpRequest()->getRawBody());

        // Nastavení defaultních hodnot, pokud data z requestu neobsahují některé hodnoty
        $name = isset($data->name) ? $data->name : "";
        $desc = isset($data->desc) ? $data->desc : "";
        $deadline = isset($data->deadline) ? $data->deadline : null;

        // Pokud jsme obdrželi data o deadline, převedení z Unix formátu na DateTime
        if (!is_null($deadline)) {
            $deadline = DateTime::from($deadline);
        }

        // Vložení dat do databáze
        $this->database->table("todos")->insert([
            "name" => $name,
            "desc" => $desc,
            "deadline" => $deadline
        ]);

        // Vrácení odpovědi
        $this->sendResponse(new JsonResponse((['status' => 'ok'])));
    }

    // URL: http://localhost/todo-app/www/todo/delete-todo/0
    public function actionDeleteTodo(int $id)
    {
        $this->database->table("todos")->where("id", $id)->delete();

        // Vrácení odpovědi
        $this->sendResponse(new JsonResponse([
            "status" => "ok",
            "message" => "Deleted $id"
        ]));
    }

    // URL: http://localhost/todo-app/www/todo/update-todo/0
    public function actionUpdateTodo(int $id)
    {
        $data = Json::decode($this->getHttpRequest()->getRawBody());

        // Nastavení defaultních hodnot, pokud data z requestu neobsahují některé hodnoty
        $name = isset($data->name) ? $data->name : "";
        $desc = isset($data->desc) ? $data->desc : "";
        $is_completed = isset($data->is_completed) ? $data->is_completed : 0;
        $deadline = isset($data->deadline) ? $data->deadline : null;

        // Pokud jsme obdrželi data o deadline, převedení z Unix formátu na DateTime
        if (!is_null($deadline)) {
            $deadline = DateTime::from($deadline);
        }

        // Vložení hodnot do databáze
        $this->database->table("todos")->get($id)->update([
            "name" => $name,
            "desc" => $desc,
            "is_completed" => $is_completed,
            "deadline" => $deadline
        ]);

        // Vrácení odpovědi
        $this->sendResponse(new JsonResponse([
            "status" => "ok",
            "message" => "Updated $id"
        ]));
    }
}
