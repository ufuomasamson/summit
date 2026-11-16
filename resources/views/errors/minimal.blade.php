
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>@yield('title') - {{ $settings->site_name }}</title>
	<meta name="theme-color" content="#c10e0e">
	<link rel="shortcut icon" href="{{ branding_url($settings->favicon) }}" />

	<!-- Tailwind CSS -->
	<script src="https://cdn.tailwindcss.com"></script>

	<!-- Lucide Icons -->
	<script src="https://unpkg.com/lucide@latest"></script>

	<!-- Fonts: Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

	<script>
	tailwind.config = {
		theme: {
			extend: {
				colors: {
					primary: {
						50:  '#fdf2f2',
						100: '#fce4e4',
						200: '#f9c9c9',
						300: '#f49c9c',
						400: '#ec6262',
						500: '#c10e0e',
						600: '#a30c0c',
						700: '#870a0a',
						800: '#6e0808',
						900: '#580707',
					},
					surface: {
						100: '#FFFFFF',
						200: '#F9F9F9',
						300: '#DFE5E5',
						400: '#BFC9C9',
					},
				},
				fontFamily: {
					sans: ['Inter', 'system-ui', 'sans-serif'],
				},
			}
		}
	}
	</script>

	<style>
	:root {
		--color-bg-base: #FFFFFF;
		--color-bg-surface: #F9F9F9;
		--color-primary: #c10e0e;
		--color-primary-dark: #a30c0c;
		--color-text-primary: #151515;
		--color-text-secondary: #616161;
		--color-text-muted: #7A7A7A;
		--color-border: #E0E5E5;
		--color-status-error: #c10e0e;
	}

	* {
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	body {
		font-family: 'Inter', system-ui, sans-serif;
		background: #F9F9F9;
		min-height: 100vh;
		letter-spacing: -0.011em;
	}

	.error-card {
		background: #FFFFFF;
		border: 1px solid var(--color-border);
		box-shadow: 0 8px 32px rgba(13, 27, 35, 0.06), 0 2px 8px rgba(13, 27, 35, 0.04);
	}

	.error-button {
		background: var(--color-primary);
		transition: background 0.2s ease;
	}

	.error-button:hover {
		background: var(--color-primary-dark);
	}
	</style>
</head>

<body class="antialiased overflow-x-hidden">
	<div class="min-h-screen flex items-center justify-center p-4">
		<div class="w-full max-w-md mx-auto">

			<!-- Error Card -->
			<div class="error-card rounded-xl p-8 md:p-10 space-y-6 text-center">

				<!-- Error Code -->
				<div>
					<span class="text-7xl md:text-8xl font-bold tracking-tight" style="color:#c10e0e;">
						@yield('code')
					</span>
				</div>

				<!-- Divider -->
				<div class="flex justify-center">
					<div class="w-16 h-1 rounded-full" style="background:#3e4e83;"></div>
				</div>

				<!-- Message -->
				<div class="space-y-2">
					<h2 class="text-xl font-semibold text-[#151515]">@yield('title')</h2>
					<p class="text-[#7A7A7A] text-sm leading-relaxed">@yield('message')</p>
				</div>

				<!-- Action Button -->
				<div class="pt-2">
					<a href="{{ url()->previous() }}"
					   class="error-button inline-flex items-center justify-center space-x-2 px-6 py-3 font-semibold text-white text-sm group" style="border-radius:0;">
						<i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
						<span>Go Back</span>
					</a>
				</div>

				<!-- Home Link -->
				<p class="text-xs text-[#7A7A7A]">
					or <a href="{{ url('/') }}" class="font-medium transition-colors" style="color:#c10e0e;">return to homepage</a>
				</p>
			</div>

			<!-- Footer -->
<p class="text-xs text-center mt-6" style="color:#616161;">
				&copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.
			</p>

		</div>
	</div>

	<!-- Initialize Lucide Icons -->
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}
		});
	</script>
</body>
</html>

