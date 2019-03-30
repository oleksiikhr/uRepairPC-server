<?php

return [

    'middleware' => [
        'no_permission' => 'Немає прав',
    ],

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
     * UserController.
     */
    'users' => [
        'show' => 'Користувач отриман',
        'store' => 'Користувач створений',
        'update' => 'Користувач оновлений',
        'destroy' => 'Користувач видалений',
        'self_destroy_error' => 'Неможливо видалити самого себе',
        'email_changed' => 'E-mail змінений',
        'password_changed' => 'Пароль змінений',
        'password_email_changed' => 'Пароль змінений та відправлений на почту',
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

    /*
     * EquipmentManufacturerController.
     */
    'equipment_manufacturers' => [
        'show' => 'Виробник обладнання отриманий',
        'store' => 'Виробник обладнання створений',
        'update' => 'Виробник обладнання оновлений',
        'destroy' => 'Виробник обладнання видалено',
    ],

    /*
     * EquipmentModelController.
     */
    'equipment_model' => [
        'show' => 'Модель обладнання отримано',
        'store' => 'Модель обладнання створено',
        'update' => 'Модель обладнання оновлено',
        'destroy' => 'Модель обладнання видалено',
    ],

    /*
     * EquipmentTypeController.
     */
    'equipment_type' => [
        'show' => 'Тип обладнання отримано',
        'store' => 'Тип обладнання створено',
        'update' => 'Тип обладнання оновлено',
        'destroy' => 'Тип обладнання видалено',
    ],

    /*
     * Working with files.
     */
    'files' => [
        'upload_success' => 'Файли збережені',
        'upload_error' => 'З\'явилася помилка при завантаженні деяких файлів',
    ],

    /*
     * Database operations.
     */
    'database' => [
        'save_error' => 'Виникла помилка при збереженні',
        'destroy_error' => 'Виникла помилка при видаленні',
    ],

];
