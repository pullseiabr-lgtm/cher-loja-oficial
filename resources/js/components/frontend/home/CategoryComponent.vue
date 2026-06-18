<template>
    <LoadingComponent :props="loading" />

    <template v-for="section in categorySections" :key="section.id">

        <!-- Tipo: Categorias -->
        <section v-if="section.type === 'categories' && section.categories && section.categories.length > 0" class="sm:mb-10">
            <div class="container">
                <component
                    :is="titleTag(section)"
                    :class="['font-bold -mb-10', titleSizeClass(section), titleAlignClass(section)]"
                >{{ section.name }}</component>
                <Swiper
                    dir="ltr"
                    :speed="1000"
                    :loop="true"
                    :navigation="true"
                    :modules="modules"
                    class="navigate-swiper"
                    :breakpoints="swiperBreakpoints(section)"
                    :centered-slides="isCenteredSwiper(section)"
                >
                    <SwiperSlide v-for="category in section.categories" :key="category.id"
                        :class="slideClass(section)">

                        <!-- Template: Card -->
                        <router-link v-if="section.item_template !== 'circle'"
                            :to="{ name: 'frontend.product', query: { category: category.slug } }"
                            class="w-full rounded-2xl shadow-xs group"
                        >
                            <img
                                class="w-full h-[120px] object-cover block rounded-tl-2xl rounded-tr-2xl"
                                :src="category.thumb"
                                alt="category"
                            />
                            <span class="text-sm sm:text-xl font-medium capitalize text-center py-2 px-3 overflow-hidden whitespace-nowrap text-ellipsis block rounded-bl-2xl rounded-br-2xl group-hover:text-primary">
                                {{ category.name }}
                            </span>
                        </router-link>

                        <!-- Template: Círculo -->
                        <router-link v-else
                            :to="{ name: 'frontend.product', query: { category: category.slug } }"
                            class="flex flex-col items-center gap-2 group"
                        >
                            <img
                                class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-full block ring-2 ring-gray-100 group-hover:ring-primary transition"
                                :src="category.thumb"
                                alt="category"
                            />
                            <span class="text-xs sm:text-sm font-medium capitalize text-center px-1 overflow-hidden whitespace-nowrap text-ellipsis w-full group-hover:text-primary">
                                {{ category.name }}
                            </span>
                        </router-link>

                    </SwiperSlide>
                </Swiper>
            </div>
        </section>

        <!-- Tipo: Produtos -->
        <section v-else-if="section.type === 'products' && section.products && section.products.length > 0" class="mb-10 sm:mb-20">
            <div class="container">
                <div class="flex items-center gap-4 mb-5 sm:mb-7" :class="titleAlignFlex(section)">
                    <component
                        :is="titleTag(section)"
                        :class="['font-bold capitalize', titleSizeClass(section)]"
                    >{{ section.name }}</component>
                </div>
                <div :class="productsContainerClass(section)">
                    <ProductListComponent :products="section.products" />
                </div>
            </div>
        </section>

        <!-- Tipo: Banner -->
        <section v-else-if="section.type === 'banner' && section.promotions && section.promotions.length > 0" class="mb-10 sm:mb-20">
            <div class="container">
                <component
                    v-if="section.name"
                    :is="titleTag(section)"
                    :class="['font-bold mb-6', titleSizeClass(section), titleAlignClass(section)]"
                >{{ section.name }}</component>

                <!-- Justificado: grid com colunas iguais -->
                <div v-if="(section.row_layout || 'justified') === 'justified'"
                    class="grid gap-4 sm:gap-6"
                    :style="{ gridTemplateColumns: `repeat(${Math.min(section.promotions.length, 3)}, minmax(0, 1fr))` }">
                    <template v-for="promo in section.promotions" :key="promo.id">
                        <a v-if="promo.link_type === 'custom'" :href="promo.link_url" target="_blank" rel="noopener" class="block w-full">
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </a>
                        <router-link v-else-if="promo.link_type === 'category'"
                            :to="{ name: 'frontend.product', query: { category: promo.link_url } }"
                            class="block w-full">
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </router-link>
                        <router-link v-else
                            :to="{ name: 'frontend.promotion.products', params: { slug: promo.slug } }"
                            class="block w-full">
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </router-link>
                    </template>
                </div>

                <!-- Esquerda / Centralizado: flex wrap -->
                <div v-else
                    class="flex flex-wrap gap-4 sm:gap-6"
                    :class="(section.row_layout === 'center') ? 'justify-center' : 'justify-start'">
                    <template v-for="promo in section.promotions" :key="promo.id">
                        <a v-if="promo.link_type === 'custom'" :href="promo.link_url" target="_blank" rel="noopener"
                            class="block w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]">
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </a>
                        <router-link v-else-if="promo.link_type === 'category'"
                            :to="{ name: 'frontend.product', query: { category: promo.link_url } }"
                            class="block w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]">
                            <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                        </router-link>
                        <router-link v-else
                            :to="{ name: 'frontend.promotion.products', params: { slug: promo.slug } }"
                            class="block w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]">
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
import 'swiper/css/pagination';
import LoadingComponent from "../components/LoadingComponent";
import ProductListComponent from "../components/ProductListComponent";

export default {
    name: "CategoryComponent",
    components: {
        Swiper,
        SwiperSlide,
        LoadingComponent,
        ProductListComponent,
    },
    setup() {
        return {
            modules: [Navigation, Pagination, Autoplay],
        }
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            breakpointsJustified: {
                0:    { slidesPerView: 'auto', spaceBetween: 16 },
                640:  { slidesPerView: 4,      spaceBetween: 24 },
                768:  { slidesPerView: 5,      spaceBetween: 24 },
                1024: { slidesPerView: 6,      spaceBetween: 24 },
            },
            breakpointsJustifiedCircle: {
                0:    { slidesPerView: 'auto', spaceBetween: 12 },
                640:  { slidesPerView: 5,      spaceBetween: 16 },
                768:  { slidesPerView: 6,      spaceBetween: 16 },
                1024: { slidesPerView: 8,      spaceBetween: 16 },
            },
            breakpointsAuto: {
                0:    { slidesPerView: 'auto', spaceBetween: 16 },
                640:  { slidesPerView: 'auto', spaceBetween: 24 },
                768:  { slidesPerView: 'auto', spaceBetween: 24 },
                1024: { slidesPerView: 'auto', spaceBetween: 24 },
            },
        }
    },
    computed: {
        categorySections: function () {
            return this.$store.getters["frontendCategorySection/sections"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendCategorySection/sections").then(() => {
            this.loading.isActive = false;
        }).catch(() => {
            this.loading.isActive = false;
        });
    },
    methods: {
        titleTag(section) {
            const tag = section.title_tag || 'h2';
            return tag === 'custom' ? 'p' : tag;
        },
        titleSizeClass(section) {
            const tag = section.title_tag || 'h2';
            if (tag === 'h1') return 'text-3xl sm:text-5xl';
            if (tag === 'custom') return 'text-lg sm:text-xl';
            return 'text-2xl sm:text-4xl';
        },
        titleAlignClass(section) {
            const pos = section.title_position || 'left';
            if (pos === 'center') return 'text-center';
            if (pos === 'right') return 'text-right';
            return 'text-left';
        },
        titleAlignFlex(section) {
            const pos = section.title_position || 'left';
            if (pos === 'center') return 'justify-center';
            if (pos === 'right') return 'justify-end';
            return 'justify-between';
        },

        swiperBreakpoints(section) {
            const layout = section.row_layout || 'justified';
            if (layout !== 'justified') return this.breakpointsAuto;
            return section.item_template === 'circle'
                ? this.breakpointsJustifiedCircle
                : this.breakpointsJustified;
        },

        isCenteredSwiper(section) {
            return (section.row_layout || 'justified') === 'center';
        },

        slideClass(section) {
            const layout = section.row_layout || 'justified';
            if (layout === 'justified') {
                return section.item_template === 'circle' ? '!w-28' : 'mobile:!w-24';
            }
            // left / center: auto width — card needs fixed width so slides don't collapse
            return section.item_template === 'circle' ? '!w-28' : '!w-40 sm:!w-48';
        },

        productsContainerClass(section) {
            const layout = section.row_layout || 'justified';
            if (layout === 'center') return 'flex flex-wrap gap-4 sm:gap-6 justify-center';
            if (layout === 'left')   return 'flex flex-wrap gap-4 sm:gap-6 justify-start';
            return 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6';
        },
    },
}
</script>
