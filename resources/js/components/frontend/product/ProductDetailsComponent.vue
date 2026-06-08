<template>
    <LoadingComponent :props="loading" />
    <section class="mb-12">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <CategoryBreadcrumbComponent :categories="categories" />
                </div>

                <!-- Galeria com miniaturas na esquerda -->
                <div v-if="images.length" class="col-12 sm:col-6 lg:col-5">
                    <div class="flex gap-3 items-start">

                        <!-- Miniaturas verticais à esquerda -->
                        <div class="relative flex-shrink-0 w-[76px]">
                            <!-- Seta cima (só aparece se > 5 imagens) -->
                            <button v-if="images.length > 5"
                                class="thumb-nav-btn thumb-nav-prev w-full flex justify-center items-center py-1 mb-1 rounded-lg bg-gray-100 hover:bg-primary hover:text-white transition text-gray-500">
                                <i class="lab-line-up-arrow text-sm"></i>
                            </button>

                            <Swiper
                                @swiper="setThumbsSwiper"
                                direction="vertical"
                                :slidesPerView="5"
                                :spaceBetween="8"
                                :freeMode="true"
                                :watchSlidesProgress="true"
                                :navigation="images.length > 5 ? { prevEl: '.thumb-nav-prev', nextEl: '.thumb-nav-next' } : false"
                                :modules="modules"
                                class="thumb-swiper-vertical"
                                style="height: 380px;">
                                <SwiperSlide v-for="(image, index) in images" :key="index"
                                    class="cursor-pointer rounded-lg overflow-hidden border-2 border-gray-200 hover:border-primary transition-all duration-300"
                                    style="height: 68px !important;">
                                    <img class="w-full h-full object-cover"
                                        :src="image" alt="thumb" />
                                </SwiperSlide>
                            </Swiper>

                            <!-- Seta baixo -->
                            <button v-if="images.length > 5"
                                class="thumb-nav-btn thumb-nav-next w-full flex justify-center items-center py-1 mt-1 rounded-lg bg-gray-100 hover:bg-primary hover:text-white transition text-gray-500">
                                <i class="lab-line-down-arrow text-sm"></i>
                            </button>
                        </div>

                        <!-- Imagem principal -->
                        <div class="flex-1 min-w-0">
                            <Swiper dir="ltr" :spaceBetween="10" :navigation="false"
                                :thumbs="{ swiper: thumbsSwiper }"
                                :modules="modules" class="gallery-swiper rounded-2xl overflow-hidden">
                                <SwiperSlide v-for="(image, index) in images" :key="index" class="w-full">
                                    <inner-image-zoom :src="image" :zoomSrc="image" :zoomScale='1' zoomType="hover"
                                        :hideHint='true' />
                                </SwiperSlide>
                            </Swiper>
                        </div>
                    </div>
                </div>

                <div v-else class="col-12 sm:col-6 lg:col-5">
                    <img :src="product.image" alt="products" class="w-full rounded-2xl">
                </div>

                <div class="col-12 sm:col-6 lg:col-7 lg:pl-10">
                    <h2 class="text-3xl sm:text-4xl font-bold capitalize mb-5">{{ product.name }}</h2>
                    <h3 class="flex items-start gap-4 mb-5">
                        <span class="text-2xl font-bold">
                            {{
                                currencyFormat(temp.price, setting.site_digit_after_decimal_point,
                                    setting.site_default_currency_symbol, setting.site_currency_position)
                            }}
                        </span>
                        <del v-if="product.is_offer" class="text-lg font-bold text-shopperz-red">
                            {{
                                currencyFormat(temp.oldPrice, setting.site_digit_after_decimal_point,
                                    setting.site_default_currency_symbol, setting.site_currency_position)
                            }}
                        </del>
                    </h3>

                    <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 mb-6 pb-6">
                        <starRating border-color="#FFBC1F" :rounded-corners="true" :padding="2.5" :border-width="2.5"
                            :star-size="11" class="-mt-0.5" inactive-color="#FFFFFF" active-color="#FFBC1F"
                            :round-start-rating="false" :show-rating="false" :read-only="true" :max-rating="5"
                            :rating="(product.rating_star / product.rating_star_count)" />
                        <div v-if="product.rating_star_count > 0" class="flex items-center gap-1">
                            <span class="text-base font-medium whitespace-nowrap text-text">
                                {{ (product.rating_star / product.rating_star_count).toFixed(1) }}
                            </span>
                            <span
                                class="text-base font-medium whitespace-nowrap text-text hover:text-primary cursor-pointer">
                                ({{
                                    product.rating_star_count
                                }} {{ product.rating_star_count > 1 ? $t('label.reviews') : $t('label.review') }})
                            </span>
                        </div>
                    </div>

                    <VariationComponent v-if="initialVariations.length > 0 && variationComponent"
                        :method="selectedVariationMethod" :variations="initialVariations" />

                    <dl class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-8">
                        <dt class="capitalize text-lg font-semibold">{{ $t('label.quantity') }}:</dt>
                        <dd class="flex items-center gap-6">
                            <div class="flex items-center gap-1 w-20 p-1 rounded-full bg-[#F7F7FC]">
                                <button @click.prevent="quantityDecrement" type="button"
                                    :class="temp.quantity === 1 ? 'cursor-not-allowed' : ''"
                                    class="lab-fill-circle-minus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
                                <input type="number" v-model="temp.quantity" v-on:keypress="onlyNumber($event)"
                                    v-on:keyup="quantityUp" class="text-center w-full h-5 text-sm font-medium">
                                <button @click.prevent="quantityIncrement" type="button"
                                    :class="(!temp.show_stock_out && !(!temp.can_purchasable)) && temp.stock === temp.quantity ? 'cursor-not-allowed' : temp.maximum_purchase_quantity > 0 && temp.quantity >= temp.maximum_purchase_quantity ? 'cursor-not-allowed' : ''"
                                    class="lab-fill-circle-plus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
                            </div>
                            <div v-if="!initialVariations.length || selectedVariation != null">
                                <!-- show_stock_out = ENABLE: sempre disponível mesmo sem estoque -->
                                <p v-if="temp.show_stock_out" class="capitalize">
                                    {{ $t('label.available') }}
                                    <span v-if="temp.stock > 0">: <b>({{ temp.stock }})</b> {{ product.unit }}</span>
                                </p>
                                <!-- Estoque normal: mostra quantidade real do banco -->
                                <p v-else-if="temp.stock > 0" class="capitalize">
                                    {{ $t('label.available') }}:
                                    <b>({{ temp.stock }})</b>
                                    {{ product.unit }}
                                </p>
                                <p v-else class="capitalize text-danger">
                                    {{ $t('label.stock_out') }}
                                </p>
                            </div>
                        </dd>
                    </dl>

                    <dl v-if="temp.quantity > 1" class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-8">
                        <dt class="capitalize text-lg font-semibold">{{ $t('label.total_price') }}:</dt>
                        <dd class="flex items-center gap-6 text-green-500 font-semibold text-lg">
                            {{
                                currencyFormat(temp.totalPrice, setting.site_digit_after_decimal_point,
                                    setting.site_default_currency_symbol, setting.site_currency_position)
                            }}
                        </dd>
                    </dl>

                    <div class="flex flex-wrap items-center gap-4 mb-10">
                        <button @click.prevent="addToCart" :disabled="enableAddToCardButton" type="button"
                            :class="enableAddToCardButton ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'"
                            class="flex items-center gap-3 px-8 h-12 leading-none rounded-full transition-all duration-300 bg-[#00b398] text-white">
                            <i class="lab-line-bag text-xl"></i>
                            <span class="whitespace-nowrap font-bold">{{ $t("button.add_to_cart") }}</span>
                        </button>
                        <button @click.prevent="buyNow" :disabled="enableAddToCardButton" type="button"
                            :class="enableAddToCardButton ? 'opacity-50 cursor-not-allowed' : 'hover:opacity-90'"
                            class="flex items-center gap-3 px-8 h-12 leading-none rounded-full transition-all duration-300 bg-secondary text-white">
                            <span class="whitespace-nowrap font-bold">{{ $t("button.buy_now") }}</span>
                        </button>
                        <button type="button" @click="wishlist(product.wishlist = !product.wishlist)"
                            :class="product.wishlist ? 'text-primary' : 'text-secondary'"
                            class="flex items-center gap-3 px-8 h-12 leading-12 rounded-full transition-all duration-500 shadow-btn-secondary bg-white">
                            <i :class="product.wishlist ? 'lab-fill-heart' : 'lab-line-heart'"
                                class="lab-line-heart text-xl"></i>
                            <span class="whitespace-nowrap font-bold">{{ $t('button.favorite') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section :class="relatedProducts.length > 0 ? 'mb-12' : 'mb-24'">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="rounded-[32px] border border-[#D9DBE9]">
                        <nav class="flex flex-wrap items-center p-4 sm:py-6 sm:px-8 gap-3 sm:gap-6">
                            <button type="button"
                                @click.prevent="multiTargets($event, 'tab-btn', 'tab-div', 'tab_details')"
                                class="tab-btn active text-sm sm:text-base font-bold leading-5 capitalize py-2 sm:py-3.5 px-5 sm:px-8 rounded-full border border-[#D9DBE9]">
                                {{ $t('label.details') }}
                            </button>

                            <button type="button"
                                @click.prevent="multiTargets($event, 'tab-btn', 'tab-div', 'tab_videos')"
                                class="tab-btn text-sm sm:text-base font-bold leading-5 capitalize py-2 sm:py-3.5 px-5 sm:px-8 rounded-full border border-[#D9DBE9]">
                                {{ $t('label.videos') }}
                            </button>

                            <button type="button"
                                @click.prevent="multiTargets($event, 'tab-btn', 'tab-div', 'tab_reviews')"
                                class="tab-btn text-sm sm:text-base font-bold leading-5 capitalize py-2 sm:py-3.5 px-5 sm:px-8 rounded-full border border-[#D9DBE9]">
                                {{ $t('label.reviews') }}
                            </button>
                            <button type="button"
                                @click.prevent="multiTargets($event, 'tab-btn', 'tab-div', 'tab_shipping_and_return')"
                                class="tab-btn text-sm sm:text-base font-bold leading-5 capitalize py-2 sm:py-3.5 px-5 sm:px-8 rounded-full border border-[#D9DBE9]">
                                {{ $t('label.shipping_and_return') }}
                            </button>
                        </nav>

                        <div id="tab_details" class="tab-div active p-4 sm:p-8 sm:pt-6 border-t border-[#D9DBE9]">
                            <h3 class="capitalize text-2xl sm:text-3xl font-bold mb-4">
                                {{ $t('label.product_details') }}
                            </h3>
                            <div class="text-description" v-html="product.details"></div>
                        </div>

                        <div id="tab_videos" class="tab-div p-4 sm:p-8 sm:pt-6 border-t border-[#D9DBE9]">
                            <h3 class="capitalize text-2xl sm:text-3xl font-bold mb-4">
                                {{ $t('label.product_videos') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <iframe v-for="video in videos" :src="video.link"
                                    class="w-full h-40 sm:h-64 rounded-2xl"></iframe>
                            </div>
                        </div>

                        <div id="tab_reviews" class="tab-div p-4 sm:p-8 sm:pt-6 border-t border-[#D9DBE9]">
                            <h3 class="capitalize text-2xl sm:text-3xl font-bold mb-4">
                                {{ $t('label.product_reviews') }}
                            </h3>
                            <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 mb-6 pb-6">
                                <starRating border-color="#FFBC1F" :rounded-corners="true" :padding="2.5"
                                    :border-width="2.5" :star-size="11" class="-mt-0.5" inactive-color="#FFFFFF"
                                    active-color="#FFBC1F" :round-start-rating="false" :show-rating="false"
                                    :read-only="true" :max-rating="5"
                                    :rating="(product.rating_star / product.rating_star_count)" />
                                <div v-if="product.rating_star_count > 0" class="flex items-center gap-1">
                                    <span class="text-base font-medium whitespace-nowrap text-text">
                                        {{ (product.rating_star / product.rating_star_count).toFixed(1) }}
                                    </span>
                                    <span
                                        class="text-base font-medium whitespace-nowrap text-text hover:text-primary cursor-pointer">
                                        ({{
                                            product.rating_star_count
                                        }} {{
                                            product.rating_star_count > 1 ? $t('label.reviews') : $t('label.review')
                                        }})
                                    </span>
                                </div>
                            </div>

                            <div v-for="review in reviews?.slice()?.reverse()" class="mb-8">
                                <h4 class="text-lg font-semibold capitalize mb-2 flex justify-between">{{ review.name }} 
                                    <span class="text-sm font-normal">{{ review.date }}</span>
                                </h4>
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <starRating border-color="#FFBC1F" inactive-color="#FFFFFF" active-color="#FFBC1F"
                                        :rounded-corners="true" :padding="2.5" :border-width="2.5" :star-size="11"
                                        class="-mt-0.5" :round-start-rating="false" :show-rating="false"
                                        :read-only="true" :max-rating="5" :rating="review.star" />
                                </div>
                                <p class="mb-4">{{ review.review }}</p>


                                <div class="flex flex-wrap gap-4" v-if="review.images.length > 0">
                                    <img v-for="reviewImage in review.images" :src="reviewImage" alt="image"
                                        class="w-20 rounded-lg" @click="previewImage(reviewImage)">
                                </div>
                            </div>

                            <button v-if="product.rating_star_count > reviews.length" @click.prevent="readMore"
                                type="button" class="flex items-center justify-center gap-3 w-fit mx-auto text-primary">
                                <span class="text-lg font-medium capitalize">{{ $t('label.read_more') }}</span>
                                <i class="lab-line-down-arrow text-sm -mt-1"></i>
                            </button>
                        </div>

                        <div id="tab_shipping_and_return" class="tab-div p-4 sm:p-8 sm:pt-6 border-t border-[#D9DBE9]">
                            <h3 class="capitalize text-2xl sm:text-3xl font-bold mb-4">
                                {{ $t('label.product_shipping_and_return') }}</h3>
                            <div class="text-description" v-html="product.shipping_and_return"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section v-if="relatedProducts.length > 0" class="mb-24 sm:mb-20">
        <div class="container">
            <div class="flex items-center justify-between gap-4 mb-5 sm:mb-7">
                <h2 class="text-2xl sm:text-4xl font-bold capitalize">
                    {{ $t('label.related_products') }}
                </h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                <ProductListComponent v-if="relatedProducts.length > 0" :products="relatedProducts" />
            </div>
        </div>
    </section>

    <div id="imagePreviewModal" class="modal flex items-center"  @click="hidePreviewImage">
            <div class="max-w-lg w-full mx-auto relative ">
                        <button 
                            @click="hidePreviewImage" 
                            class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full w-8 h-8 hover:bg-opacity-75"
                        >  ✕</button>
                <img data-modal="imagePreviewModal" :src="previewImg" alt="return" class="w-full h-full rounded-lg object-cover" />
            </div>
    </div>

</template>

<script>
import { ref } from "vue";
import { Swiper, SwiperSlide } from 'swiper/vue';
import { FreeMode, Navigation, Thumbs } from 'swiper/modules';
import LoadingComponent from "../components/LoadingComponent";
import starRating from "vue-star-rating";
import targetService from "../../../services/targetService";
import router from "../../../router";
import CategoryBreadcrumbComponent from "../components/CategoryBreadcrumbComponent";
import ProductListComponent from "../components/ProductListComponent";
import VariationComponent from "../components/VariationComponent";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import { useHead } from '@vueuse/head';
import 'vue-inner-image-zoom/lib/vue-inner-image-zoom.css';

import InnerImageZoom from 'vue-inner-image-zoom';

export default {
    name: "ProductDetailsComponent",
    components: {
        VariationComponent,
        ProductListComponent,
        CategoryBreadcrumbComponent,
        starRating,
        Swiper,
        SwiperSlide,
        LoadingComponent,
        'inner-image-zoom': InnerImageZoom
    },
    setup() {
        const thumbsSwiper = ref(null);
        const setThumbsSwiper = (swiper) => {
            thumbsSwiper.value = swiper;
        };
        return {
            thumbsSwiper,
            setThumbsSwiper,
            modules: [FreeMode, Navigation, Thumbs],
        }
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                search: {
                    slug: null,
                    review_limit: 3
                }
            },
            enableAddToCardButton: true,
            selectedVariation: null,
            productArray: {},
            variationComponent: false,
            initProduct: {
                isVariation: false,
                variationId: null,
                sku: null,
                stock: 0,
                show_stock_out: false,
                can_purchasable: true,
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0,
                maximum_purchase_quantity: 0
            },
            temp: {
                name: "",
                image: "",
                isVariation: false,
                variationId: null,
                productId: 0,
                sku: null,
                stock: 0,
                show_stock_out: false,
                can_purchasable: true,
                taxes: {},
                shipping: {},
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0,
                maximum_purchase_quantity: 0
            },
            previewImg:null
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        categories: function () {
            return this.$store.getters["frontendProductCategory/ancestorsAndSelf"];
        },
        initialVariations: function () {
            return this.$store.getters["frontendProductVariation/initialVariation"];
        },
        product: function () {
            return this.$store.getters["frontendProduct/show"];
        },
        images: function () {
            return this.$store.getters["frontendProduct/showImages"];
        },
        videos: function () {
            return this.$store.getters["frontendProduct/showVideos"];
        },
        reviews: function () {
            return  this.$store.getters["frontendProduct/showReviews"];
            //return reviews?.reverse();
        },
        relatedProducts: function () {
            return this.$store.getters["frontendProduct/relatedProducts"];
        },
    },
    mounted() {
        this.show();
        this.showRelatedProduct();
    },
    methods: {
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        multiTargets: function (event, commonBtnClass, commonDivClass, targetID) {
            targetService.multiTargets(event, commonBtnClass, commonDivClass, targetID)
        },
        wishlist: function (toggle) {
            this.$store.dispatch("frontendWishlist/toggle", {
                product_id: this.product.id,
                toggle: toggle
            }).then((res) => {
            }).catch((err) => {
                if (err.response.status === 401) {
                    this.product.wishlist = false;
                    router.push({ name: "auth.login" });
                }
            });
        },
        readMore: function () {
            this.props.search.review_limit += 1;
            this.show();
        },
        previewImage: function (img) {
            this.previewImg = img;
            appService.modalShow('#imagePreviewModal');
        },
        hidePreviewImage: function () {
             appService.modalHide('#imagePreviewModal');
        },
        show: function () {
            if (typeof this.$route.params.slug !== "undefined") {
                this.loading.isActive = true;
                this.props.search.slug = this.$route.params.slug;
                this.$store.dispatch("frontendProduct/show", this.props.search).then((res) => {
                    this.initProduct = {
                        isVariation: false,
                        variationId: null,
                        sku: res.data.data.sku,
                        stock: res.data.data.stock,
                        show_stock_out: res.data.data.show_stock_out ?? false,
                        can_purchasable: res.data.data.can_purchasable ?? true,
                        quantity: 1,
                        discount: 0,
                        price: res.data.data.price,
                        oldPrice: res.data.data.old_price,
                        totalPrice: res.data.data.price,
                        maximum_purchase_quantity: res.data.data.maximum_purchase_quantity
                    };
                    this.temp = {
                        name: res.data.data.name,
                        image: res.data.data.image,
                        isVariation: false,
                        variationId: null,
                        productId: res.data.data.id,
                        sku: res.data.data.sku,
                        stock: res.data.data.stock,
                        show_stock_out: res.data.data.show_stock_out ?? false,
                        can_purchasable: res.data.data.can_purchasable ?? true,
                        taxes: res.data.data.taxes,
                        shipping: res.data.data.shipping,
                        quantity: 1,
                        discount: 0,
                        price: res.data.data.price,
                        oldPrice: res.data.data.old_price,
                        totalPrice: res.data.data.price,
                        maximum_purchase_quantity: res.data.data.maximum_purchase_quantity
                    };

                    this.$store.dispatch("frontendProductCategory/ancestorsAndSelf", res.data.data.category_slug).then((categoryRes) => {
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });

                    this.$store.dispatch("frontendProductVariation/initialVariation", res.data.data.id).then((initVariationRes) => {
                        if (initVariationRes.data.data.length > 0) {
                            this.variationComponent = true;
                        }

                        // Habilitar botão: stock real > 0 OU produto sempre disponível (show_stock_out)
                        if (!initVariationRes.data.data.length && (res.data.data.stock > 0 || res.data.data.show_stock_out)) {
                            this.enableAddToCardButton = false;
                        }
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });

                    if (Object.keys(res.data.data.seo) && res.data.data.seo.title && res.data.data.seo.description) {
                        let metaData = [
                            { name: 'title', content: res.data.data.seo.title },
                            { name: 'description', content: res.data.data.seo.description },
                        ];

                        if (res.data.data.seo.thumb && res.data.data.seo.cover) {
                            metaData.push({ content: res.data.data.seo.thumb });
                            metaData.push({ content: res.data.data.seo.cover });
                        }

                        useHead({
                            title: this.setting.company_name + ' - ' + res.data.data.seo.title,
                            meta: metaData
                        });
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        },
        showRelatedProduct: function () {
            if (typeof this.$route.params.slug !== "undefined") {
                this.loading.isActive = true;
                this.props.search.slug = this.$route.params.slug;
                this.$store.dispatch("frontendProduct/relatedProducts", {
                    slug: this.$route.params.slug,
                    rand: 8
                }).then((res) => {
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        },
        selectedVariationMethod: function (variation) {
            this.enableAddToCardButton = true;
            this.selectedVariation = null;

            this.temp.isVariation = this.initProduct.isVariation;
            this.temp.variationId = this.initProduct.variationId;
            this.temp.sku = this.initProduct.sku;
            this.temp.stock = this.initProduct.stock;
            this.temp.quantity = this.initProduct.quantity;
            this.temp.discount = this.initProduct.discount;
            this.temp.price = this.initProduct.price;
            this.temp.oldPrice = this.initProduct.oldPrice;
            this.temp.totalPrice = this.initProduct.price;
            this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;

            if (variation) {

                this.selectedVariation = variation;

                this.temp.isVariation = true;
                this.temp.variationId = variation.id;
                this.temp.sku = variation.sku;
                this.temp.stock = variation.stock;
                this.temp.quantity = 1;
                this.temp.discount = 0;
                this.temp.price = variation.price;
                this.temp.oldPrice = variation.old_price;
                this.temp.totalPrice = variation.price;
                this.temp.maximum_purchase_quantity = variation.maximum_purchase_quantity;

                if (variation.stock > 0) {
                    this.enableAddToCardButton = false;
                }
            }
        },
        quantityUp: function () {
            if (this.temp.quantity === 0) {
                this.temp.quantity = 1;
            }

            // Limit by stock only when stock is tracked (purchasable + not always-available)
            const unlimitedStock = this.temp.show_stock_out || !this.temp.can_purchasable;
            if (!unlimitedStock && this.temp.quantity > this.temp.stock) {
                this.temp.quantity = this.temp.stock;
            }

            if (this.temp.maximum_purchase_quantity > 0 && this.temp.quantity > this.temp.maximum_purchase_quantity) {
                alertService.error(this.$t('message.purchase_limit_exceeded'));
                this.temp.quantity = this.temp.maximum_purchase_quantity;
            }

            this.totalPriceSetup();
        },
        quantityIncrement: function () {
            this.temp.quantity++;
            if (this.temp.quantity <= 0) {
                this.temp.quantity = 1;
            }

            const unlimitedStock = this.temp.show_stock_out || !this.temp.can_purchasable;
            if (!unlimitedStock && this.temp.quantity > this.temp.stock) {
                this.temp.quantity--;
            }

            if (this.temp.maximum_purchase_quantity > 0 && this.temp.quantity > this.temp.maximum_purchase_quantity) {
                this.temp.quantity--;
            }
            this.totalPriceSetup();
        },
        quantityDecrement: function () {
            this.temp.quantity--;
            if (this.temp.quantity <= 0) {
                this.temp.quantity = 1;
            }
            this.totalPriceSetup();
        },
        totalPriceSetup: function () {
            this.temp.totalPrice = (this.temp.price * this.temp.quantity);
        },
        buildProductArray: function () {
            return {
                name: this.temp.name,
                product_id: this.temp.productId,
                image: this.temp.image,
                variation_names: '',
                variation_id: this.temp.variationId,
                sku: this.temp.sku,
                stock: this.temp.stock,
                taxes: this.temp.taxes,
                shipping: this.temp.shipping,
                quantity: this.temp.quantity,
                discount: this.temp.discount,
                price: this.temp.price,
                old_price: this.temp.oldPrice,
                total_price: this.temp.totalPrice,
                maximum_purchase_quantity: this.temp.maximum_purchase_quantity
            };
        },
        resetTemp: function () {
            this.productArray = {};
            this.selectedVariation = null;
            this.temp.isVariation = this.initProduct.isVariation;
            this.temp.variationId = this.initProduct.variationId;
            this.temp.sku = this.initProduct.sku;
            this.temp.stock = this.initProduct.stock;
            this.temp.quantity = this.initProduct.quantity;
            this.temp.discount = this.initProduct.discount;
            this.temp.price = this.initProduct.price;
            this.temp.oldPrice = this.initProduct.oldPrice;
            this.temp.totalPrice = this.initProduct.price;
            this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;
        },
        buyNow: function () {
            if (+this.temp.quantity < 1 || this.enableAddToCardButton) return false;

            this.enableAddToCardButton = true;
            this.productArray = this.buildProductArray();

            const goToCheckout = () => {
                this.resetTemp();
                router.push({ name: 'frontend.checkout.checkout' });
            };

            if (this.selectedVariation) {
                this.$store.dispatch("frontendProductVariation/ancestorsToString", this.selectedVariation.id).then((res) => {
                    this.productArray.variation_names = res.data.data;
                    this.variationComponent = false;
                    this.$store.dispatch("frontendCart/lists", this.productArray).then(() => {
                        this.variationComponent = true;
                        goToCheckout();
                    }).catch(() => {
                        alertService.error(this.$t('message.maximum_quantity'));
                        this.variationComponent = true;
                        this.selectedVariation = null;
                        this.temp.stock = this.initProduct.stock;
                        this.temp.quantity = this.initProduct.quantity;
                        this.enableAddToCardButton = false;
                    });
                }).catch(() => {});
            } else {
                this.$store.dispatch("frontendCart/lists", this.productArray).then(() => {
                    goToCheckout();
                }).catch(() => {
                    alertService.error(this.$t('message.maximum_quantity'));
                    this.enableAddToCardButton = false;
                    this.selectedVariation = null;
                    this.temp.stock = this.initProduct.stock;
                    this.temp.quantity = this.initProduct.quantity;
                });
            }
        },
        addToCart: function () {

            if (+this.temp.quantity < 1) {
                return false;
            }

            this.enableAddToCardButton = true;
            this.productArray = {
                name: this.temp.name,
                product_id: this.temp.productId,
                image: this.temp.image,
                variation_names: '',
                variation_id: this.temp.variationId,
                sku: this.temp.sku,
                stock: this.temp.stock,
                taxes: this.temp.taxes,
                shipping: this.temp.shipping,
                quantity: this.temp.quantity,
                discount: this.temp.discount,
                price: this.temp.price,
                old_price: this.temp.oldPrice,
                total_price: this.temp.totalPrice,
                maximum_purchase_quantity: this.temp.maximum_purchase_quantity
            }

            if (this.selectedVariation) {
                this.$store.dispatch("frontendProductVariation/ancestorsToString", this.selectedVariation.id).then((res) => {
                    this.productArray.variation_names = res.data.data;
                    this.variationComponent = false;
                    this.$store.dispatch("frontendCart/lists", this.productArray).then((res) => {
                        alertService.success(this.$t('message.add_to_cart'));
                        this.variationComponent = true;
                        this.productArray = {};
                        this.selectedVariation = null;
                        this.temp.isVariation = this.initProduct.isVariation;
                        this.temp.variationId = this.initProduct.variationId;
                        this.temp.sku = this.initProduct.sku;
                        this.temp.stock = this.initProduct.stock;
                        this.temp.quantity = this.initProduct.quantity;
                        this.temp.discount = this.initProduct.discount;
                        this.temp.price = this.initProduct.price;
                        this.temp.oldPrice = this.initProduct.oldPrice;
                        this.temp.totalPrice = this.initProduct.price;
                        this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;
                    }).catch((err) => {
                        alertService.error(this.$t('message.maximum_quantity'));
                        this.variationComponent = true;
                        this.selectedVariation = null;
                        this.temp.stock = this.initProduct.stock;
                        this.temp.quantity = this.initProduct.quantity;
                    });
                }).catch((err) => {
                });
            } else {
                this.$store.dispatch("frontendCart/lists", this.productArray).then((res) => {
                    alertService.success(this.$t('message.add_to_cart'));
                    this.enableAddToCardButton = false;
                    this.productArray = {};
                    this.selectedVariation = null;
                    this.temp.isVariation = this.initProduct.isVariation;
                    this.temp.variationId = this.initProduct.variationId;
                    this.temp.sku = this.initProduct.sku;
                    this.temp.stock = this.initProduct.stock;
                    this.temp.quantity = this.initProduct.quantity;
                    this.temp.discount = this.initProduct.discount;
                    this.temp.price = this.initProduct.price;
                    this.temp.oldPrice = this.initProduct.oldPrice;
                    this.temp.totalPrice = this.initProduct.price;
                    this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;
                }).catch((err) => {
                    alertService.error(this.$t('message.maximum_quantity'));
                    this.enableAddToCardButton = false;
                    this.selectedVariation = null;
                    this.temp.stock = this.initProduct.stock;
                    this.temp.quantity = this.initProduct.quantity;
                });
            }
        }
    },
    watch: {
        $route() {
            this.show();
            this.showRelatedProduct();
        }
    }
}
</script>

<style scoped>
/* Swiper vertical de miniaturas */
.thumb-swiper-vertical {
    width: 76px;
}

.thumb-swiper-vertical .swiper-slide {
    height: 68px !important;
    width: 76px !important;
    border-radius: 8px;
    overflow: hidden;
    opacity: 0.65;
    transition: opacity 0.2s, border-color 0.2s;
}

.thumb-swiper-vertical .swiper-slide-thumb-active {
    opacity: 1;
    border-color: var(--color-primary, #00b398) !important;
    border-width: 2px !important;
}

/* Esconder setas nativas do Swiper na galeria principal */
.gallery-swiper :deep(.swiper-button-next),
.gallery-swiper :deep(.swiper-button-prev) {
    display: none;
}

/* Botão de nav das miniaturas */
.thumb-nav-btn {
    cursor: pointer;
    border: none;
    outline: none;
    line-height: 1;
}
</style>