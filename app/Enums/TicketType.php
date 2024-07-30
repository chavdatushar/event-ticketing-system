<?php
namespace App\Enums;

enum TicketType: string
{
    case EarlyBird = 'early_bird';
    case Regular = 'regular';
    case VIP = 'vip';

    public function label(): string
    {
        return match ($this) {
            TicketType::EarlyBird => 'Early Bird',
            TicketType::Regular => 'Regular',
            TicketType::VIP => 'VIP',
        };
    }
}
