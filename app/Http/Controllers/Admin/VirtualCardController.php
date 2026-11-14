<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardSettings;
use App\Models\CardTransaction;
use App\Models\User;
use App\Helpers\CardGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VirtualCardController extends Controller
{
    /**
     * Display all cards
     */
    public function index(Request $request)
    {
        $query = Card::with('user')->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by card level
        if ($request->has('level') && $request->level) {
            $query->where('card_level', $request->level);
        }
        
        // Search by user or card number
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                  ->orWhere('last_four', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $cards = $query->paginate(20);
        
        $stats = [
            'total' => Card::count(),
            'pending' => Card::where('status', 'pending')->count(),
            'active' => Card::where('status', 'active')->count(),
            'blocked' => Card::where('status', 'blocked')->count(),
        ];
        
        return view('admin.cards.index', compact('cards', 'stats'));
    }

    /**
     * Display pending card applications
     */
    public function pending()
    {
        $cards = Card::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(20);
        
        return view('admin.cards.pending', compact('cards'));
    }

    /**
     * View single card details
     */
    public function viewCard($id)
    {
        $card = Card::with('user')->findOrFail($id);
        
        $transactions = CardTransaction::where('card_id', $card->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.cards.view', compact('card', 'transactions'));
    }

    /**
     * Approve card application
     */
    public function approveCard($id)
    {
        $card = Card::with('user')->findOrFail($id);

        if ($card->status !== 'pending') {
            return back()->with('error', 'Only pending cards can be approved.');
        }

        DB::beginTransaction();
        try {
            // Generate card details
            $cardNumber = CardGenerator::generateCardNumber($card->card_type);
            $cvv = CardGenerator::generateCVV($card->card_type);
            $expiry = CardGenerator::generateExpiry();
            $token = CardGenerator::generateToken();
            $bin = CardGenerator::getBIN($cardNumber);
            $lastFour = CardGenerator::getLastFour($cardNumber);
            $encryptedPAN = CardGenerator::encryptPAN($cardNumber);

            // Update card
            $card->update([
                'card_number' => $cardNumber,
                'cvv' => $cvv,
                'expiry' => $expiry['formatted'],
                'expiry_month' => $expiry['month'],
                'expiry_year' => $expiry['year'],
                'card_pan' => $encryptedPAN,
                'card_token' => $token,
                'card_bin' => $bin,
                'last_four' => $lastFour,
                'status' => 'active',
                'activated_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Card approved and activated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve card. Please try again.');
        }
    }

    /**
     * Reject card application
     */
    public function rejectCard(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $card = Card::with('user')->findOrFail($id);

        if ($card->status !== 'pending') {
            return back()->with('error', 'Only pending cards can be rejected.');
        }

        DB::beginTransaction();
        try {
            // Update card status
            $card->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Refund issuance fee to user
            $user = $card->user;
            $user->balance += $card->amount;
            $user->save();

            // Record refund transaction
            CardTransaction::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'type' => 'refund',
                'amount' => $card->amount,
                'description' => 'Card application rejected - fee refunded',
                'status' => 'completed',
                'balance_before' => $user->balance - $card->amount,
                'balance_after' => $user->balance,
            ]);

            DB::commit();

            return back()->with('success', 'Card application rejected. Issuance fee refunded to user.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject card. Please try again.');
        }
    }

    /**
     * Block card
     */
    public function blockCard($id)
    {
        $card = Card::findOrFail($id);

        if ($card->status === 'blocked') {
            return back()->with('error', 'Card is already blocked.');
        }

        if (!in_array($card->status, ['active', 'inactive'])) {
            return back()->with('error', 'Cannot block this card.');
        }

        $card->update([
            'status' => 'blocked',
            'blocked_at' => now(),
        ]);

        return back()->with('success', 'Card blocked successfully.');
    }

    /**
     * Unblock card
     */
    public function unblockCard($id)
    {
        $card = Card::findOrFail($id);

        if ($card->status !== 'blocked') {
            return back()->with('error', 'Card is not blocked.');
        }

        $card->update([
            'status' => 'active',
            'blocked_at' => null,
        ]);

        return back()->with('success', 'Card unblocked successfully.');
    }

    /**
     * Top up card balance
     */
    public function topupCard(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $card = Card::with('user')->findOrFail($id);

        if ($card->status !== 'active') {
            return back()->with('error', 'Only active cards can be topped up.');
        }

        DB::beginTransaction();
        try {
            $balanceBefore = $card->balance;
            $card->balance += $request->amount;
            $card->save();

            // Record transaction
            CardTransaction::create([
                'card_id' => $card->id,
                'user_id' => $card->user_id,
                'type' => 'topup',
                'amount' => $request->amount,
                'description' => $request->description ?? 'Admin top-up',
                'status' => 'completed',
                'balance_before' => $balanceBefore,
                'balance_after' => $card->balance,
            ]);

            DB::commit();

            return back()->with('success', 'Card topped up successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to top up card. Please try again.');
        }
    }

    /**
     * Deduct from card balance
     */
    public function deductCard(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        $card = Card::with('user')->findOrFail($id);

        if ($card->balance < $request->amount) {
            return back()->with('error', 'Insufficient card balance.');
        }

        DB::beginTransaction();
        try {
            $balanceBefore = $card->balance;
            $card->balance -= $request->amount;
            $card->save();

            // Record transaction
            CardTransaction::create([
                'card_id' => $card->id,
                'user_id' => $card->user_id,
                'type' => 'deduction',
                'amount' => $request->amount,
                'description' => $request->description,
                'status' => 'completed',
                'balance_before' => $balanceBefore,
                'balance_after' => $card->balance,
            ]);

            DB::commit();

            return back()->with('success', 'Amount deducted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to deduct amount. Please try again.');
        }
    }

    /**
     * Delete card
     */
    public function deleteCard($id)
    {
        $card = Card::with('user')->findOrFail($id);

        if ($card->balance > 0) {
            return back()->with('error', 'Cannot delete card with non-zero balance. Please deduct balance first.');
        }

        DB::beginTransaction();
        try {
            // Delete transactions first
            CardTransaction::where('card_id', $card->id)->delete();
            
            // Delete card
            $card->delete();

            DB::commit();

            return redirect()->route('admin.cards.index')
                ->with('success', 'Card deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete card. Please try again.');
        }
    }

    /**
     * Show card settings page
     */
    public function settings()
    {
        $cardSettings = CardSettings::getSettings();
        
        return view('admin.cards.cardsettings', [
            'cardSettings' => $cardSettings,
        ]);

        
    }

    /**
     * Update card settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'standard_fee' => 'required|numeric|min:0',
            'gold_fee' => 'required|numeric|min:0',
            'platinum_fee' => 'required|numeric|min:0',
            'black_fee' => 'required|numeric|min:0',
            'monthly_fee' => 'required|numeric|min:0',
            'topup_fee_percentage' => 'required|numeric|min:0|max:100',
            'min_daily_limit' => 'required|numeric|min:0',
            'max_daily_limit' => 'required|numeric|min:0',
            'cards_enabled' => 'required|boolean',
            'admin_notes' => 'nullable|string',
        ]);

        $cardSettings = CardSettings::getSettings();
        $cardSettings->update($request->all());

        return back()->with('success', 'Card settings updated successfully.');
    }

    /**
     * Toggle card system status
     */
    public function toggleSystem()
    {
        $cardSettings = CardSettings::getSettings();
        $cardSettings->cards_enabled = !$cardSettings->cards_enabled;
        $cardSettings->save();

        $status = $cardSettings->cards_enabled ? 'enabled' : 'disabled';
        
        return back()->with('success', 'Card system ' . $status . ' successfully.');
    }
}
