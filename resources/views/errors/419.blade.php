<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Session Expired - {{ $settings->site_name ?? 'Summit Bank' }}</title>
	<meta name="theme-color" content="#c10e0e">
	@if(isset($settings) && !empty($settings->favicon))
		<link rel="shortcut icon" href="{{ asset('storage/app/public/' . $settings->favicon) }}" />
	@endif

	<script src="https://cdn.tailwindcss.com"></script>
	<script src="https://unpkg.com/lucide@latest"></script>
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
						500: '#c10e0e',
						600: '#a30c0c',
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
	body {
		font-family: 'Inter', system-ui, sans-serif;
		background: #F9F9F9;
		min-height: 100vh;
		letter-spacing: -0.011em;
		-webkit-font-smoothing: antialiased;
	}
	.error-card {
		background: #FFFFFF;
		border: 1px solid #E0E5E5;
		box-shadow: 0 8px 32px rgba(13, 27, 35, 0.06), 0 2px 8px rgba(13, 27, 35, 0.04);
	}
	.error-button {
		background: #c10e0e;
		transition: background 0.2s ease;
	}
	.error-button:hover {
		background: #a30c0c;
	}
	</style>
</head>

<body class="antialiased overflow-x-hidden">
	<div class="min-h-screen flex items-center justify-center p-4">
		<div class="w-full max-w-md mx-auto">
			<div class="error-card rounded-xl p-8 md:p-10 space-y-6 text-center">
				<div class="flex justify-center">
					<div class="w-16 h-16 rounded-full flex items-center justify-center" style="background:#fdf2f2;border:1px solid #f9c9c9;">
						<i data-lucide="clock" class="w-8 h-8" style="color:#c10e0e;"></i>
					</div>
				</div>

				<div class="flex justify-center">
					<div class="w-16 h-1 rounded-full" style="background:#3e4e83;"></div>
				</div>

				<div class="space-y-2">
					<h2 class="text-xl font-semibold text-[#151515]">Session Expired</h2>
					<p class="text-[#7A7A7A] text-sm leading-relaxed">
						Your session timed out for security. Please go back and try again, or refresh the page and sign in if needed.
					</p>
				</div>

				<div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
					<a href="{{ url()->previous() }}"
					   class="error-button inline-flex items-center justify-center space-x-2 px-6 py-3 font-semibold text-white text-sm group" style="border-radius:0;">
						<i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
						<span>Go Back</span>
					</a>
					<a href="javascript:location.reload()"
					   class="inline-flex items-center justify-center space-x-2 px-6 py-3 font-semibold text-sm border border-[#E0E5E5] bg-white text-[#151515] hover:bg-[#F9F9F9]" style="border-radius:0;">
						<i data-lucide="refresh-cw" class="w-4 h-4"></i>
						<span>Refresh Page</span>
					</a>
				</div>

				<p class="text-xs text-[#7A7A7A]">
					or <a href="{{ url('/') }}" class="font-medium transition-colors" style="color:#c10e0e;">return to homepage</a>
				</p>
			</div>

			<p class="text-xs text-center mt-6" style="color:#616161;">
				&copy; {{ date('Y') }} {{ $settings->site_name ?? 'Summit Bank' }}. All rights reserved.
			</p>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}
		});
	</script>
</body>
</html>
