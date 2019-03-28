<?php

return [

    /**
     * AuthController.
     */
    'auth' => [
        'login_error' => 'Дані невірні',
        'login_success' => 'Ви увійшли в систему',
        'token_invalid' => 'Токен не дійсний',
        'token_refresh' => 'Токен оновлено',
        'logout' => 'Ви вийшли з системи',
    ],

    /*
     * EquipmentController.
     */
    'equipments' => [
        'show' => 'Обладнання отримано',
        'store' => 'Обладнання створено',
        'update' => 'Обладнання оновлено',
        'destroy' => 'Обладнання видалено',
    ],

    'equipment_manufacturers' => [
        'show' => 'Виробник обладнання отриман',
        'store' => 'Виробник обладнання створений',
        'update' => 'Виробник обладнання оновлений',
        'destroy' => 'Виробник обладнання видалено',
    ],

    /**
     * Database operations.
     */
    'database' => [
        'save_error' => 'Виникла помилка при збереженні',
        'destroy_error' => 'Виникла помилка при видаленні',
    ],

];
