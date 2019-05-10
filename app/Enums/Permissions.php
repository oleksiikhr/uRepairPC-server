<?php

namespace App\Enums;

abstract class Permissions
{
    /*
     * Users
     */
    const USERS_VIEW = 'users.view';
    const USERS_EDIT = 'users.edit';
    const USERS_CREATE = 'users.create';
    const USERS_DELETE = 'users.delete';

    /*
     * Profile
     */
    const PROFILE_EDIT = 'profile.edit';

    /*
     * Roles
     */
    const ROLES_VIEW = 'roles.view';
    const ROLES_MANAGE = 'roles.manage';

    /*
     * Equipments
     */
    const EQUIPMENTS_VIEW = 'equipments.view';
    const EQUIPMENTS_EDIT = 'equipments.edit';
    const EQUIPMENTS_CREATE = 'equipments.create';
    const EQUIPMENTS_DELETE = 'equipments.delete';

    /*
     * Equipments
     */
    const EQUIPMENTS_CONFIG_VIEW = 'equipments.config.view';
    const EQUIPMENTS_CONFIG_EDIT = 'equipments.config.edit';
    const EQUIPMENTS_CONFIG_CREATE = 'equipments.config.create';
    const EQUIPMENTS_CONFIG_DELETE = 'equipments.config.delete';

    /*
     * Equipment Files
     */
    const EQUIPMENTS_FILES_VIEW = 'equipments.files.view';
    const EQUIPMENTS_FILES_DOWNLOAD = 'equipments.files.download';
    const EQUIPMENTS_FILES_EDIT = 'equipments.files.edit';
    const EQUIPMENTS_FILES_CREATE = 'equipments.files.create';
    const EQUIPMENTS_FILES_DELETE = 'equipments.files.delete';

    /*
     * Requests
     */
    const REQUESTS_VIEW = 'requests.view';
    const REQUESTS_EDIT = 'requests.edit';
    const REQUESTS_CREATE = 'requests.create';
    const REQUESTS_DELETE = 'requests.delete';

    /*
     * Requests Files
     */
    const REQUESTS_FILES_VIEW = 'requests.files.view';
    const REQUESTS_FILES_DOWNLOAD = 'requests.files.download';
    const REQUESTS_FILES_EDIT = 'requests.files.edit';
    const REQUESTS_FILES_CREATE = 'requests.files.create';
    const REQUESTS_FILES_DELETE = 'requests.files.delete';

    /*
     * Requests Config
     */
    const REQUESTS_CONFIG_VIEW = 'requests.config.view';
    const REQUESTS_CONFIG_EDIT = 'requests.config.edit';
    const REQUESTS_CONFIG_CREATE = 'requests.config.create';
    const REQUESTS_CONFIG_DELETE = 'requests.config.delete';

    /*
     * Other
     */
    const GLOBAL_SETTINGS = 'global.settings';
    const GLOBAL_MANIFEST = 'global.manifest';

    // Permission not created - blocks all requests
    const DISABLE = 'disable';
}
