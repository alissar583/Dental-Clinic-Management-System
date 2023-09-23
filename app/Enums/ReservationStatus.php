<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ReservationStatus extends Enum
{
    const Pending = 1;
    const Confirmed = 2;
    const Cancelled = 3;
}
