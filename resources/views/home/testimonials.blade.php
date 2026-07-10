@php
    $testimonials = [
        ['name' => 'Dr. Amaka Okafor', 'role' => 'Medical Director', 'org' => "St. Raphael's Specialist Hospital, Lagos", 'text' => "Cadical has transformed how we procure medical equipment. The institutional portal is seamless — we raised a purchase order and had our ultrasound machines delivered in 48 hours. Exceptional service.", 'rating' => 5],
        ['name' => 'Engr. Taiwo Balogun', 'role' => 'Biomedical Engineer', 'org' => 'University College Hospital, Ibadan', 'text' => 'Their maintenance engineers are genuinely knowledgeable. We had a Philips monitor down at 2am and their team was on-site by morning. That kind of reliability is rare in Nigeria.', 'rating' => 5],
        ['name' => 'Mrs. Ngozi Eze', 'role' => 'Head of Procurement', 'org' => 'Redeemed Healthcare Centre, Abuja', 'text' => "We've used three other suppliers before Cadical. None of them offered the level of after-sales support and product certification documentation that Cadical provides as standard.", 'rating' => 5],
        ['name' => 'Dr. Emmanuel Adeyemi', 'role' => 'Lab Director', 'org' => 'Synlab Nigeria, Lagos', 'text' => 'The diagnostic equipment we sourced through Cadical has been performing flawlessly for over a year. The calibration certificates and service reports were impeccable.', 'rating' => 5],
    ];
@endphp
<section class="py-20 px-4 md:px-8 bg-slate-50">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-cadical-500 text-xs font-semibold uppercase tracking-widest mb-3">Testimonials</p>
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900">What healthcare professionals say</h2>
        </div>

        <div x-data="{
            items: {{ Illuminate\Support\Js::from($testimonials) }},
            current: 0, perView: window.innerWidth >= 768 ? 2 : 1,
            init() {
                window.addEventListener('resize', () => this.perView = window.innerWidth >= 768 ? 2 : 1);
                setInterval(() => this.current = (this.current + 1) % this.items.length, 6000);
            }
        }">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * (100 / perView)}%)`">
                    <template x-for="(t, i) in items" :key="i">
                        <div class="px-3" :style="`flex: 0 0 ${100 / perView}%`">
                            <div class="bg-white border border-slate-100 rounded-2xl p-6 h-full shadow-sm">
                                <i data-lucide="quote" class="w-7 h-7 text-blue-100 mb-3"></i>
                                <div class="flex gap-0.5 mb-4">
                                    <template x-for="n in t.rating" :key="n">
                                        <i data-lucide="star" class="w-[13px] h-[13px] text-accent fill-accent"></i>
                                    </template>
                                </div>
                                <p class="text-slate-700 text-sm leading-relaxed mb-5 italic" x-text="'“' + t.text + '”'"></p>
                                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                                    <div class="w-10 h-10 rounded-full bg-cadical-500/10 flex items-center justify-center text-cadical-500 font-bold text-sm flex-shrink-0" x-text="t.name.charAt(0)"></div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900" x-text="t.name"></div>
                                        <div class="text-xs text-slate-500" x-text="t.role + ', ' + t.org"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-center gap-2 mt-6">
                <template x-for="(t, i) in items" :key="'dot-'+i">
                    <button @click="current = i" class="rounded-full transition-all duration-300" :class="current === i ? 'bg-cadical-500 w-5 h-2' : 'bg-slate-300 w-2 h-2'"></button>
                </template>
            </div>
        </div>
    </div>
</section>
