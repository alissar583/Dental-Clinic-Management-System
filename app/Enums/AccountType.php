<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AccountType extends Enum
{
    const Admin = 1;
    const Doctor = 2;
    const Secretarial = 3;
    const Patient = 4;
}
