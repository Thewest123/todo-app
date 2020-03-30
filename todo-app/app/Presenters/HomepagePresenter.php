<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Database\Context;
use Nette\Application\Responses\JsonResponse;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(Context $database)
    {
        $this->database = $database;
    }

    /**
     * URL: http://localhost/sandbox/www/homepage/get-users
     * Endpointa vrátí všechny uživatele
     * @return array uživatelé
     */
    public function actionGetUsers()
    {
        // Získá všechny uživatele
        $users = array_map(function ($item) {
            return [
                "id" => $item["id"],
                "firstName" => $item["first_name"],
                "lastName" => $item["last_name"]
            ];
        }, array_values($this->database->table("users")->fetchAll()));

        // Vrátí odpověď
        $this->sendResponse(new JsonResponse($users));
    }
    // http://localhost/sandbox/www/homepage/save-user/?firstName=Anca&lastName=Voborilova
    public function actionSaveUser(string $firstName, string $lastName)
    {
        // Vloží data
        $this->database->table("users")->insert([
            "first_name" => $firstName,
            "last_name" => $lastName
        ]);

        // Vrátí odpověď
        $this->sendResponse(new JsonResponse("ok"));
    }
    // http://localhost/sandbox/www/homepage/delete-user/?id=5
    public function actionDeleteUser($id)
    {
        $this->database->table("users")->where("id", $id)->delete();

        // Vrátí odpověď
        $this->sendResponse(new JsonResponse("ok"));
    }

    // http://localhost/sandbox/www/homepage/update-user/?id=1&firstName=Marek&lastName=Kejda
    public function actionUpdateUser($id, $firstName, $lastName)
    {
        $this->database->table("users")->get($id)->update([
            "first_name" => $firstName,
            "last_name" => $lastName
        ]);

        // Vrátí odpověď
        $this->sendResponse(new JsonResponse([
            "status" => "ok",
            "message" => "Všechno proběhlo v pořádku"
        ]));
    }
}
