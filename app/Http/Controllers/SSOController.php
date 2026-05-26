<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SSOController extends Controller
{
    /**
     * Handle SSO login from Workspace
     */
    public function login(Request $request)
    {
        $token = $request->input('token');

        if (! $token) {
            return redirect()->route('login')
                ->with('error', 'Invalid SSO request');
        }

        try {
            $payload = $this->decodeToken($token);

            // Validate token is for this app
            if (($payload['target'] ?? '') !== 'hire') {
                throw new \Exception('Invalid token target');
            }

            // Find user by linked workspace_user_id or by target_user_id
            $user = null;

            if (! empty($payload['target_user_id'])) {
                $user = User::find($payload['target_user_id']);
            }

            if (! $user && ! empty($payload['user_id'])) {
                $user = User::where('workspace_user_id', $payload['user_id'])->first();
            }

            if (! $user) {
                // Fallback: try email matching (for initial setup)
                $user = User::where('email', $payload['email'])->first();

                if ($user && ! $user->workspace_user_id) {
                    // Auto-link if email matches
                    $user->update([
                        'workspace_user_id' => $payload['user_id'],
                        'workspace_linked_at' => now(),
                    ]);
                }
            }

            if (! $user) {
                return redirect()->route('login')
                    ->with('error', 'No linked account found. Please link your accounts first.');
            }

            // Log the user in
            Auth::login($user, true);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'SSO login failed: '.$e->getMessage());
        }
    }

    /**
     * Handle account linking request from Workspace
     */
    public function link(Request $request)
    {
        $token = $request->input('token');
        $callback = $request->input('callback');

        if (! $token || ! $callback) {
            return redirect()->route('login')
                ->with('error', 'Invalid linking request');
        }

        try {
            $payload = $this->decodeToken($token);

            if (($payload['action'] ?? '') !== 'link') {
                throw new \Exception('Invalid action');
            }

            // Store linking info in session
            session([
                'sso_linking' => [
                    'workspace_user_id' => $payload['workspace_user_id'],
                    'workspace_email' => $payload['workspace_email'],
                    'workspace_name' => $payload['workspace_name'],
                    'callback' => $callback,
                    'expires_at' => now()->addMinutes(5),
                ],
            ]);

            // If user is already logged in, confirm linking
            if (Auth::check()) {
                return redirect()->route('sso.link.confirm');
            }

            // Otherwise, show login page with linking context
            return redirect()->route('login')
                ->with('info', 'Please login to link your account with '.$payload['workspace_name']);

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Invalid linking request: '.$e->getMessage());
        }
    }

    /**
     * Confirm account linking
     */
    public function confirmLink(Request $request)
    {
        $linkingData = session('sso_linking');

        if (! $linkingData || now()->isAfter($linkingData['expires_at'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Linking session expired');
        }

        $user = Auth::user();

        // Check if already linked to a different workspace account
        if ($user->workspace_user_id && $user->workspace_user_id != $linkingData['workspace_user_id']) {
            return view('sso.confirm-link', [
                'workspace_email' => $linkingData['workspace_email'],
                'workspace_name' => $linkingData['workspace_name'],
                'already_linked' => true,
            ]);
        }

        return view('sso.confirm-link', [
            'workspace_email' => $linkingData['workspace_email'],
            'workspace_name' => $linkingData['workspace_name'],
            'already_linked' => false,
        ]);
    }

    /**
     * Process the linking confirmation
     */
    public function processLink(Request $request)
    {
        $linkingData = session('sso_linking');

        if (! $linkingData || now()->isAfter($linkingData['expires_at'])) {
            return redirect()->route('dashboard')
                ->with('error', 'Linking session expired');
        }

        $user = Auth::user();

        // Update user with workspace link
        $user->update([
            'workspace_user_id' => $linkingData['workspace_user_id'],
            'workspace_linked_at' => now(),
        ]);

        // Clear session
        session()->forget('sso_linking');

        // Generate callback token
        $callbackPayload = [
            'workspace_user_id' => $linkingData['workspace_user_id'],
            'hire_user_id' => $user->id,
            'hire_email' => $user->email,
            'action' => 'link_complete',
            'exp' => time() + 60,
            'iat' => time(),
        ];

        $callbackToken = $this->encodeToken($callbackPayload);

        // Redirect back to workspace with confirmation
        return redirect($linkingData['callback'].'?token='.$callbackToken);
    }

    /**
     * Cancel linking
     */
    public function cancelLink()
    {
        session()->forget('sso_linking');

        return redirect()->route('dashboard')
            ->with('info', 'Account linking cancelled');
    }

    /**
     * Decode and verify token
     */
    private function decodeToken(string $token): array
    {
        $secret = config('services.sso.secret');

        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \Exception('Invalid token format');
        }

        [$header, $payload, $signature] = $parts;

        // Verify signature
        $expectedSignature = base64_encode(hash_hmac('sha256', "$header.$payload", $secret, true));
        if (! hash_equals($expectedSignature, $signature)) {
            throw new \Exception('Invalid token signature');
        }

        $payload = json_decode(base64_decode($payload), true);

        // Check expiry
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new \Exception('Token expired');
        }

        return $payload;
    }

    /**
     * Encode payload to token
     */
    private function encodeToken(array $payload): string
    {
        $secret = config('services.sso.secret');

        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", $secret, true);
        $signature = base64_encode($signature);

        return "$header.$payload.$signature";
    }
}
