@extends('layout.appLayout');

@section('content')
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Brand Eyewear — See the world as it was meant to be seen</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
            rel="stylesheet">

        <!-- Styles & Scripts (Tailwind CSS v4 & Laravel Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
            }

            /* Smooth transitions for accordion */
            .accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            }

            .accordion-item.active .accordion-content {
                max-height: 200px;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .accordion-item.active .accordion-icon {
                transform: rotate(45deg);
            }
        </style>
    </head>

    <body class="bg-[#FDFDFC] text-[#1b1b18] min-h-screen flex flex-col antialiased">


        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="w-full pt-4 pb-16">
                <div
                    class="bg-[#0b0b0a] text-white overflow-hidden min-h-[560px] flex flex-col-reverse lg:flex-row items-center justify-between p-8 lg:p-20 relative">
                    <!-- Subtle background light effect -->
                    <div
                        class="absolute top-0 right-0 w-[400px] h-[400px] bg-white/5 rounded-full filter blur-[100px] pointer-events-none">
                    </div>

                    <!-- Text details -->
                    <div class="w-full lg:w-[48%] mt-8 lg:mt-0 flex flex-col justify-center z-10">
                        <h1
                            class="text-4xl sm:text-5xl lg:text-6xl font-semibold leading-tight tracking-tight max-w-lg mb-6">
                            See the world the way you want
                        </h1>
                        <p class="text-gray-400 text-base sm:text-lg leading-relaxed max-w-md mb-8">
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit. Omnis dicta ullam fugit dolorem ab
                            totam sunt, debitis nesciunt suscipit dolorum!
                        </p>
                        <div>
                            <a href="#products"
                                class="inline-flex items-center justify-center px-8 py-3.5 border border-white rounded-full text-sm font-medium hover:bg-white hover:text-black transition-all duration-300 transform active:scale-95">
                                Explore Collection
                            </a>
                        </div>
                    </div>

                    <!-- Sunglasses Hero Image -->
                    <div class="w-full lg:w-[48%] flex justify-center items-center z-10">
                        <div class="relative w-full max-w-[480px] hover:scale-102 transition-transform duration-500">
                            <img src="./images/hero_sunglasses.png" alt="Premium Elegant Sunglasses"
                                class="w-full object-contain filter drop-shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Product Grid Section -->
            <section id="products" class="max-w-7xl mx-auto px-6 py-16">
                <!-- Section Title -->
                <div class="text-center max-w-xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-semibold tracking-tight text-[#1b1b18] mb-4">
                        Discover your signature look
                    </h2>
                    <p class="text-[#706f6c] text-sm sm:text-base leading-relaxed">
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aperiam iure illo numquam ea eos!
                        Inventore?
                    </p>
                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product->id) }}" target="blank"
                            class="group border border-[#19140010] bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 relative">

                            @include('partials.whishlistButton', [
                                'productId' => $product->id,
                            ])

                            <div
                                class="aspect-square bg-gray-50 rounded-xl overflow-hidden mb-6 flex items-center justify-center p-4 relative">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }} Glasses"
                                    class="max-h-full object-contain transition-transform duration-500 group-hover:scale-108">
                            </div>
                            <h3 class="text-lg font-semibold mb-1 text-center group-hover:text-black transition-colors">
                                {{ $product->name }}</h3>
                            <p class="text-xs text-[#706f6c] text-center leading-relaxed max-w-[210px] mx-auto mb-2">
                                {{ $product->description }}
                            </p>
                            <p class="text-sm font-bold text-center text-[#1b1b18]">
                                ${{ number_format($product->price, 2) }}
                            </p>
                        </a>
                    @endforeach
                </div>

                <!-- Discover More Link -->
                <div class="text-center mt-12">
                    <a href="/products"
                        class="inline-flex items-center space-x-1.5 text-sm font-semibold border-b border-[#1b1b18] pb-1 hover:opacity-75 transition-opacity">
                        <span>Discover more</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </section>

            <!-- Video/Innovation Section -->
            <section id="customize" class="max-w-7xl mx-auto px-6 py-16">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left: Video Preview Cover -->
                    <div
                        class="relative rounded-[24px] overflow-hidden bg-gray-100 group shadow-md aspect-[4/3] max-w-2xl mx-auto lg:mx-0 w-full">
                        <img src="{{ asset('images/innovation_glasses.png') }}" alt="Innovation Glasses detailing"
                            class="w-full h-full object-cover">
                        <!-- Tint Overlay -->
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors duration-300">
                        </div>
                        <!-- Play Button Modal Trigger -->
                        <button id="open-video-btn"
                            class="absolute inset-0 m-auto w-16 h-16 rounded-full bg-white text-black flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 transition-all duration-300 z-10"
                            aria-label="Play Concept Video">
                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Right: Text Accordion content -->
                    <div class="flex flex-col justify-center">
                        <h2 class="text-3xl sm:text-4xl font-semibold tracking-tight text-[#1b1b18] mb-4">
                            Elevating vision through innovation
                        </h2>
                        <p class="text-[#706f6c] text-sm sm:text-base leading-relaxed mb-8">
                            Designed to elevate everyday moments with clarity, comfort, and a look that effortlessly stands
                            out.
                        </p>

                        <!-- Minimal Info Dividers -->
                        <div class="border-t border-[#19140015] py-4">
                            <div class="flex justify-between items-center text-[#1b1b18] font-medium text-sm sm:text-base">
                                <span>Dynamic focus adjustment</span>
                            </div>
                        </div>
                        <div class="border-t border-[#19140015] py-4">
                            <div class="flex justify-between items-center text-[#1b1b18] font-medium text-sm sm:text-base">
                                <span>Personalized visual tuning</span>
                            </div>
                        </div>
                        <div class="border-t border-[#19140015] py-4 border-b">
                            <div class="flex justify-between items-center text-[#1b1b18] font-medium text-sm sm:text-base">
                                <span>Adaptive light filtering</span>
                            </div>
                        </div>

                        <div class="mt-8">
                            {{-- <a href="#reserve"
                                class="inline-flex items-center justify-center px-8 py-3.5 bg-[#0b0b0a] text-white rounded-full text-sm font-medium hover:bg-black/90 transition-colors transform active:scale-95">
                                Reserve now
                            </a> --}}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Video Modal Lightbox -->
            <div id="video-modal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="relative w-full max-w-4xl bg-black rounded-2xl overflow-hidden aspect-video shadow-2xl">
                    <!-- Close button -->
                    <button id="close-video-btn"
                        class="absolute top-4 right-4 text-white hover:text-gray-300 bg-black/40 p-2 rounded-full z-10 transition-colors"
                        aria-label="Close Video">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <!-- Stock Video Player -->
                    <video id="modal-player" class="w-full h-full object-cover" controls preload="none">
                        <source
                            src="https://assets.mixkit.co/videos/preview/mixkit-fashion-woman-with-silver-glasses-profile-40082-large.mp4"
                            type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            <!-- More Features Section -->
            <section id="lenses" class="max-w-7xl mx-auto px-6 py-16">
                <!-- Section Title -->
                <div class="mb-12">
                    <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-[#1b1b18]">
                        More features
                    </h2>
                </div>

                <!-- Feature Three-Column Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1: Smart Design -->
                    <div>
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100 mb-6 relative">
                            <img src="https://atstkhkmxhxvqxhdzgnx.supabase.co/storage/v1/object/public/products-images/products/BoDD6j7r40ECAhmNCymKpyO6WhhZ7rvG0c9yFm7v.png"
                                alt="Smart Design transparency frames" class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-lg font-semibold text-[#1b1b18] mb-3">Smart Design</h3>
                        <p class="text-xs text-[#706f6c] leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia tenetur explicabo cupiditate?
                            Corrupti architecto voluptatibus, explicabo tempora sit quidem ipsum nihil blanditiis veniam,
                            cupiditate inventore.
                        </p>
                    </div>

                    <!-- Feature 2: Elegant Style -->
                    <div>
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100 mb-6 relative">
                            <img src="https://atstkhkmxhxvqxhdzgnx.supabase.co/storage/v1/object/public/products-images/products/3tKwYYS8oPvBK3uqwjhpAALfTXEhF7RWl6Er28vE.jpg"
                                alt="Elegant Style profile views" class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-lg font-semibold text-[#1b1b18] mb-3">Elegant Style</h3>
                        <p class="text-xs text-[#706f6c] leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia tenetur explicabo cupiditate?
                            Corrupti architecto voluptatibus, explicabo tempora sit quidem ipsum nihil blanditiis veniam,
                            cupiditate inventore.
                        </p>
                    </div>

                    <!-- Feature 3: Clarity refined -->
                    <div>
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100 mb-6 relative">
                            <img src="https://atstkhkmxhxvqxhdzgnx.supabase.co/storage/v1/object/public/products-images/products/51CXPbm9qnOfna6mLMPYjj3xQbHGvdnx0wGxiKeK.jpg"
                                alt="Clarity Refined lens process" class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-lg font-semibold text-[#1b1b18] mb-3">Clarity refined</h3>
                        <p class="text-xs text-[#706f6c] leading-relaxed">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia tenetur explicabo cupiditate?
                            Corrupti architecto voluptatibus, explicabo tempora sit quidem ipsum nihil blanditiis veniam,
                            cupiditate inventore.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Testimonial Section -->
            <section class="bg-gray-50 border-y border-[#19140008] py-20 px-6">
                <div class="max-w-4xl mx-auto text-center">
                    <p
                        class="text-2xl sm:text-3xl lg:text-4xl font-normal leading-relaxed text-[#1b1b18] tracking-tight mb-8">
                        "These glasses feel incredibly light, but the clarity is unreal. I didn't expect such a big
                        difference until I tried them."
                    </p>
                    <cite class="text-[#706f6c] not-italic text-sm sm:text-base font-medium">
                        — Customer
                    </cite>
                </div>
            </section>

            <!-- Newsletter Subscription Card Section -->
            <section class="max-w-7xl mx-auto px-6 py-16">
                <div id="newsletter-card"
                    class="bg-[#0b0b0a] text-white rounded-[32px] p-8 sm:p-12 lg:p-20 text-center relative overflow-hidden transition-all duration-500">
                    <div class="absolute inset-0 bg-radial-gradient from-white/5 to-transparent pointer-events-none"></div>

                    <!-- Form State -->
                    <div id="newsletter-content" class="relative z-10 max-w-xl mx-auto">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold tracking-tight leading-tight mb-8">
                            Be the first to discover new styles and releases !
                        </h2>

                        <form id="newsletter-form"
                            class="flex flex-col sm:flex-row items-stretch justify-center gap-4 max-w-md mx-auto">
                            <div class="flex-grow relative">
                                <input type="email" placeholder="Enter your email" required
                                    class="w-full bg-transparent border-b border-white/40 focus:border-white py-3 px-1 text-sm outline-none transition-colors text-white placeholder-gray-500"
                                    aria-label="Email Address">
                            </div>
                            <button type="submit"
                                class="bg-white text-black px-6 py-3 rounded-full text-xs font-bold tracking-widest hover:bg-gray-200 transition-colors uppercase active:scale-95 transform">
                                Subscribe
                            </button>
                        </form>
                    </div>

                    <!-- Success State (Hidden by default) -->
                    <div id="newsletter-success" class="hidden relative z-10 max-w-md mx-auto py-4">
                        <svg class="w-16 h-16 text-emerald-400 mx-auto mb-6" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-2xl font-semibold mb-2">Thank you!</h3>
                        <p class="text-gray-400 text-sm">
                            You've been successfully subscribed. We'll be in touch with updates and exclusive preview
                            releases.
                        </p>
                    </div>
                </div>
            </section>

            <!-- FAQs Section -->
            <section class="max-w-4xl mx-auto px-6 py-16">
                <!-- Section Title -->
                <div class="mb-12">
                    <h2 class="text-3xl font-semibold tracking-tight text-[#1b1b18]">FAQs</h2>
                </div>

                <!-- FAQs Accordion -->
                <div class="divide-y divide-[#19140015]">
                    <!-- FAQ Item 1 -->
                    <div class="accordion-item py-5">
                        <button
                            class="accordion-header w-full flex justify-between items-center text-left text-base sm:text-lg font-medium text-[#1b1b18] hover:opacity-85 transition-opacity"
                            aria-expanded="false">
                            <span>Are the lenses scratch-resistant?</span>
                            <span
                                class="accordion-icon text-2xl font-light leading-none transition-transform duration-300 text-[#706f6c]">+</span>
                        </button>
                        <div class="accordion-content">
                            <p class="text-sm text-[#706f6c] leading-relaxed">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, amet. Eligendi
                                reprehenderit numquam commodi minus possimus dolore cupiditate assumenda ex?
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="accordion-item py-5">
                        <button
                            class="accordion-header w-full flex justify-between items-center text-left text-base sm:text-lg font-medium text-[#1b1b18] hover:opacity-85 transition-opacity"
                            aria-expanded="false">
                            <span>Do the frames fit different face shapes?</span>
                            <span
                                class="accordion-icon text-2xl font-light leading-none transition-transform duration-300 text-[#706f6c]">+</span>
                        </button>
                        <div class="accordion-content">
                            <p class="text-sm text-[#706f6c] leading-relaxed">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, amet. Eligendi
                                reprehenderit numquam commodi minus possimus dolore cupiditate assumenda ex?
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="accordion-item py-5">
                        <button
                            class="accordion-header w-full flex justify-between items-center text-left text-base sm:text-lg font-medium text-[#1b1b18] hover:opacity-85 transition-opacity"
                            aria-expanded="false">
                            <span>Are the glasses water-resistant?</span>
                            <span
                                class="accordion-icon text-2xl font-light leading-none transition-transform duration-300 text-[#706f6c]">+</span>
                        </button>
                        <div class="accordion-content">
                            <p class="text-sm text-[#706f6c] leading-relaxed">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, amet. Eligendi
                                reprehenderit numquam commodi minus possimus dolore cupiditate assumenda ex?
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="accordion-item py-5">
                        <button
                            class="accordion-header w-full flex justify-between items-center text-left text-base sm:text-lg font-medium text-[#1b1b18] hover:opacity-85 transition-opacity"
                            aria-expanded="false">
                            <span>How do I clean the lenses properly?</span>
                            <span
                                class="accordion-icon text-2xl font-light leading-none transition-transform duration-300 text-[#706f6c]">+</span>
                        </button>
                        <div class="accordion-content">
                            <p class="text-sm text-[#706f6c] leading-relaxed">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Explicabo, amet. Eligendi
                                reprehenderit numquam commodi minus possimus dolore cupiditate assumenda ex?
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>



        <!-- Inline Interaction Javascript -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // 1. FAQs Accordion functionality
                const accordionHeaders = document.querySelectorAll('.accordion-header');
                accordionHeaders.forEach(header => {
                    header.addEventListener('click', () => {
                        const item = header.parentElement;
                        const isActive = item.classList.contains('active');

                        // Close all open items
                        document.querySelectorAll('.accordion-item').forEach(i => {
                            i.classList.remove('active');
                            i.querySelector('.accordion-header').setAttribute('aria-expanded',
                                'false');
                        });

                        // Toggle current item
                        if (!isActive) {
                            item.classList.add('active');
                            header.setAttribute('aria-expanded', 'true');
                        }
                    });
                });

                // 2. Video Modal lightbox functionality
                const videoModal = document.getElementById('video-modal');
                const openVideoBtn = document.getElementById('open-video-btn');
                const closeVideoBtn = document.getElementById('close-video-btn');
                const modalPlayer = document.getElementById('modal-player');

                if (openVideoBtn && videoModal && modalPlayer) {
                    openVideoBtn.addEventListener('click', () => {
                        videoModal.classList.remove('opacity-0', 'pointer-events-none');
                        videoModal.classList.add('opacity-100');
                        modalPlayer.play();
                    });

                    const closeVideo = () => {
                        videoModal.classList.add('opacity-0', 'pointer-events-none');
                        videoModal.classList.remove('opacity-100');
                        modalPlayer.pause();
                        modalPlayer.currentTime = 0;
                    };

                    closeVideoBtn.addEventListener('click', closeVideo);
                    videoModal.addEventListener('click', (e) => {
                        if (e.target === videoModal) {
                            closeVideo();
                        }
                    });

                    // Close on Escape key press
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !videoModal.classList.contains('opacity-0')) {
                            closeVideo();
                        }
                    });
                }

                // 3. Newsletter form success animation
                const newsletterForm = document.getElementById('newsletter-form');
                const newsletterContent = document.getElementById('newsletter-content');
                const newsletterSuccess = document.getElementById('newsletter-success');
                const newsletterCard = document.getElementById('newsletter-card');

                if (newsletterForm) {
                    newsletterForm.addEventListener('submit', (e) => {
                        e.preventDefault();

                        // Animate card size change and content transition
                        newsletterContent.classList.add('opacity-0');

                        setTimeout(() => {
                            newsletterContent.classList.add('hidden');
                            newsletterSuccess.classList.remove('hidden');
                            // Trigger fade in for success content
                            setTimeout(() => {
                                newsletterSuccess.classList.add('opacity-100');
                            }, 50);
                        }, 300);
                    });
                }
            });
        </script>
    </body>

    </html>
@endsection
