<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\Message;
use App\Models\Partnership;
use App\Models\Organization;
use App\Models\Notification;

class UserController extends Controller
{
    /**
     * Display the user's personal dashboard.
     * This method gathers all the necessary data for the dashboard view.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $userOrganizationIds = $user->organizations()->pluck('organizations.OrganizationID');

        $partnerships = Partnership::with('proposal')
            ->where(function ($query) use ($userOrganizationIds) {
                $query->whereIn('OrganizationSenderID', $userOrganizationIds)
                    ->orWhereIn('OrganizationTargetID', $userOrganizationIds);
            })
            ->get();

        $events = $partnerships->map(function ($p) {
            return [
                'title' => $p->proposal->ProposalTitle ?? 'Partnership',
                'start' => $p->StartDate,
                'end'   => $p->EndDate,
                'url'   => route('proposals.show', ['proposal' => $p->ProposalID]),
                'color' => '#f8971d',
                'borderColor' => '#f8971d'
            ];
        });

        $upcomingPartnerships = $partnerships->where('Status', 'Active')
                                            ->where('EndDate', '>=', now())
                                            ->sortBy('EndDate');

        $proposals = \App\Models\Proposal::with('targetOrganization')
                        ->where('UserID', $user->UserID)->latest()->take(5)->get();
        $organizations = $user->organizations()->withCount('users')->get();
        $latestUnreadMessage = null;

        return view('user.home', [
            'user' => $user,
            'organizations' => $organizations,
            'proposals' => $proposals,
            'latestUnreadMessage' => $latestUnreadMessage,
            'upcomingPartnerships' => $upcomingPartnerships,
            'events' => $events,
        ]);
    }

    public function getUserNotifications()
    {
        return Notification::where('TargetType', 'user')
            ->where('TargetID', auth()->id())
            ->latest()
            ->take(5)
            ->get();
    }
}