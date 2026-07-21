<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Tâches planifiées — notifications automatiques
|--------------------------------------------------------------------------
| Nécessite un cron serveur qui appelle `php artisan schedule:run` chaque
| minute (crontab : * * * * * cd /chemin/du/projet && php artisan schedule:run
| >> /dev/null 2>&1).
*/

// Rappel 3 jours avant l'échéance d'un emprunt
Schedule::command('notifications:due-reminders')->dailyAt('08:00');

// Passage en retard des emprunts non rendus + notification
Schedule::command('loans:mark-overdue')->dailyAt('00:05');

// Expiration des réservations non retirées dans le délai imparti
Schedule::command('reservations:expire')->hourly();
