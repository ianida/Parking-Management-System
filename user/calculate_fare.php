<?php

function calculateParkingFare($startTime, $endTime, $vehicleType, $discountPercent = 0) {
    // Map tblcategory names to fare keys
    $categoryMap = [
        'Motorbike' => 'bike',
        'Car'       => 'car',
        'Truck'     => 'truck',
        'Bicycle'   => 'bicycle'
    ];

    // Use mapped type, default to 'car' if unknown
    $vehicleType = $categoryMap[$vehicleType] ?? 'car';

    // Vehicle-specific base charge (flat fee) + hourly rate
    $vehicleRates = [
        'bike'    => ['base' => 20, 'hourly' => 10],
        'car'     => ['base' => 50, 'hourly' => 30],
        'jeep'    => ['base' => 60, 'hourly' => 35],
        'truck'   => ['base' => 80, 'hourly' => 50],
        'bicycle' => ['base' => 5, 'hourly' => 2]
    ];

    $surgeHours = [8, 9, 17, 18]; // Morning and evening rush hours
    $surgeMultiplier = 1.5; // 50% extra during peak
    $minimumChargeHours = 1; // Minimum 1 hour

    $start = strtotime($startTime);
    $end = strtotime($endTime);

    // Total parked hours (minimum 1)
    $totalHours = ceil(($end - $start) / 3600);
    if ($totalHours < $minimumChargeHours) {
        $totalHours = $minimumChargeHours;
    }

    // Detect if surge pricing applies
    $surgeApplied = false;
    for ($t = $start; $t < $end; $t += 3600) {
        $hour = (int)date('G', $t);
        if (in_array($hour, $surgeHours)) {
            $surgeApplied = true;
            break;
        }
    }

    // Get vehicle-specific rates
    $baseCharge = $vehicleRates[$vehicleType]['base'];
    $hourlyRate = $vehicleRates[$vehicleType]['hourly'];

    // Calculate fare
    $fare = $baseCharge + ($totalHours * $hourlyRate);

    // Apply surge pricing
    if ($surgeApplied) {
        $fare *= $surgeMultiplier;
    }

    // Apply discount if any
    if ($discountPercent > 0) {
        $fare -= ($fare * ($discountPercent / 100));
    }

    // Ensure fare is not negative
    if ($fare < 0) {
        $fare = 0;
    }

    return round($fare, 2);
}

function calculateComission($totalFare){
    $comission = round($totalFare * 0.15, 2);
    return ($comission * -1);
}

?>
