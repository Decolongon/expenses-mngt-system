<?php

namespace App\Enums;

use App\Concerns\HasEnumOptions;

enum CategoryIconEnum: string
{
    use HasEnumOptions;
    
    case ShoppingCart = 'ShoppingCart';
    case ShoppingBag = 'ShoppingBag';
    case ShoppingBasket = 'ShoppingBasket';
    case Utensils = 'Utensils';
    case Coffee = 'Coffee';
    case Pizza = 'Pizza';
    case Cake = 'Cake';
    case Car = 'Car';
    case Fuel = 'Fuel';
    case Bus = 'Bus';
    case TrainFront = 'TrainFront';
    case Bike = 'Bike';
    case Plane = 'Plane';
    case House = 'House';
    case Building2 = 'Building2';
    case Zap = 'Zap';
    case Wifi = 'Wifi';
    case Smartphone = 'Smartphone';
    case Film = 'Film';
    case Music = 'Music';
    case Gamepad2 = 'Gamepad2';
    case BookOpen = 'BookOpen';
    case GraduationCap = 'GraduationCap';
    case HeartPulse = 'HeartPulse';
    case Stethoscope = 'Stethoscope';
    case Dumbbell = 'Dumbbell';
    case Shirt = 'Shirt';
    case Baby = 'Baby';
    case PawPrint = 'PawPrint';
    case Package = 'Package';
    case Gift = 'Gift';
    case CreditCard = 'CreditCard';
    case Landmark = 'Landmark';
    case TrendingUp = 'TrendingUp';
    case PiggyBank = 'PiggyBank';
    case Wallet = 'Wallet';
    case Banknote = 'Banknote';
    case Receipt = 'Receipt';
    case CircleDollarSign = 'CircleDollarSign';
    case Droplets = 'Droplets';
    case Flame = 'Flame';
    case Sun = 'Sun';
    case CloudRain = 'CloudRain';
    case Leaf = 'Leaf';
    case TreePine = 'TreePine';
    case Tent = 'Tent';
    case TreePalm = 'TreePalm';
    case Camera = 'Camera';
    case Brush = 'Brush';
    case Wrench = 'Wrench';
}
