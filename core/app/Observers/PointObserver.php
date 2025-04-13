<?php

namespace App\Observers;

use App\Models\Point;
use App\Models\User;

class PointObserver
{
    public function saved(Point $point)
    {
        $this->updateUserPoints($point);
    }

    public function deleted(Point $point)
    {
        $this->updateUserPoints($point);
    }

    private function updateUserPoints(Point $point)
    {
        $user = $point->user;

        if ($user) {
            // Calculate total points
            $totalDebit = $user->points()->sum('debit');
            $totalCredit = $user->points()->sum('credit');

            // Update the user's points
            $user->points = $totalDebit - $totalCredit;
            $user->save();
        }
    }
}
