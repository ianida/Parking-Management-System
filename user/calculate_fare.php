<?php

/**
 * Calculate total parking fare based on start/end time and vehicle type
 * @param string $startTime - booking start time (Y-m-d H:i:s)
 * @param string $endTime   - booking end time (Y-m-d H:i:s)
 * @param string $vehicleType - type of vehicle (from tblcategory)
 * @param float $discountPercent - optional discount percentage
 * @return float - total fare (always positive)
 */
function calculateParkingFare($startTime, $endTime, $vehicleType, $discountPercent = 0) {
    // Map tblcategory names to fare keys
    $categoryMap = [
        'Motorbike' => 'bike',
        'Car'       => 'car',
        'Truck'     => 'truck',
        'Bicycle'   => 'bicycle'
    ];

    // Default to 'car' if unknown
    $vehicleType = $categoryMap[$vehicleType] ?? 'car';

    // Vehicle-specific base + hourly rates
    $vehicleRates = [
        'bike'    => ['base' => 20, 'hourly' => 10],
        'car'     => ['base' => 50, 'hourly' => 30],
        'jeep'    => ['base' => 60, 'hourly' => 35],
        'truck'   => ['base' => 80, 'hourly' => 50],
        'bicycle' => ['base' => 5, 'hourly' => 2]
    ];

    $surgeHours = [8, 9, 17, 18];    // Rush hours
    $surgeMultiplier = 1.5;          // 50% extra
    $minimumChargeHours = 1;          // Minimum 1 hour

    $start = strtotime($startTime);
    $end = strtotime($endTime);

    // Total hours (minimum 1)
    $totalHours = ceil(($end - $start) / 3600);
    if ($totalHours < $minimumChargeHours) {
        $totalHours = $minimumChargeHours;
    }

    // Check for surge pricing
    $surgeApplied = false;
    for ($t = $start; $t < $end; $t += 3600) {
        $hour = (int)date('G', $t);
        if (in_array($hour, $surgeHours)) {
            $surgeApplied = true;
            break;
        }
    }

    $baseCharge = $vehicleRates[$vehicleType]['base'];
    $hourlyRate = $vehicleRates[$vehicleType]['hourly'];

    $fare = $baseCharge + ($totalHours * $hourlyRate);

    if ($surgeApplied) {
        $fare *= $surgeMultiplier;
    }

    // Apply discount if any
    if ($discountPercent > 0) {
        $fare -= ($fare * ($discountPercent / 100));
    }

    // Ensure fare is never negative
    if ($fare < 0) $fare = 0;

    return round($fare, 2);
}

/**
 * Calculate commission (admin share) from total fare
 * @param float $totalFare
 * @return float - commission (positive)
 */
function calculateComission($totalFare){
    $commission = round($totalFare * 0.15, 2);
    return $commission;  // now positive
}

?>
