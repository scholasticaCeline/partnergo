@push('styles')
    <link href="{{ asset('css/landingpage.css') }}" rel="stylesheet">
@endpush

@extends('layout.app')

@section('content')
    <div class="wrapper">
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">
                        The <span class="highlight">Easiest</span> Way<br>
                        to Find a <span class="highlight">Business<br>
                        Partner</span>
                    </h1>
                    <div class="hero-divider"></div>
                    <p class="hero-text">
                        Partnerships are the fastest way to grow, but finding the right one is hard. 
                        Our app matches you with aligned businesses based on goals, audience, 
                        and value. Start building smart, strategic collaborations—without the guesswork.
                    </p>
                    <a href="#" class="cta-button">Get started</a>
                    
                    <div class="stats-container">
                        <div class="stat-box">
                            <i class="fas fa-music stat-icon"></i>
                            <h3 class="stat-number">1200+</h3>
                            <p class="stat-text">Partnerships<br>made</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-music stat-icon"></i>
                            <h3 class="stat-number">50+</h3>
                            <p class="stat-text">Organizations</p>
                        </div>
                        <div class="stat-box">
                            <i class="fas fa-music stat-icon"></i>
                            <h3 class="stat-number">< 24 h</h3>
                            <p class="stat-text">Response<br>time</p>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="image-container">
                        <img src={{ asset('assets/') }} alt="Hero Image" class="hero-image">
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">How PartnerGO Works</h2>
                <div class="steps-container">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Create Your Profile</h3>
                        <p class="step-text">Tell us about your business, goals, audience, and what you're looking for in a partner.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3 class="step-title">Get Matched</h3>
                        <p class="step-text">Our algorithm finds businesses that align with your goals and complement your strengths.</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Connect & Collaborate</h3>
                        <p class="step-text">Reach out, discuss opportunities, and start building successful partnerships.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials">
            <div class="container">
                <h2 class="section-title">Success Stories</h2>
                <div class="testimonials-container">
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p class="testimonial-text">"PartnerGO connected us with the perfect marketing agency that understood our industry. Our collaboration increased our revenue by 40% in just 6 months."</p>
                            <div class="testimonial-author">
                                <div class="author-image"></div>
                                <div class="author-info">
                                    <h4 class="author-name">Sarah Johnson</h4>
                                    <p class="author-position">CEO, TechStart Inc.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <p class="testimonial-text">"Finding the right manufacturing partner was a game-changer for our product launch. PartnerGO made it happen in weeks instead of months."</p>
                            <div class="testimonial-author">
                                <div class="author-image"></div>
                                <div class="author-info">
                                    <h4 class="author-name">Michael Chen</h4>
                                    <p class="author-position">Founder, InnoGoods</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Partnership Types Section -->
        <section class="partnership-types">
            <div class="container">
                <h2 class="section-title">Find Partners For</h2>
                <div class="types-container">
                    <div class="type-card">
                        <div class="type-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="type-title">Strategic Alliances</h3>
                        <p class="type-text">Join forces with complementary businesses to expand market reach.</p>
                    </div>
                    <div class="type-card">
                        <div class="type-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="type-title">Co-Marketing</h3>
                        <p class="type-text">Create joint campaigns to reach new audiences and share costs.</p>
                    </div>
                    <div class="type-card">
                        <div class="type-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="type-title">Product Integration</h3>
                        <p class="type-text">Combine products or services to create more value for customers.</p>
                    </div>
                    <div class="type-card">
                        <div class="type-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="type-title">Distribution Channels</h3>
                        <p class="type-text">Find partners to help distribute your products to new markets.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">Ready to Find Your Perfect Business Partner?</h2>
                    <p class="cta-text">Join thousands of businesses already growing through strategic partnerships.</p>
                    <a href="#" class="cta-button">Get started for free</a>
                </div>
            </div>
        </section>
    </div>
@endsection
