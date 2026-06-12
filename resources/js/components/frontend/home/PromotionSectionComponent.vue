<template>
    <LoadingComponent :props="loading" />

    <template v-for="section in sections" :key="section.id">
        <section v-if="section.promotions && section.promotions.length" class="mb-10 sm:mb-20">
            <div class="container">
                <h2 v-if="section.name" class="text-2xl sm:text-3xl font-bold mb-6">{{ section.name }}</h2>

                <!-- Carrossel -->
                <Swiper
                    v-if="section.layout_type === 'carousel'"
                    dir="ltr"
                    :speed="800"
                    :loop="section.promotions.length > 2"
                    :navigation="true"
                    :modules="modules"
                    :breakpoints="{
                        0:   { slidesPerView: 1, spaceBetween: 12 },
                        480: { slidesPerView: 2, spaceBetween: 16 },
                        768: { slidesPerView: 3, spaceBetween: 20 },
                    }"
                    class="navigate-swiper"
                >
                    <SwiperSlide v-for="promo in section.promotions" :key="promo.id">
                        <a v-if="promo.link_type === 'custom'" :href="promo.link_url" target="_blank" rel="noopener" class="block w-full">
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </a>
                        <router-link
                            v-else-if="promo.link_type === 'category'"
                            :to="{ name: 'frontend.product', query: { category: promo.link_url } }"
                            class="block w-full"
                        >
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </router-link>
                        <router-link
                            v-else
                            :to="{ name: 'frontend.promotion.products', params: { slug: promo.slug } }"
                            class="block w-full"
                        >
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </router-link>
                    </SwiperSlide>
                </Swiper>

                <!-- Grade -->
                <div
                    v-else
                    class="grid gap-4 sm:gap-6"
                    :style="{ gridTemplateColumns: `repeat(${section.columns || 3}, minmax(0, 1fr))` }"
                >
                    <template v-for="promo in section.promotions" :key="promo.id">
                        <a v-if="promo.link_type === 'custom'" :href="promo.link_url" target="_blank" rel="noopener" class="block w-full">
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </a>
                        <router-link
                            v-else-if="promo.link_type === 'category'"
                            :to="{ name: 'frontend.product', query: { category: promo.link_url } }"
                            class="block w-full"
                        >
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </router-link>
                        <router-link
                            v-else
                            :to="{ name: 'frontend.promotion.products', params: { slug: promo.slug } }"
                            class="block w-full"
                        >
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </router-link>
                    </template>
                </div>
            </div>
        </section>
    </template>
</template>

<script>
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "PromotionSectionComponent",
    components: { Swiper, SwiperSlide, LoadingComponent },
    setup() {
        return { modules: [Navigation, Pagination, Autoplay] };
    },
    data() {
        return { loading: { isActive: false } };
    },
    computed: {
        sections: function () {
            return this.$store.getters["frontendPromotionSection/sections"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendPromotionSection/sections").then(() => {
            this.loading.isActive = false;
        }).catch(() => {
            this.loading.isActive = false;
        });
    },
}
</script>
