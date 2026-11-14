<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NewNotification;

trait CreatesNotifications
{
    /**
     * Create a notification for a user
     * 
     * @param int $userId
     * @param string $type (transfer, deposit, withdrawal, password_change, login, etc.)
     * @param string $title
     * @param string $message
     * @param array $data Additional data (optional)
     * @return Notification
     */
    protected function createNotification($userId, $type, $title, $message, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    /**
     * Create a transfer notification
     */
    protected function notifyTransfer($userId, $amount, $currency, $recipient, $status = 'successful', $type = null)
    {
        $statusText = $status === 'successful' ? 'successful' : 'pending';
        
        if ($type === 'crypto') {
            $title = ucfirst($statusText) . ' Cryptocurrency Transfer';
            $message = "Your cryptocurrency transfer of {$amount} to wallet {$recipient} was {$statusText}.";
        } else {
            $title = ucfirst($statusText) . ' Transfer';
            $message = "Your transfer of {$currency}{$amount} to {$recipient} was {$statusText}.";
        }
        
        return $this->createNotification(
            $userId,
            'transfer',
            $title,
            $message,
            [
                'amount' => $amount,
                'currency' => $currency,
                'recipient' => $recipient,
                'status' => $status,
                'type' => $type
            ]
        );
    }
    
    /**
     * Create a deposit notification
     */
    protected function notifyDeposit($userId, $amount, $currency, $method = null)
    {
        $methodText = $method ? " via {$method}" : '';
        
        return $this->createNotification(
            $userId,
            'deposit',
            'Deposit Received',
            "Your account has been credited with {$currency}{$amount}{$methodText}.",
            [
                'amount' => $amount,
                'currency' => $currency,
                'method' => $method
            ]
        );
    }
    
    /**
     * Create a withdrawal notification
     */
    protected function notifyWithdrawal($userId, $amount, $currency, $status = 'processing')
    {
        $statusText = $status === 'successful' ? 'completed' : 'is being processed';
        
        return $this->createNotification(
            $userId,
            'withdrawal',
            'Withdrawal ' . ucfirst($status),
            "Your withdrawal of {$currency}{$amount} {$statusText}.",
            [
                'amount' => $amount,
                'currency' => $currency,
                'status' => $status
            ]
        );
    }
    
    /**
     * Create a password change notification
     */
    protected function notifyPasswordChange($userId)
    {
        return $this->createNotification(
            $userId,
            'password_change',
            'Password Changed',
            'Your password has been successfully updated. If you did not make this change, please contact support immediately.',
            []
        );
    }
    
    /**
     * Create a login notification
     */
    protected function notifyLogin($userId, $ip = null, $location = null)
    {
        $locationText = $location ? " from {$location}" : '';
        $ipText = $ip ? " (IP: {$ip})" : '';
        
        return $this->createNotification(
            $userId,
            'login',
            'New Login Detected',
            "Your account was accessed{$locationText}{$ipText}.",
            [
                'ip' => $ip,
                'location' => $location
            ]
        );
    }
    
    /**
     * Create a card notification
     */
    protected function notifyCard($userId, $action, $cardType = 'Virtual Card')
    {
        $actions = [
            'created' => 'Your ' . $cardType . ' has been created successfully.',
            'funded' => 'Your ' . $cardType . ' has been funded.',
            'frozen' => 'Your ' . $cardType . ' has been frozen for security.',
            'unfrozen' => 'Your ' . $cardType . ' has been activated.',
            'deleted' => 'Your ' . $cardType . ' has been deleted.'
        ];
        
        $message = $actions[$action] ?? 'Card action performed.';
        
        return $this->createNotification(
            $userId,
            'card',
            ucfirst($action) . ' - ' . $cardType,
            $message,
            [
                'action' => $action,
                'card_type' => $cardType
            ]
        );
    }
    
    /**
     * Create a loan notification
     */
    protected function notifyLoan($userId, $amount, $currency, $status)
    {
        $statusMessages = [
            'approved' => 'Your loan application for ' . $currency . $amount . ' has been approved.',
            'rejected' => 'Your loan application for ' . $currency . $amount . ' has been rejected.',
            'pending' => 'Your loan application for ' . $currency . $amount . ' is being reviewed.',
            'disbursed' => 'Your loan of ' . $currency . $amount . ' has been disbursed to your account.'
        ];
        
        return $this->createNotification(
            $userId,
            'loan',
            'Loan ' . ucfirst($status),
            $statusMessages[$status] ?? 'Loan status updated.',
            [
                'amount' => $amount,
                'currency' => $currency,
                'status' => $status
            ]
        );
    }
    
    /**
     * Create a profile update notification
     */
    protected function notifyProfileUpdate($userId, $field = null)
    {
        $fieldText = $field ? " Your {$field} has been updated." : '';
        
        return $this->createNotification(
            $userId,
            'profile_update',
            'Profile Updated',
            "Your profile information has been updated successfully.{$fieldText}",
            [
                'field' => $field
            ]
        );
    }

    /**
     * Create account block notification
     */
    public function notifyBlock($userId, $reason, $isPermanent = false, $unblockDate = null)
    {
        $user = User::find($userId);
        $blockType = $isPermanent ? 'Permanent Ban' : 'Temporary Block';
        $settings = Settings::first();
        
        $message = $isPermanent 
            ? "Your account has been permanently banned. Reason: {$reason}"
            : "Your account has been temporarily blocked. Reason: {$reason}";
        
        if ($unblockDate) {
            $message .= " Your account will be automatically unblocked on " . $unblockDate->format('M d, Y \\a\\t h:i A');
        }
        
        $message .= " Contact: {$settings->contact_email}";
        
        $this->createNotification(
            $userId,
            'Account ' . $blockType,
            $message,
            'danger'
        );
        
        try {
            Mail::to($user->email)->send(new \App\Mail\NewNotification(
                $message,
                "Account {$blockType} - {$settings->site_name}",
                $user->name
            ));
        } catch (\Exception $e) {
            \Log::error('Block notification email failed: ' . $e->getMessage());
        }
    }

    /**
     * Create account unblock notification
     */
    public function notifyUnblock($userId, $note = null)
    {
        $user = User::find($userId);
        $settings = Settings::first();
        
        $message = "Good news! Your account has been unblocked and all banking services have been restored.";
        
        if ($note) {
            $message .= " Note: {$note}";
        }
        
        $this->createNotification(
            $userId,
            'Account Unblocked',
            $message,
            'success'
        );
        
        try {
            Mail::to($user->email)->send(new \App\Mail\NewNotification(
                $message,
                "Account Unblocked - {$settings->site_name}",
                $user->name
            ));
        } catch (\Exception $e) {
            \Log::error('Unblock notification email failed: ' . $e->getMessage());
        }
    }

    /**
     * Notify user of a crypto swap
     */
    protected function notifyCryptoSwap($userId, $fromCoin, $toCoin, $amount, $received)
    {
        $fromLabel = strtoupper($fromCoin);
        $toLabel = strtoupper($toCoin);
        $amountFmt = ($fromCoin === 'usd') ? number_format($amount, 2) : number_format($amount, 8);
        $receivedFmt = ($toCoin === 'usd') ? number_format($received, 2) : number_format($received, 8);

        return $this->createNotification(
            $userId,
            'Crypto Swap',
            "You swapped {$amountFmt} {$fromLabel} to {$receivedFmt} {$toLabel}.",
            'success'
        );
    }

    /**
     * Notify user of admin crypto funding
     */
    protected function notifyCryptoFunding($userId, $coin, $amount, $action = 'credit')
    {
        $label = strtoupper($coin);
        $amountFmt = number_format($amount, 8);
        $verb = $action === 'credit' ? 'credited' : 'debited';

        return $this->createNotification(
            $userId,
            'Crypto ' . ucfirst($action),
            "Your {$label} wallet has been {$verb} with {$amountFmt} {$label}.",
            'info'
        );
    }
}
