<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GrantApplication;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GrantApplicationController extends Controller
{
    /**
     * Show grant application selection page
     */
    public function index()
    {
        $settings = Settings::latest()->first();
        $title = "Grant Application";
        
        return view('user.grant.index', compact('settings', 'title'));
    }

    /**
     * Show individual grant application form
     */
    public function individual()
    {
        $settings = Settings::latest()->first();
        $title = "Individual Grant Application";
        
        return view('user.grant.individual', compact('settings', 'title'));
    }

    /**
     * Show company grant application form
     */
    public function company()
    {
        $settings = Settings::latest()->first();
        $title = "Company Grant Application";
        
        return view('user.grant.company', compact('settings', 'title'));
    }

    /**
     * Store individual grant application
     */
    public function storeIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'requested_amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        
        $notes = "Application Type: Individual\n\n";
        if ($request->funding_details) {
            $notes .= "Funding Details: {$request->funding_details}\n\n";
        }
        
        $fundingTypes = [];
        if ($request->program_funding) $fundingTypes[] = 'Program Funding';
        if ($request->research_funding) $fundingTypes[] = 'Research Funding';
        if ($request->capacity_funding) $fundingTypes[] = 'Capacity Funding';
        if ($request->other_funding) $fundingTypes[] = 'Other Funding';
        
        if (!empty($fundingTypes)) {
            $notes .= "Selected Funding Types: " . implode(', ', $fundingTypes) . "\n\n";
        }

        $application = GrantApplication::create([
            'user_id' => $user->id,
            'application_type' => 'individual',
            'status' => 'processing',
            'requested_amount' => $request->requested_amount,
            'program_funding' => $request->program_funding ? 1 : 0,
            'research_funding' => $request->research_funding ? 1 : 0,
            'equipment_funding' => $request->capacity_funding ? 1 : 0,
            'community_outreach' => $request->other_funding ? 1 : 0,
            'notes' => $notes,
        ]);

        return redirect()->route('grant.processing', ['id' => $application->id]);
    }

    /**
     * Store company grant application
     */
    public function storeCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'legal_name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:30',
            'organization_type' => 'required|in:nonprofit,for-profit,government,educational',
            'founding_year' => 'required|integer|min:1800|max:' . date('Y'),
            'phone_number' => 'required|string',
            'contact_person' => 'required|string',
            'mission_statement' => 'required|string',
            'mailing_address' => 'required|string',
            'incorporation_date' => 'required|date',
            'project_title' => 'required|string',
            'project_description' => 'required|string',
            'expected_outcomes' => 'required|string',
            'requested_amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        
        $notes = "Application Type: Company\n\n";
        $notes .= "Organization Type: {$request->organization_type}\n";
        $notes .= "Founding Year: {$request->founding_year}\n";
        $notes .= "Phone: {$request->phone_number}\n";
        $notes .= "Contact Person: {$request->contact_person}\n\n";
        $notes .= "Project Title: {$request->project_title}\n\n";
        $notes .= "Project Description:\n{$request->project_description}\n\n";
        $notes .= "Expected Outcomes:\n{$request->expected_outcomes}\n\n";

        $application = GrantApplication::create([
            'user_id' => $user->id,
            'application_type' => 'company',
            'status' => 'processing',
            'legal_name' => $request->legal_name,
            'ein' => $request->tax_id,
            'mailing_address' => $request->mailing_address,
            'incorporation_date' => $request->incorporation_date,
            'mission_statement' => $request->mission_statement,
            'requested_amount' => $request->requested_amount,
            'notes' => $notes,
        ]);

        return redirect()->route('grant.processing', ['id' => $application->id]);
    }

    /**
     * Show processing page
     */
    public function processing(Request $request)
    {
        $settings = Settings::latest()->first();
        $title = "Processing Application";
        $applicationId = $request->id;
        
        $application = GrantApplication::where('id', $applicationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        return view('user.grant.processing', compact('settings', 'title', 'application'));
    }

    /**
     * Show results page and auto-process application
     */
    public function results(Request $request)
    {
        $settings = Settings::latest()->first();
        $title = "Application Results";
        $applicationId = $request->id;
        
        $application = GrantApplication::where('id', $applicationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $user = Auth::user();
        
        // Auto-process if still in processing status
        if ($application->status === 'processing') {
            if ($user->grant_limit > 0) {
                // Auto-approve
                $application->status = 'approved';
                $application->approved_amount = $user->grant_limit;
                $application->addNote("System: Application auto-approved based on grant limit of {$settings->currency}" . number_format($user->grant_limit, 2));
            } else {
                // Auto-reject
                $application->status = 'rejected';
                $application->addNote("System: Application auto-rejected - No grant limit available");
            }
            $application->save();
        }
        
        return view('user.grant.results', compact('settings', 'title', 'application'));
    }

    /**
     * Show all user's applications
     */
    public function myApplications()
    {
        $settings = Settings::latest()->first();
        $title = "My Grant Applications";
        
        $applications = GrantApplication::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.grant.myApplications', compact('settings', 'title', 'applications'));
    }

    /**
     * View single application
     */
    public function view($id)
    {
        $settings = Settings::latest()->first();
        $title = "Application Details";
        
        $application = GrantApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        return view('user.grant.view', compact('settings', 'title', 'application'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $settings = Settings::latest()->first();
        
        $application = GrantApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Only allow editing if status is processing
        if ($application->status !== 'processing') {
            return redirect()->route('grant.view', $id)
                ->with('error', 'You cannot edit this application as it has already been processed.');
        }
        
        if ($application->application_type === 'individual') {
            $title = "Edit Individual Grant Application";
            return view('user.grant.edit-individual', compact('settings', 'title', 'application'));
        } else {
            $title = "Edit Company Grant Application";
            return view('user.grant.edit-company', compact('settings', 'title', 'application'));
        }
    }

    /**
     * Update individual application
     */
    public function updateIndividual(Request $request, $id)
    {
        $application = GrantApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($application->status !== 'processing') {
            return back()->with('error', 'You cannot edit this application as it has already been processed.');
        }

        $validator = Validator::make($request->all(), [
            'requested_amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $notes = "Application Type: Individual\n\n";
        if ($request->funding_details) {
            $notes .= "Funding Details: {$request->funding_details}\n\n";
        }
        
        $fundingTypes = [];
        if ($request->program_funding) $fundingTypes[] = 'Program Funding';
        if ($request->research_funding) $fundingTypes[] = 'Research Funding';
        if ($request->capacity_funding) $fundingTypes[] = 'Capacity Funding';
        if ($request->other_funding) $fundingTypes[] = 'Other Funding';
        
        if (!empty($fundingTypes)) {
            $notes .= "Selected Funding Types: " . implode(', ', $fundingTypes) . "\n\n";
        }

        $application->update([
            'requested_amount' => $request->requested_amount,
            'program_funding' => $request->program_funding ? 1 : 0,
            'research_funding' => $request->research_funding ? 1 : 0,
            'equipment_funding' => $request->capacity_funding ? 1 : 0,
            'community_outreach' => $request->other_funding ? 1 : 0,
            'notes' => $notes . "\n\n[PREVIOUS NOTES]\n" . ($application->notes ?? ''),
        ]);

        $application->addNote("User updated application");

        return redirect()->route('grant.view', $id)->with('success', 'Application updated successfully.');
    }

    /**
     * Update company application
     */
    public function updateCompany(Request $request, $id)
    {
        $application = GrantApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($application->status !== 'processing') {
            return back()->with('error', 'You cannot edit this application as it has already been processed.');
        }

        $validator = Validator::make($request->all(), [
            'legal_name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:30',
            'organization_type' => 'required|in:nonprofit,for-profit,government,educational',
            'founding_year' => 'required|integer|min:1800|max:' . date('Y'),
            'phone_number' => 'required|string',
            'contact_person' => 'required|string',
            'mission_statement' => 'required|string',
            'mailing_address' => 'required|string',
            'incorporation_date' => 'required|date',
            'project_title' => 'required|string',
            'project_description' => 'required|string',
            'expected_outcomes' => 'required|string',
            'requested_amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $notes = "Application Type: Company\n\n";
        $notes .= "Organization Type: {$request->organization_type}\n";
        $notes .= "Founding Year: {$request->founding_year}\n";
        $notes .= "Phone: {$request->phone_number}\n";
        $notes .= "Contact Person: {$request->contact_person}\n\n";
        $notes .= "Project Title: {$request->project_title}\n\n";
        $notes .= "Project Description:\n{$request->project_description}\n\n";
        $notes .= "Expected Outcomes:\n{$request->expected_outcomes}\n\n";

        $application->update([
            'legal_name' => $request->legal_name,
            'ein' => $request->tax_id,
            'mailing_address' => $request->mailing_address,
            'incorporation_date' => $request->incorporation_date,
            'mission_statement' => $request->mission_statement,
            'requested_amount' => $request->requested_amount,
            'notes' => $notes . "\n\n[PREVIOUS NOTES]\n" . ($application->notes ?? ''),
        ]);

        $application->addNote("User updated application");

        return redirect()->route('grant.view', $id)->with('success', 'Application updated successfully.');
    }
}
