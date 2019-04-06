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
     * Groups
     */
    const GROUPS_VIEW = 'groups.view';
    const GROUPS_MANAGE = 'groups.manage';

    /*
     * Equipments
     */
    const EQUIPMENTS_VIEW = 'equipments.view';
    const EQUIPMENTS_EDIT = 'equipments.edit';
    const EQUIPMENTS_CREATE = 'equipments.create';
    const EQUIPMENTS_DELETE = 'equipments.delete';

    /*
     * Equipment Files
     */
    const EQUIPMENTS_FILES_VIEW = 'equipments.files.view';
    const EQUIPMENTS_FILES_DOWNLOAD = 'equipments.files.download';
    const EQUIPMENTS_FILES_EDIT = 'equipments.files.edit';
    const EQUIPMENTS_FILES_CREATE = 'equipments.files.create';
    const EQUIPMENTS_FILES_DELETE = 'equipments.files.delete';

    /*
     * Other
     */
    const OTHER_GLOBAL_SETTINGS = 'other.global_settings';
}
