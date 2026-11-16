<div class="row">
    <div class="col-12">
        <form method="post" action="{{ route('updatewebinfo') }}" id="appinfoform" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class=" form-row">
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Website Name</h5>
                    <input type="text" name="site_name" class="form-control " value="{{ $settings->site_name }}"
                        required>
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">PWA Short Name (Max 12 characters)</h5>
                    <input type="text" name="pwa_short_name" maxlength="12" class="form-control " 
                        value="{{ $settings->pwa_short_name ?? substr($settings->site_name, 0, 12) }}" 
                        placeholder="e.g., MyBank">
                    <small class="text-muted">Used for app icon label on mobile devices</small>
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Website Title</h5>
                    <input type="text" name="site_title" class="form-control " value="{{ $settings->site_title }}"
                        required>
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Website Keywords</h5>
                    <input type="text" name="keywords" class="form-control " value="{{ $settings->keywords }}"
                        required>
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Website Url (https://yoursite.com)</h5>
                    <input type="text" placeholder="https://yoursite.com" name="site_address" class="form-control "
                        value="{{ $settings->site_address }}" required>
                </div>
                <!--<div class="form-group col-md-12">-->
                <!--    <h5 class="text-{{ $text }}">Website Description</h5>-->
                <!--    <textarea name="description" class="form-control " rows="4">{{ $settings->description }}</textarea>-->
                <!--</div>-->
            </div>

            <div class=" form-row">
                <!--<div class="form-group col-md-12">-->
                <!--    <h5 class="text-{{ $text }}">Announcement</h5>-->
                <!--    <textarea name="update" class="form-control " rows="2">{{ $settings->newupdate }}</textarea>-->
                <!--</div>-->
                <div class="form-group col-md-6">
                    <div class="form-group col-md-12">
                        <h5 class="text-{{ $text }}">Bank Address</h5>
                        <textarea name="address" class="form-control " rows="2">{{ $settings->address }}</textarea>
                    </div>
                    <!--<h5 class="text-{{ $text }}">Welcome messages for new registered users</h5>-->
                    <!--<textarea name="welcome_message" class="form-control " rows="2">{{ $settings->welcome_message }}</textarea>-->
                    <!--<small class="text-{{ $text }}">This message will be displayed to users whose registration-->
                    <!--    date is less than or equal to 3 days</small>-->
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">whatsapp number</h5>
                    <input name="whatsapp" class="form-control " type="text" value="{{ $settings->whatsapp }}">
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Tido livechat id</h5>
                    <input name="tido" class="form-control " type="text" value="{{ $settings->tido }}">
                </div>
                <!--<div class="form-group col-md-6">-->
                <!--    <h5 class="text-{{ $text }}">Purchase/License Code</h5>-->
                <!--    <input name="purchase_code" class="form-control " type="text"-->
                <!--        value="{{ $moresettings->purchase_code }}">-->
                <!--</div>-->
                <!--<div class="form-group col-md-6">-->
                <!--    <h5 class="text-{{ $text }}">Personal Access Token</h5>-->
                <!--    <input name="merchant_key" class="form-control " type="text"-->
                <!--        value="{{ $settings->merchant_key }}">-->
                <!--</div>-->
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Timezone</h5>
                    <select name="timezone" class="form-control  select2">
                        <option>{{ $settings->timezone }}</option>
                        @foreach ($timezones as $list)
                            <option value="{{ $list }}">{{ $list }}</option>
                        @endforeach
                    </select>
                    <div class="mt-4">
                        <h5 class="text-{{ $text }}">Installation Type</h5>
                        <select name="install_type" class="form-control ">
                            <option>{{ $settings->install_type }}</option>
                            <option>Main-Domain</option>
                            <option>Sub-Domain</option>
                            <option>Sub-Folder</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="form-group col-md-6 mb-3">
                <h5 class="">Turn On/Off SMS</h5>
                <div class="selectgroup">
                    <label class="selectgroup-item">
                        <input type="radio" name="sms" value="1" class="selectgroup-input"
                            {{ $settings->sms == '1' ? 'checked' : '' }}>
                        <span class="selectgroup-button">On</span>
                    </label>
                    <label class="selectgroup-item">
                        <input type="radio" name="sms" {{ $settings->sms != '1' ? 'checked' : '' }}
                            value="0" class="selectgroup-input">
                        <span class="selectgroup-button">Off</span>
                    </label>
                </div>
            </div>

            <!-- PWA Settings Section -->
            <div class="form-row">
                <div class="col-md-12 mt-4 mb-3">
                    <h4 class="text-{{ $text }} border-bottom pb-2">
                        <i class="fas fa-mobile-alt"></i> Progressive Web App (PWA) Settings
                    </h4>
                </div>

                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">PWA Description</h5>
                    <textarea name="pwa_description" class="form-control" rows="3" placeholder="What users will see when installing your banking app">{{ $settings->pwa_description ?? $settings->description }}</textarea>
                    <small class="text-muted">Displayed during app installation</small>
                </div>

                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">PWA Start URL</h5>
                    <input type="text" name="pwa_start_url" class="form-control " 
                        value="{{ $settings->pwa_start_url ?? '/dashboard' }}" 
                        placeholder="/dashboard">
                    <small class="text-muted">Where app opens when launched</small>
                </div>

                <div class="form-group col-md-3">
                    <h5 class="text-{{ $text }}">Theme Color</h5>
                    <input type="color" name="pwa_theme_color" class="form-control " 
                        value="{{ $settings->pwa_theme_color ?? '#2d8659' }}">
                    <small class="text-muted">Browser UI color (Forest Green)</small>
                </div>

                <div class="form-group col-md-3">
                    <h5 class="text-{{ $text }}">Background Color</h5>
                    <input type="color" name="pwa_background_color" class="form-control " 
                        value="{{ $settings->pwa_background_color ?? '#0a1929' }}">
                    <small class="text-muted">Splash screen background (Slate)</small>
                </div>

                <div class="form-group col-md-3">
                    <h5 class="text-{{ $text }}">Display Mode</h5>
                    <select name="pwa_display" class="form-control ">
                        <option value="standalone" {{ ($settings->pwa_display ?? 'standalone') == 'standalone' ? 'selected' : '' }}>Standalone (Fullscreen app)</option>
                        <option value="fullscreen" {{ ($settings->pwa_display ?? '') == 'fullscreen' ? 'selected' : '' }}>Fullscreen</option>
                        <option value="minimal-ui" {{ ($settings->pwa_display ?? '') == 'minimal-ui' ? 'selected' : '' }}>Minimal UI</option>
                        <option value="browser" {{ ($settings->pwa_display ?? '') == 'browser' ? 'selected' : '' }}>Browser</option>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <h5 class="text-{{ $text }}">Orientation</h5>
                    <select name="pwa_orientation" class="form-control ">
                        <option value="portrait" {{ ($settings->pwa_orientation ?? 'portrait') == 'portrait' ? 'selected' : '' }}>Portrait</option>
                        <option value="landscape" {{ ($settings->pwa_orientation ?? '') == 'landscape' ? 'selected' : '' }}>Landscape</option>
                        <option value="any" {{ ($settings->pwa_orientation ?? '') == 'any' ? 'selected' : '' }}>Any</option>
                    </select>
                </div>

                <div class="form-group col-md-6 mb-3">
                    <h5 class="text-{{ $text }}">Enable/Disable PWA</h5>
                    <div class="selectgroup">
                        <label class="selectgroup-item">
                            <input type="radio" name="pwa_enabled" value="1" class="selectgroup-input"
                                {{ ($settings->pwa_enabled ?? true) ? 'checked' : '' }}>
                            <span class="selectgroup-button">Enabled</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="pwa_enabled" value="0" class="selectgroup-input"
                                {{ !($settings->pwa_enabled ?? true) ? 'checked' : '' }}>
                            <span class="selectgroup-button">Disabled</span>
                        </label>
                    </div>
                    <small class="text-muted">Allow users to install your banking app on their devices</small>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Logo (PNG/JPG/WebP; max width ~200px, height ~100px; up to 4MB)</h5>
                    @if(!empty($settings->logo))
                        <div class="mb-2 p-3 rounded" style="background:#f5f5f5;display:inline-block;">
                            <img src="{{ branding_url($settings->logo) }}"
                                 alt="Current logo"
                                 style="max-height:64px;max-width:200px;object-fit:contain;">
                        </div>
                        <p class="text-muted small mb-2">Current logo — upload a new file below to replace it.</p>
                    @endif
                    <input name="logo" class="form-control " type="file" accept="image/png,image/jpeg,image/jpg,image/webp,image/gif">
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{ $text }}">Favicon (PNG/ICO recommended, ~32×32px; up to 2MB)</h5>
                    @if(!empty($settings->favicon))
                        <div class="mb-2 p-3 rounded" style="background:#f5f5f5;display:inline-block;">
                            <img src="{{ branding_url($settings->favicon) }}"
                                 alt="Current favicon"
                                 style="max-height:32px;max-width:32px;object-fit:contain;">
                        </div>
                    @endif
                    <input name="favicon" class="form-control " type="file" accept="image/png,image/jpeg,image/jpg,image/webp,image/gif,image/x-icon,.ico">
                </div>
            </div>


          
            <div class="mt-3 form-row">
                <div class="col-12">
                    <input type="submit" class="px-5 btn btn-primary btn-lg" value="Update">
                </div>

            </div>

        </form>
    </div>
</div>
