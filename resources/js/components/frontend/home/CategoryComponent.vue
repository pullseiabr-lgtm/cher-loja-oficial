<template>
    <LoadingComponent :props="loading" />

    <template v-for="section in categorySections" :key="section.id">

        <!-- ===== CATEGORIAS ===== -->
        <section v-if="section.type === 'categories' && section.categories && section.categories.length > 0" class="sm:mb-10">
            <div class="container">
                <component v-if="showTitle(section)" :is="titleTag(section)"
                    :class="['font-bold mb-4 sm:mb-6', titleSizeClass(section), titleAlignClass(section)]"
                >{{ section.name }}</component>

                <!-- Carrossel -->
                <template v-if="isCarousel(section)">
                    <Swiper dir="ltr" :speed="1000" :loop="true" :navigation="true" :modules="modules"
                        class="navigate-swiper"
                        :breakpoints="categoryCarouselBreakpoints(section)"
                        :centered-slides="isCenteredSwiper(section)">
                        <SwiperSlide v-for="category in section.categories" :key="category.id"
                            :class="categorySlideClass(section)">
                            <router-link v-if="section.item_template === 'overlay'"
                                :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                class="w-full rounded-2xl overflow-hidden shadow-xs group block relative">
                                <img class="w-full object-cover block"
                                    :style="overlayImageCustomStyle(section)"
                                    :src="category.thumb" alt="category" />
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-2.5 pt-5 sm:p-3 sm:pt-6">
                                    <span class="text-white text-xs sm:text-base font-semibold capitalize block truncate group-hover:brightness-110 transition">
                                        {{ category.name }}
                                    </span>
                                </div>
                            </router-link>
                            <router-link v-else-if="section.item_template !== 'circle'"
                                :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                class="w-full rounded-2xl shadow-xs group">
                                <img class="w-full object-cover block rounded-tl-2xl rounded-tr-2xl"
                                    :style="cardImageCustomStyle(section)"
                                    :src="category.thumb" alt="category" />
                                <span class="text-sm sm:text-xl font-medium capitalize text-center py-2 px-3 overflow-hidden whitespace-nowrap text-ellipsis block rounded-bl-2xl rounded-br-2xl group-hover:text-primary">
                                    {{ category.name }}
                                </span>
                            </router-link>
                            <router-link v-else
                                :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                class="flex flex-col items-center gap-2 group"
                                :style="circleLinkStyle(section)">
                                <img class="object-cover rounded-full block ring-2 ring-gray-100 group-hover:ring-primary transition"
                                    :style="circleImageCustomStyle(section)"
                                    :src="category.thumb" alt="category" />
                                <span class="text-xs sm:text-sm font-medium capitalize text-center px-1 overflow-hidden whitespace-nowrap text-ellipsis w-full group-hover:text-primary">
                                    {{ category.name }}
                                </span>
                            </router-link>
                        </SwiperSlide>
                    </Swiper>
                </template>

                <!-- Linha (esquerda / centralizado / justificado) -->
                <template v-else>
                    <div :class="rowFlexClass(section)">
                        <template v-for="category in section.categories" :key="category.id">
                            <router-link v-if="section.item_template === 'overlay'"
                                :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                class="rounded-2xl overflow-hidden shadow-xs group block relative w-36 sm:w-52 flex-none">
                                <img class="w-full object-cover block"
                                    :style="overlayImageCustomStyle(section)"
                                    :src="category.thumb" alt="category" />
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-2 pt-4 sm:p-3 sm:pt-6">
                                    <span class="text-white text-xs sm:text-base font-semibold capitalize block truncate group-hover:brightness-110 transition">
                                        {{ category.name }}
                                    </span>
                                </div>
                            </router-link>
                            <router-link v-else-if="section.item_template !== 'circle'"
                                :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                class="rounded-2xl shadow-xs group w-36 sm:w-44 flex-none">
                                <img class="w-full object-cover block rounded-tl-2xl rounded-tr-2xl"
                                    :style="cardImageCustomStyle(section)"
                                    :src="category.thumb" alt="category" />
                                <span class="text-sm font-medium capitalize text-center py-2 px-2 overflow-hidden whitespace-nowrap text-ellipsis block rounded-bl-2xl rounded-br-2xl group-hover:text-primary">
                                    {{ category.name }}
                                </span>
                            </router-link>
                            <router-link v-else
                                :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                class="flex flex-col items-center gap-2 group flex-none"
                                :style="circleLinkStyle(section)">
                                <img class="object-cover rounded-full block ring-2 ring-gray-100 group-hover:ring-primary transition"
                                    :style="circleImageCustomStyle(section)"
                                    :src="category.thumb" alt="category" />
                                <span class="text-xs sm:text-sm font-medium capitalize text-center px-1 overflow-hidden whitespace-nowrap text-ellipsis w-full group-hover:text-primary">
                                    {{ category.name }}
                                </span>
                            </router-link>
                        </template>
                    </div>
                </template>
            </div>
        </section>

        <!-- ===== PRODUTOS ===== -->
        <section v-else-if="section.type === 'products' && section.products && section.products.length > 0" class="mb-10 sm:mb-20">
            <div class="container">
                <div v-if="showTitle(section)" class="flex items-center gap-4 mb-5 sm:mb-7" :class="titleAlignFlex(section)">
                    <component :is="titleTag(section)" :class="['font-bold capitalize', titleSizeClass(section)]">
                        {{ section.name }}
                    </component>
                </div>

                <!-- Carrossel -->
                <template v-if="isCarousel(section)">
                    <Swiper dir="ltr" :speed="1000" :loop="false" :navigation="true" :modules="modules"
                        class="navigate-swiper"
                        :breakpoints="productCarouselBreakpoints"
                        :centered-slides="isCenteredSwiper(section)">
                        <SwiperSlide v-for="product in section.products" :key="product.id">
                            <router-link :to="{ name: 'frontend.product.details', params: { slug: product.slug } }"
                                class="block rounded-2xl overflow-hidden shadow-xs group bg-white">
                                <img :src="product.cover" class="w-full h-40 object-cover block" alt="" />
                                <div class="p-3">
                                    <p class="text-sm font-medium text-gray-800 truncate group-hover:text-primary">{{ product.name }}</p>
                                    <p class="text-primary font-bold text-sm mt-1">{{ product.currency_price }}</p>
                                </div>
                            </router-link>
                        </SwiperSlide>
                    </Swiper>
                </template>

                <!-- Linha -->
                <template v-else>
                    <div :class="productsContainerClass(section)">
                        <ProductListComponent :products="section.products" />
                    </div>
                </template>
            </div>
        </section>

        <!-- ===== BANNERS ===== -->
        <section v-else-if="section.type === 'banner' && section.promotions && section.promotions.length > 0" class="mb-10 sm:mb-20">
            <div class="container">
                <component v-if="section.name && showTitle(section)" :is="titleTag(section)"
                    :class="['font-bold mb-6', titleSizeClass(section), titleAlignClass(section)]">
                    {{ section.name }}
                </component>

                <!-- Carrossel -->
                <template v-if="isCarousel(section)">
                    <Swiper dir="ltr" :speed="1000" :loop="true" :navigation="true" :modules="modules"
                        class="navigate-swiper"
                        :breakpoints="bannerCarouselBreakpoints">
                        <SwiperSlide v-for="promo in section.promotions" :key="promo.id">
                            <a v-if="promo.link_type === 'custom'" :href="promo.link_url" target="_blank" rel="noopener" class="block w-full">
                                <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                            </a>
                            <router-link v-else-if="promo.link_type === 'category'"
                                :to="{ name: 'frontend.product', query: { category: promo.link_url } }" class="block w-full">
                                <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                            </router-link>
                            <router-link v-else
                                :to="{ name: 'frontend.promotion.products', params: { slug: promo.slug } }" class="block w-full">
                                <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                            </router-link>
                        </SwiperSlide>
                    </Swiper>
                </template>

                <!-- Justificado: grid com colunas iguais -->
                <template v-else-if="(section.row_layout || 'carousel') === 'justified'">
                    <div class="grid gap-4 sm:gap-6"
                        :style="{ gridTemplateColumns: `repeat(${Math.min(section.promotions.length, 3)}, minmax(0, 1fr))` }">
                        <template v-for="promo in section.promotions" :key="promo.id">
                            <a v-if="promo.link_type === 'custom'" :href="promo.link_url" target="_blank" rel="noopener" class="block w-full">
                                <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                            </a>
                            <router-link v-else-if="promo.link_type === 'category'"
                                :to="{ name: 'frontend.product', query: { category: promo.link_url } }" class="block w-full">
                                <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                            </router-link>
                            <router-link v-else
                                :to="{ name: 'frontend.promotion.products', params: { slug: promo.slug } }" class="block w-full">
                                <img class="w-full block rounded-2xl object-cover aspect-[540/336]" :src="promo.cover" :alt="promo.name" />
                            </router-link>
                        </template>
                    </div>
                </template>

                <!-- Esquerda / Centralizado -->
                <template v-else>
                    <div class="flex flex-wrap gap-4 sm:gap-6"
                        :class="section.row_layout === 'center' ? 'justify-center' : 'justify-start'">
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
                </template>

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
    components: { Swiper, SwiperSlide, LoadingComponent, ProductListComponent },
    setup() {
        return { modules: [Navigation, Pagination, Autoplay] }
    },
    data() {
        return {
            loading: { isActive: false },
            productCarouselBreakpoints: {
                0:    { slidesPerView: 2, spaceBetween: 12 },
                640:  { slidesPerView: 3, spaceBetween: 16 },
                1024: { slidesPerView: 4, spaceBetween: 24 },
            },
            bannerCarouselBreakpoints: {
                0:    { slidesPerView: 1, spaceBetween: 16 },
                640:  { slidesPerView: 2, spaceBetween: 24 },
                1024: { slidesPerView: 3, spaceBetween: 24 },
            },
        }
    },
    computed: {
        categorySections() {
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
        isCarousel(section) {
            const layout = section.row_layout || 'carousel';
            return layout === 'carousel';
        },

        isCenteredSwiper(section) {
            return (section.row_layout || 'carousel') === 'center';
        },

        categoryCarouselBreakpoints(section) {
            if (section.item_template === 'circle') {
                return {
                    0:    { slidesPerView: 4, spaceBetween: 8  },
                    480:  { slidesPerView: 5, spaceBetween: 12 },
                    640:  { slidesPerView: 6, spaceBetween: 16 },
                    1024: { slidesPerView: 8, spaceBetween: 16 },
                };
            }
            if (section.item_template === 'overlay') {
                return {
                    0:    { slidesPerView: 2, spaceBetween: 12 },
                    640:  { slidesPerView: 3, spaceBetween: 16 },
                    1024: { slidesPerView: 4, spaceBetween: 24 },
                };
            }
            return {
                0:    { slidesPerView: 2, spaceBetween: 12 },
                480:  { slidesPerView: 3, spaceBetween: 16 },
                640:  { slidesPerView: 4, spaceBetween: 24 },
                768:  { slidesPerView: 5, spaceBetween: 24 },
                1024: { slidesPerView: 6, spaceBetween: 24 },
            };
        },

        categorySlideClass(section) {
            return section.item_template === 'circle' ? '' : '';
        },

        rowFlexClass(section) {
            const layout = section.row_layout || 'carousel';
            if (layout === 'center')    return 'flex flex-wrap gap-4 justify-center';
            if (layout === 'justified') return 'flex flex-wrap gap-4 justify-between';
            return 'flex flex-wrap gap-4 justify-start';
        },

        productsContainerClass(section) {
            const layout = section.row_layout || 'carousel';
            if (layout === 'center') return 'flex flex-wrap gap-4 sm:gap-6 justify-center';
            if (layout === 'left')   return 'flex flex-wrap gap-4 sm:gap-6 justify-start';
            return 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6';
        },

        showTitle(section) {
            return (section.title_position || 'left') !== 'none';
        },

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
            if (pos === 'right')  return 'text-right';
            return 'text-left';
        },
        titleAlignFlex(section) {
            const pos = section.title_position || 'left';
            if (pos === 'center') return 'justify-center';
            if (pos === 'right')  return 'justify-end';
            return 'justify-between';
        },

        circleLinkStyle(section) {
            const size = section.item_image_size || '6em';
            return { width: size, flexShrink: '0' };
        },

        circleImageCustomStyle(section) {
            const size = section.item_image_size || '6em';
            return { width: '100%', aspectRatio: '1 / 1' };
        },

        cardImageCustomStyle(section) {
            const size = section.item_image_size || '120px';
            return { height: size };
        },

        overlayImageCustomStyle(section) {
            const size = section.item_image_size || '4/3';
            if (size.includes('px') || size.includes('em') || size.includes('%')) {
                return { height: size, width: '100%', objectFit: 'cover' };
            }
            return { aspectRatio: size, width: '100%', objectFit: 'cover' };
        },
    },
}
</script>
