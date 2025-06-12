<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        // 1. Fetch all proposals from your database.
        $proposals = Proposal::all();

        // 2. Prepare an empty array for the calendar events.
        $events = [];

        // 3. Loop through each proposal and format it using the correct column names from your ERD.
        foreach ($proposals as $proposal) {
            $events[] = [
                // 'title' is what FullCalendar expects. We're getting the value from your 'ProposalName' column.
                'title' => $proposal->ProposalName,

                // 'start' is what FullCalendar expects. The value comes from your 'StartDate' column.
                'start' => $proposal->StartDate,

                // 'end' is what FullCalendar expects. The value comes from your 'EndDate' column.
                'end'   => $proposal->EndDate,
            ];
        }

        // 4. Pass the formatted $events array to your view.
        return view('your-calendar-view-name', compact('events'));
    }
}
