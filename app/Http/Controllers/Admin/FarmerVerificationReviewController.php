<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FarmerDocument;
use App\Models\FarmerVerificationRequest;
use App\Notifications\FarmerVerificationStatusNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FarmerVerificationReviewController extends Controller
{
        public function index(Request $request)
    {
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Web-uploaded documents
        $webDocs = FarmerDocument::with('farmer')
            ->select('id', 'farmer_id as user_id', 'document_path', 'status', 'created_at', 'updated_at')
            ->get()
            ->map(function ($doc) {
                $doc->source = 'web';
                $doc->farmer = $doc->farmer ?? User::find($doc->user_id);
                return $doc;
            });

        // Mobile-uploaded documents
        $mobileDocs = FarmerVerificationRequest::with('farmer')
            ->select('id', 'user_id', 'document_path', 'status', 'created_at', 'updated_at')
            ->get()
            ->map(function ($doc) {
                $doc->source = 'mobile';
                $doc->farmer = $doc->farmer ?? User::find($doc->user_id);
                return $doc;
            });

        // Merge & sort
        $merged = $webDocs->merge($mobileDocs)->sortByDesc('created_at')->values();

        // Manual pagination
        $paginated = new LengthAwarePaginator(
            $merged->forPage($currentPage, $perPage),
            $merged->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.verifications.index', [
            'documents' => $paginated,
        ]);
    }

    public function approve($id)
    {
        $doc = FarmerDocument::find($id) ?? FarmerVerificationRequest::findOrFail($id);
        $doc->status = 'approved';
        $doc->admin_note = null;
        $doc->submitted_at = now();
        $doc->save();

        $farmer = $doc->farmer ?? User::find($doc->user_id);
        if ($farmer) {
            $farmer->is_verified = true;
            $farmer->save();
            $farmer->notify(new FarmerVerificationStatusNotification('approved'));
        }

        return back()->with('success', 'Farmer verification approved.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000',
        ]);

        $doc = FarmerDocument::find($id) ?? FarmerVerificationRequest::findOrFail($id);
        $doc->status = 'rejected';
        $doc->admin_note = $request->admin_note;
        $doc->save();

        $farmer = $doc->farmer ?? User::find($doc->user_id);
        if ($farmer) {
            $farmer->notify(new FarmerVerificationStatusNotification('rejected', $request->admin_note));
        }

        return back()->with('error', 'Document rejected with note.');
    }
public function approveWeb($id)
{
    $document = FarmerDocument::findOrFail($id);
    $document->status = 'approved';
    $document->admin_note = null;
    $document->submitted_at = now();
    $document->save();

    // Mark farmer as verified
    $document->farmer->is_verified = true;
    $document->farmer->save();

    // Notify farmer
    $document->farmer->notify(new FarmerVerificationStatusNotification('approved'));

    return back()->with('success', 'Web verification approved successfully.');
}
public function rejectWeb(Request $request, $id)
{
    $request->validate([
        'admin_note' => 'required|string|max:1000',
    ]);

    $document = FarmerDocument::findOrFail($id);
    $document->status = 'rejected';
    $document->admin_note = $request->admin_note;
    $document->save();

    $document->farmer->notify(new FarmerVerificationStatusNotification('rejected', $request->admin_note));

    return back()->with('error', 'Document rejected with note.');
}
}
