<template>
    <section v-if="isActive && testimonials.length > 0" class="py-16 bg-gradient-to-b from-white to-slate-50">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-10">
                <span class="inline-block text-xs font-semibold tracking-widest uppercase text-primary mb-2 opacity-80">
                    Opiniões reais
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                    O que nossos clientes dizem
                </h2>
                <div class="mx-auto w-12 h-1 rounded-full bg-primary opacity-70"></div>
            </div>

            <!-- Swiper Carousel -->
            <div class="relative testimonial-swiper-wrapper">
                <Swiper
                    :slides-per-view="slidesPerView"
                    :space-between="24"
                    :loop="testimonials.length > perPage"
                    :navigation="{ prevEl: '.t-prev', nextEl: '.t-next' }"
                    :breakpoints="{
                        0:   { slidesPerView: 1 },
                        640: { slidesPerView: Math.min(2, perPage) },
                        1024: { slidesPerView: perPage },
                    }"
                    :modules="modules"
                    class="px-1 py-4"
                >
                    <SwiperSlide v-for="item in testimonials" :key="item.id">
                        <div class="testimonial-card">
                            <!-- Quote icon -->
                            <div class="testimonial-quote">
                                <svg width="28" height="22" viewBox="0 0 28 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 22V13.4444C0 10.2963 0.703704 7.62963 2.11111 5.44444C3.51852 3.25926 5.7037 1.48148 8.66667 0.111111L10.5556 2.88889C8.96296 3.62963 7.72222 4.62963 6.83333 5.88889C5.94444 7.14815 5.37037 8.7037 5.11111 10.5556H10.5556V22H0ZM17.4444 22V13.4444C17.4444 10.2963 18.1481 7.62963 19.5556 5.44444C20.963 3.25926 23.1481 1.48148 26.1111 0.111111L28 2.88889C26.4074 3.62963 25.1667 4.62963 24.2778 5.88889C23.3889 7.14815 22.8148 8.7037 22.5556 10.5556H28V22H17.4444Z"
                                          fill="currentColor" class="text-primary opacity-15"/>
                                </svg>
                            </div>

                            <!-- Content -->
                            <p class="testimonial-content">
                                "{{ item.content }}"
                            </p>

                            <!-- Author -->
                            <div class="testimonial-author">
                                <div class="testimonial-avatar">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
                                </div>
                                <div class="min-w-0">
                                    <h4 class="testimonial-name">{{ item.name }}</h4>
                                    <div class="flex items-center gap-0.5 mt-0.5">
                                        <span v-for="star in 5" :key="star"
                                            :class="star <= item.rating ? 'text-yellow-400' : 'text-gray-200'"
                                            class="text-sm leading-none">&#9733;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </SwiperSlide>
                </Swiper>

                <!-- Navigation Buttons -->
                <button class="t-prev testimonial-nav-btn testimonial-nav-prev" aria-label="Anterior">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="t-next testimonial-nav-btn testimonial-nav-next" aria-label="Próximo">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>
</template>

<script>
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import activityEnum from '../../../enums/modules/activityEnum';

export default {
    name: 'TestimonialComponent',
    components: { Swiper, SwiperSlide },
    setup() {
        return { modules: [Navigation] };
    },
    computed: {
        testimonials() {
            return this.$store.getters['frontendTestimonial/lists'];
        },
        testimonialSetting() {
            return this.$store.getters['frontendTestimonial/setting'];
        },
        isActive() {
            return parseInt(this.testimonialSetting?.testimonials_section_status) === activityEnum.ENABLE;
        },
        perPage() {
            return parseInt(this.testimonialSetting?.testimonials_section_per_page) || 3;
        },
        slidesPerView() {
            return this.perPage;
        },
    },
    mounted() {
        this.$store.dispatch('frontendTestimonial/setting').then(() => {
            if (this.isActive) {
                this.$store.dispatch('frontendTestimonial/lists');
            }
        }).catch(() => {});
    },
};
</script>

<style scoped>
.testimonial-swiper-wrapper {
    padding: 0 44px;
}

.testimonial-card {
    position: relative;
    background: #fff;
    border-radius: 1rem;
    padding: 1.75rem 1.5rem 1.5rem;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.testimonial-card:hover {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transform: translateY(-3px);
}

.testimonial-quote {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
}

.testimonial-content {
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.7;
    flex: 1;
    margin-bottom: 1.25rem;
    font-style: italic;
    display: -webkit-box;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

.testimonial-avatar {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e2e8f0;
}

.testimonial-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.testimonial-verified {
    font-size: 0.75rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.testimonial-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #475569;
    font-size: 0.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: background 0.15s, color 0.15s, border-color 0.15s, box-shadow 0.15s;
    outline: none;
}

.testimonial-nav-btn:hover {
    background: var(--color-primary, #00b8a9);
    color: #fff;
    border-color: var(--color-primary, #00b8a9);
    box-shadow: 0 4px 12px rgba(0, 184, 169, 0.25);
}

.testimonial-nav-prev {
    left: 0;
}

.testimonial-nav-next {
    right: 0;
}

/* Disabled state */
.testimonial-nav-btn.swiper-button-disabled {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
}
</style>
