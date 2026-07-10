@php
    $footerLinks = [
        'Products' => [
            ['label' => 'All Products', 'href' => '/products'],
            ['label' => 'Imaging', 'href' => '/products?category=Imaging'],
            ['label' => 'Diagnostics', 'href' => '/products?category=Diagnostics'],
            ['label' => 'ICU Equipment', 'href' => '/products?category=ICU'],
            ['label' => 'Surgical', 'href' => '/products?category=Surgery'],
            ['label' => 'Consumables', 'href' => '/products?category=Consumables'],
        ],
        'Services' => [
            ['label' => 'Equipment Repair', 'href' => '/booking'],
            ['label' => 'Maintenance Plans', 'href' => '/booking'],
            ['label' => 'Calibration', 'href' => '/booking'],
            ['label' => 'Supply Consultation', 'href' => '/booking'],
        ],
        'Company' => [
            ['label' => 'About', 'href' => '/about'],
            ['label' => 'Contact', 'href' => '/contact'],
            ['label' => 'Referrals', 'href' => '/referrals'],
            ['label' => 'Become Supplier', 'href' => '/auth/register'],
        ],
        'Legal' => [
            ['label' => 'Privacy Policy', 'href' => '/privacy-policy'],
            ['label' => 'Terms of Use', 'href' => '/terms'],
        ],
    ];
    $socials = [
        ['src' => 'images/instagram.png', 'href' => 'https://www.instagram.com/cadicalsolutions', 'alt' => 'Instagram'],
        ['src' => 'images/twitter.png', 'href' => 'https://x.com/CadicalSolution', 'alt' => 'X (Twitter)'],
        ['src' => 'images/linkedin.png', 'href' => 'https://www.linkedin.com/company/cadical-solutions/', 'alt' => 'LinkedIn'],
        ['src' => 'images/facebook.png', 'href' => 'https://www.facebook.com/share/1CMA1c1Czi/', 'alt' => 'Facebook'],
    ];
@endphp
<footer class="bg-slate-900 text-slate-400">
    <div class="max-w-7xl mx-auto px-4 md:px-8 pt-16 pb-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 pb-12 border-b border-slate-800">
            <div class="col-span-2 md:col-span-1">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Cadical" class="w-8 h-8 rounded-lg">
                    <div>
                        <div class="text-white text-sm font-bold">Cadical Solutions</div>
                        <div class="text-[10px] text-slate-500">Right Supply. Right Time.</div>
                    </div>
                </a>
                <p class="text-sm leading-relaxed mb-5">Nigeria's trusted medical equipment supply and service platform.</p>
                <div class="flex gap-3">
                    @foreach ($socials as $s)
                        <a href="{{ $s['href'] }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors">
                            <img src="{{ asset($s['src']) }}" alt="{{ $s['alt'] }}" class="w-4 h-4 opacity-70 hover:opacity-100 transition-opacity">
                        </a>
                    @endforeach
                </div>
            </div>

            @foreach ($footerLinks as $group => $items)
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">{{ $group }}</h4>
                    <ul class="space-y-2.5">
                        @foreach ($items as $item)
                            <li><a href="{{ url($item['href']) }}" class="text-sm hover:text-white transition-colors">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} Cadical Solutions Limited. All rights reserved. RC 8969474</p>
            <div class="flex gap-4">
                <a href="{{ url('/privacy-policy') }}" class="hover:text-white transition-colors">Privacy</a>
                <a href="{{ url('/terms') }}" class="hover:text-white transition-colors">Terms</a>
            </div>
        </div>
    </div>
</footer>
