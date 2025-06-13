<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Notification;

class NotificationController extends Controller
{
    public static function createForAdmins($organizationId, $content)
    {
        $colors = ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc'];
        $randomColor = $colors[array_rand($colors)];

        $adminUsers = \DB::table('user_organizations')
            ->where('OrganizationID', $organizationId)
            ->where('IsAdmin', 1)
            ->pluck('UserID');

        foreach ($adminUsers as $adminId) {
            Notification::create([
                'NotificationID' => Str::uuid(),
                'TargetType'     => 'user',
                'TargetID'       => $adminId,
                'color'          => $randomColor,
                'Content'        => $content,
                'read_at'        => null,
            ]);
        }
    }
}
