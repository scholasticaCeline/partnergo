<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\Message;
use App\Models\Organization;

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

        // --- 1. Fetch Calendar Events (Accepted Partnerships) ---
        $userOrganizationIds = $user->organizations()->pluck('organizations.OrganizationID');

        $partnerships = Proposal::where('ProposalStatus', 'accepted')
            ->whereNotNull('StartDate')
            ->whereNotNull('EndDate')
            ->where(function ($query) use ($user, $userOrganizationIds) {
                $query->where('UserID', $user->UserID)
                    ->orWhereIn('OrganizationID', $userOrganizationIds);
            })
            ->get();

        $events = $partnerships->map(function ($partnership) {
            return [
                'title' => $partnership->ProposalTitle,
                'start' => $partnership->StartDate,
                'end'   => $partnership->EndDate,
                'url'   => route('proposals.show', $partnership->ProposalID),
                'backgroundColor' => '#16a34a',
                'borderColor'     => '#16a34a'
            ];
        });


        // --- 2. Fetch the Latest Unread Message (CORRECTED QUERY) ---
        $latestUnreadMessage = null;
        if (class_exists(\App\Models\Message::class)) {
            // This query now matches your confirmed database structure exactly.
            $latestUnreadMessage = \App\Models\Message::where('ReceiverID', $user->UserID) // Use your ReceiverID column
                                            ->whereNull('readAt')      // Check for NULL in your readAt column
                                            ->with('sender')
                                            ->latest('CreatedAt')      // Order by your CreatedAt column
                                            ->first();
        }


        // --- 3. Fetch Data for Sidebar Widgets ---
        $proposals = Proposal::with('targetOrganization')
                            ->where('UserID', $user->UserID)
                            ->latest()
                            ->take(5)
                            ->get();

        $organizations = $user->organizations()->withCount('users')->get();


        // --- 4. Return the View with all the data ---
        return view('user.home', [ // Or user.dashboard, depending on your file name
            'user' => $user,
            'organizations' => $organizations,
            'events' => $events,
            'proposals' => $proposals,
            'latestUnreadMessage' => $latestUnreadMessage,
        ]);
    }
}