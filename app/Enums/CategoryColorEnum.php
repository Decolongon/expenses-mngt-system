<?php

namespace App\Enums;

use App\Concerns\HasEnumOptions;

enum CategoryColorEnum: string
{
    use HasEnumOptions;
    
    case Red = '#FF0000';
    case Tomato = '#FF6347';
    case Orange = '#FFA500';
    case Amber = '#FFBF00';
    case Gold = '#FFD700';
    case Yellow = '#FFFF00';
    case Lime = '#00FF00';
    case Green = '#008000';
    case SpringGreen = '#00FF7F';
    case Emerald = '#50C878';
    case Mint = '#98FF98';
    case Teal = '#008080';
    case Cyan = '#00FFFF';
    case SkyBlue = '#87CEEB';
    case Blue = '#0000FF';
    case RoyalBlue = '#4169E1';
    case Navy = '#000080';
    case Indigo = '#4B0082';
    case Purple = '#800080';
    case Violet = '#EE82EE';
    case Magenta = '#FF00FF';
    case Pink = '#FFC0CB';
    case HotPink = '#FF69B4';
    case Coral = '#FF7F50';
    case Salmon = '#FA8072';
    case Maroon = '#800000';
    case Brown = '#A52A2A';
    case Chocolate = '#D2691E';
    case SaddleBrown = '#8B4513';
    case Beige = '#F5F5DC';
    case Cream = '#FFFDD0';
    case White = '#FFFFFF';
    case Snow = '#FFFAFA';
    case Gray = '#808080';
    case Silver = '#C0C0C0';
    case Black = '#000000';
    case Charcoal = '#36454F';
    case SlateGray = '#708090';
    case Lavender = '#E6E6FA';
    case Plum = '#DDA0DD';
    case Orchid = '#DA70D6';
    case Turquoise = '#40E0D0';
    case Aquamarine = '#7FFFD4';
    case Khaki = '#F0E68C';
    case Olive = '#808000';
    case ForestGreen = '#228B22';
    case SeaGreen = '#2E8B57';
    case MidnightBlue = '#191970';
    case SteelBlue = '#4682B4';
    case DodgerBlue = '#1E90FF';
}
