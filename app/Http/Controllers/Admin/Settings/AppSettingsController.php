<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\SettingsCont;
use Illuminate\Support\Facades\Storage;

class AppSettingsController extends Controller
{

    // Return view
    public function appsettingshow()
    {
        $live_timezones = timezone_identifiers_list();
        include 'currencies.php';
        return view('admin.Settings.AppSettings.show', [
            'title' => 'Website information settings',
            'timezones' => $live_timezones,
            'currencies' => $currencies,
            'timezone' => config('app.timezone'),
            'settings' => Settings::where('id', '=', '1')->first(),
        ]);
    }

    // for front end content management
    function RandomStringGenerator($n)
    {
        $generated_string = "";
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        // Return the random generated string 
        return $generated_string;
    }

    // updatertransfercodes
    public function updatertransfercodes(Request $request)
    {
        Settings::where('id', '1')
            ->update([
                'code1' => $request->code1,
                'code2' => $request->code2,
                'code3' => $request->code3,
                'code1status' => $request->code1status,
                'code2status' => $request->code2status,
                'code3status' => $request->code3status,
                'code1message' => $request->code1message,
                'code2message' => $request->code2message,
                'code3message' => $request->code3message,
                'otp' => $request->otp,
            ]);

        return redirect()->back()->with('success', 'Settings Saved successfully');
    }
    /**
     * Save an uploaded branding image into storage/app/public/photos.
     * Uses a direct filesystem move so aaPanel/open_basedir issues surface clearly.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string|null  $oldRelativePath  e.g. photos/old.png
     * @return array{0: ?string, 1: ?string}  [relativePath, errorMessage]
     */
    protected function storePublicPhoto($file, $oldRelativePath = null): array
    {
        if (!$file->isValid()) {
            return [null, 'Upload invalid: ' . $file->getErrorMessage()];
        }

        $photosDir = storage_path('app/public/photos');

        if (!is_dir($photosDir) && !@mkdir($photosDir, 0775, true) && !is_dir($photosDir)) {
            return [null, $this->storagePermissionHint($photosDir, 'Could not create photos directory')];
        }

        if (!is_writable($photosDir)) {
            return [null, $this->storagePermissionHint($photosDir, 'photos directory is not writable')];
        }

        // Remove previous file when present
        if (!empty($oldRelativePath)) {
            $oldAbsolute = storage_path('app/public/' . ltrim(str_replace('\\', '/', $oldRelativePath), '/'));
            if (is_file($oldAbsolute)) {
                @unlink($oldAbsolute);
            }
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'png');
        $filename = uniqid('brand_', true) . '.' . $extension;
        $relative = 'photos/' . $filename;
        $absolute = $photosDir . DIRECTORY_SEPARATOR . $filename;

        try {
            // Prefer native move — more reliable than Flysystem on some panels
            $file->move($photosDir, $filename);
        } catch (\Throwable $e) {
            \Log::error('Brand image move failed: ' . $e->getMessage(), [
                'php_user' => $this->currentPhpUser(),
                'photos_dir' => $photosDir,
            ]);
            return [null, $this->storagePermissionHint($photosDir, 'move failed: ' . $e->getMessage())];
        }

        if (!is_file($absolute)) {
            return [null, $this->storagePermissionHint($photosDir, 'file missing after move')];
        }

        @chmod($absolute, 0664);

        return [$relative, null];
    }

    protected function currentPhpUser(): string
    {
        if (function_exists('posix_geteuid') && function_exists('posix_getpwuid')) {
            $info = @posix_getpwuid(posix_geteuid());
            if (!empty($info['name'])) {
                return $info['name'];
            }
        }

        return get_current_user() ?: (string) getmyuid();
    }

    protected function storagePermissionHint(string $photosDir, string $reason): string
    {
        $phpUser = $this->currentPhpUser();
        $siteRoot = base_path();

        return "Could not save image ({$reason}). "
            . "PHP runs as [{$phpUser}]; target [{$photosDir}]. "
            . "On the VPS run exactly: "
            . "cd {$siteRoot} && "
            . "sudo chown -R {$phpUser}:{$phpUser} storage bootstrap/cache && "
            . "sudo chmod -R ug+rwX storage bootstrap/cache";
    }

    // update wensite information
    public function updatewebinfo(Request $request)
    {
        $this->validate($request, [
            // max is kilobytes — 500 was rejecting normal logo files
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'favicon' => 'nullable|file|mimes:jpg,jpeg,png,ico,webp,gif|max:2048',
        ]);

        $settings = Settings::where('id', '=', '1')->firstOrFail();

        $path = $settings->logo;
        if ($request->hasFile('logo')) {
            [$path, $error] = $this->storePublicPhoto($request->file('logo'), $settings->logo);
            if ($error) {
                return redirect()->back()->withInput()->with('error', $error);
            }
        }

        $pathfav = $settings->favicon;
        if ($request->hasFile('favicon')) {
            [$pathfav, $error] = $this->storePublicPhoto($request->file('favicon'), $settings->favicon);
            if ($error) {
                return redirect()->back()->withInput()->with('error', $error);
            }
        }

        // Query builder bypasses casts — json_encode pwa_settings explicitly
        Settings::where('id', '1')
            ->update([
                'newupdate' => $request['update'],
                'site_name' => $request['site_name'],
                'description' => $request['description'],
                'keywords' => $request['keywords'],
                'timezone' => $request['timezone'],
                'site_title' => $request['site_title'],
                'install_type' => $request['install_type'],
                'logo' => $path,
                'merchant_key' => $request->merchant_key,
                'favicon' => $pathfav,
                'site_address' => $request['site_address'],
                'welcome_message' => $request->welcome_message,
                'whatsapp' => $request->whatsapp,
                'tido' => $request->tido,
                'address' => $request->address,
                'sms' => $request->sms,
                'card_fee' => $request->card_fee,
                'pwa_settings' => json_encode([
                    'short_name' => $request['pwa_short_name'],
                    'description' => $request['pwa_description'],
                    'theme_color' => $request['pwa_theme_color'],
                    'background_color' => $request['pwa_background_color'],
                    'display' => $request['pwa_display'],
                    'orientation' => $request['pwa_orientation'],
                    'start_url' => $request['pwa_start_url'],
                    'enabled' => $request['pwa_enabled'] ?? false,
                ]),
            ]);

        // purchase_code field is commented out in the form — never crash the save
        $moreset = SettingsCont::find(1);
        if ($moreset && $request->exists('purchase_code')) {
            $moreset->purchase_code = $request->purchase_code;
            $moreset->save();
        }

        $uploaded = [];
        if ($request->hasFile('logo')) {
            $uploaded[] = 'logo';
        }
        if ($request->hasFile('favicon')) {
            $uploaded[] = 'favicon';
        }

        $message = empty($uploaded)
            ? 'Settings Saved successfully'
            : 'Settings Saved successfully (' . implode(' & ', $uploaded) . ' updated)';

        return redirect()->back()->with('success', $message);
    }



    public function updatepreference(Request $request)
    {

        if ($request->return_capital == 'true') {
            $return_capital = true;
        } else {
            $return_capital = false;
        }

        Settings::where('id', 1)->update([
            'contact_email' => $request['contact_email'],
            'currency' => $request['currency'],
            's_currency' => $request['s_currency'],
            'weekend_trade' => $request['weekend_trade'],
            'location' => $request['location'],
            'trade_mode' => $request['trade_mode'],
            'enable_verification' => $request['enail_verify'],
            'google_translate' => $request['googlet'],
            'enable_kyc' => $request['enable_kyc'],
            'enable_kyc_registration' => $request['enable_kyc_registration'],
            'captcha' => $request['captcha'],
            'enable_with' => $request['withdraw'],
            'return_capital' => $return_capital,
            'enable_social_login' => $request['social'],
            'enable_annoc' => $request['annouc'],
            'redirect_url' => $request->redirect_url,
            'should_cancel_plan' => $request->should_cancel_plan,
            'enable_crypto_transfer' => $request['enable_crypto_transfer'],
        ]);
        return response()->json(['status' => 200, 'success' => 'Settings Saved successfully']);
    }

    // Update email preference
    public function updateemail(Request $request)
    {
        Settings::where('id', ' 1')
            ->update([
                'mail_server' => $request['server'],
                'emailfrom' => $request['emailfrom'],
                'emailfromname' => $request['emailfromname'],
                'smtp_host' => $request['smtp_host'],
                'smtp_port' => $request['smtp_port'],
                'smtp_encrypt' => $request['smtp_encrypt'],
                'smtp_user' => $request['smtp_user'],
                'smtp_password' => $request['smtp_password'],
                'google_id' => $request['google_id'],
                'google_secret' => $request['google_secret'],
                'google_redirect' => $request['google_redirect'],
                'capt_secret' => $request['capt_secret'],
                'capt_sitekey' => $request['capt_sitekey'],
            ]);
        return response()->json(['status' => 200, 'success' => 'Settings Saved successfully']);
        //return redirect()->back()->with('message', 'Action Sucessful');
    }

    /**
     * Generate PWA manifest.json dynamically
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function manifest()
    {
        $settings = Settings::first();
        $pwa = is_array($settings->pwa_settings) ? $settings->pwa_settings : json_decode($settings->pwa_settings ?? '{}', true);
        
        $manifest = [
            "name" => $settings->site_name,
            "short_name" => $pwa['short_name'] ?? substr($settings->site_name, 0, 12),
            "description" => $pwa['description'] ?? $settings->description,
            "start_url" => $pwa['start_url'] ?? "/dashboard",
            "scope" => "/",
            "display" => $pwa['display'] ?? "standalone",
            "orientation" => $pwa['orientation'] ?? "portrait",
            "background_color" => $pwa['background_color'] ?? "#0a1929",
            "theme_color" => $pwa['theme_color'] ?? "#c10e0e",
            "icons" => [
                [
                    "src" => asset('storage/app/public/' . $settings->logo),
                    "sizes" => "192x192",
                    "type" => "image/png",
                    "purpose" => "any maskable"
                ],
                [
                    "src" => asset('storage/app/public/' . $settings->logo),
                    "sizes" => "512x512",
                    "type" => "image/png",
                    "purpose" => "any maskable"
                ]
            ],
            "categories" => ["finance", "banking", "productivity"],
            "prefer_related_applications" => false,
            "screenshots" => []
        ];
        
        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'max-age=3600');
    }
}
