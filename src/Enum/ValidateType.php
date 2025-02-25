<?php

namespace QurbanAli\TalentScopeApi\Enum;

enum ValidateType: string
{
    case EMAIL = 'email';
    case TEXT = 'text';
    case PASSWORD = 'password';
    case ARRAY = 'array';
    case URL = 'url';
    case NOT_EMPTY = 'not_empty';
}