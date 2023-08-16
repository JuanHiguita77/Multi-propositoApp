<?php   

namespace App\Enums;

//Se definen los roles y sus enumeraciones

enum RoleType: int
{
    case ADMIN = 1;
    case USER = 2;
}
