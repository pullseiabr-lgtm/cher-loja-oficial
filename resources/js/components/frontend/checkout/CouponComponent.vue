<template>
    <LoadingComponent :props="loading" />

    <!-- First Purchase Gift Card -->
    <div v-if="firstPurchaseCoupon && Object.keys(cartCoupon).length === 0"
        style="margin-bottom: 1.5rem; border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, #fff8f0 0%, #fff1e6 50%, #ffe8d6 100%); border: 1px solid #f0d4b8; position: relative;">
        <div style="position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; background: radial-gradient(circle, rgba(255,183,77,0.15) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -15px; left: -15px; width: 60px; height: 60px; background: radial-gradient(circle, rgba(255,138,101,0.1) 0%, transparent 70%); border-radius: 50%;"></div>
        <div style="display: flex; align-items: stretch;">
            <div v-if="firstPurchaseCoupon.image" style="width: 110px; flex-shrink: 0; padding: 16px 0 16px 16px; display: flex; align-items: center;">
                <img :src="firstPurchaseCoupon.image" alt="presente"
                    style="width: 100%; height: auto; border-radius: 12px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.08);" />
            </div>
            <div style="flex: 1; padding: 16px 16px 14px 16px; display: flex; flex-direction: column; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                    <span style="font-size: 1.2rem;">🎁</span>
                    <span style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.2px; color: #c77d3a;">Exclusivo para você</span>
                </div>
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #5a3e28; line-height: 1.3; margin-bottom: 8px;">
                    {{ firstPurchaseCoupon.description || 'Tem presente pra Você' }}
                </h4>
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <button @click.prevent="applyFirstPurchaseCoupon"
                        style="display: inline-flex; align-items: center; gap: 4px; background: linear-gradient(135deg, #4caf50, #43a047); color: #fff; font-size: 0.7rem; font-weight: 600; padding: 4px 12px; border-radius: 20px; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 4px rgba(76,175,80,0.3);">
                        Resgatar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="Object.keys(cartCoupon).length !== 0"
        class="mb-6 rounded-2xl border border-success flex items-center gap-3 p-4 cursor-pointer">
        <div class="relative flex-shrink-0">
            <i class="lab-fill-shape lab-font-size-2xl opacity-[0.3] text-success"></i>
            <i
                class="lab-line-percent lab-font-size-8 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-success"></i>
        </div>
        <div class="flex-auto overflow-hidden">
            <h4
                class="font-semibold leading-5 whitespace-nowrap overflow-hidden text-ellipsis capitalize text-success">
                {{ $t('message.coupon_applied') }}
                <span v-if="cartCoupon.discount_percentage" class="text-xs font-normal">
                    ({{ Math.round(cartCoupon.discount_percentage) }}% OFF)
                </span>
            </h4>
        </div>
        <button @click.prevent="destroyCoupon" class="lab-line-trash lab-font-size-xl text-danger"></button>
    </div>

    <div v-if="!firstPurchaseCoupon && Object.keys(cartCoupon).length === 0" @click.prevent="showTarget('coupon-modal', 'modal-active')"
        class="mb-6 rounded-2xl border border-focus flex items-center gap-3 p-4 cursor-pointer">
        <div class="relative flex-shrink-0">
            <i class="lab lab-fill-shape lab-font-size-2xl opacity-[0.3] text-focus"></i>
            <i
                class="lab lab-line-percent lab-font-size-8 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-focus"></i>
        </div>
        <div class="flex-auto overflow-hidden">
            <h4
                class="font-semibold leading-5 mb-1 whitespace-nowrap overflow-hidden text-ellipsis capitalize text-focus">
                {{ $t('message.apply_coupon') }}</h4>
            <h5 class="text-xs font-normal whitespace-nowrap overflow-hidden text-ellipsis">
                {{ $t('message.get_discount_with_your_order') }}
            </h5>
        </div>
        <i class="lab lab-line-chevron-right rtl:rotate-180 lab-font-size-2xl text-focus"></i>
    </div>

    <div id="coupon-modal"
        class="fixed inset-0 z-50 p-3 w-screen h-dvh overflow-y-auto bg-black/50 transition-all duration-300 opacity-0 invisible">
        <div class="w-full rounded-xl mx-auto bg-white transition-all duration-300 max-w-[360px]">
            <div class="flex items-center justify-between gap-2 py-4 px-4 border-b border-slate-100">
                <h3 class="text-lg font-bold capitalize"> {{ $t('label.coupon_code') }}</h3>
                <button @click.prevent="hideTarget('coupon-modal', 'modal-active')" type="button"
                    class="lab-line-circle-cross text-lg text-[#E93C3C]"></button>
            </div>
            <form @submit.prevent="couponChecking" class="w-full flex items-center px-4 mt-4">
                <input :class="error ? 'invalid' : ''" type="text" v-model="code"
                    class="h-11 w-full px-3 ltr:rounded-tl-lg rtl:rounded-tr-lg ltr:rounded-bl-lg rtl:rounded-br-lg border ltr:border-r-0 rtl:border-l-0 border-[#D9DBE9]">
                <button type="submit" class="h-11 px-4 leading-11 ltr:rounded-tr-lg rtl:rounded-tl-lg rtl:rounded-bl-lg ltr:rounded-br-lg rtl:rounded-br-0 rtl:rounded-tr-0
                capitalize font-semibold text-white bg-[#007FE3]">
                    {{ $t('button.apply') }}
                </button>
            </form>
            <small class="w-full px-4 pt-0 db-field-alert" v-if="error">{{ error }}</small>

            <div v-if="coupons.length > 0" class="p-4 pt-4 flex flex-col gap-4">
                <div v-for="coupon in coupons" :key="coupon" class="bg-[#EEF7FF] p-4 relative rounded-xl">
                    <h3 class="py-1 px-2 rounded font-medium text-xs w-fit mb-2 bg-[#FFDB1F]">
                        {{ $t('label.code') }}: {{ coupon.code }}
                    </h3>
                    <h4 class="text-sm font-medium mb-3">
                        {{ coupon.description }}
                    </h4>
                    <p class="text-xs text-text">{{ coupon.convert_start_date }} - {{ coupon.convert_end_date }}</p>
                    <button @click.prevent="appCouponButton(coupon)" type="button"
                        class="absolute bottom-0 ltr:right-0 rtl:left-0 text-sm font-semibold capitalize py-1.5 px-3 rounded-br-xl rounded-tl-xl text-white bg-primary">
                        {{ $t('button.apply') }}
                    </button>
                </div>
            </div>
            <div v-else class="px-4 pt-2 pb-6 text-center">
                <div class="flex justify-center mb-3 opacity-30">
                    <i class="lab-line-percent" style="font-size: 3rem;"></i>
                </div>
                <p class="text-sm text-gray-400">Digite seu código de cupom acima e clique em Aplicar</p>
            </div>
        </div>
    </div>
</template>

<script>
import targetService from "../../../services/targetService";
import alertService from "../../../services/alertService";
import LoadingComponent from "../components/LoadingComponent.vue";

export default {
    name: "CouponComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            code: null,
            error: ""
        }
    },
    computed: {
        coupons: function () {
            return this.$store.getters['frontendCoupon/lists'];
        },
        subtotal: function () {
            return this.$store.getters['frontendCart/subtotal'];
        },
        cartCoupon: function () {
            return this.$store.getters['frontendCart/coupon'];
        },
        firstPurchaseCoupon: function () {
            const list = this.$store.getters['frontendCoupon/firstPurchase'];
            return list && list.length > 0 ? list[0] : null;
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendCoupon/lists", {}).then(res => {
            this.loading.isActive = false;
            this.loadFirstPurchase();
        }).catch((err) => {
            this.loading.isActive = false;
            this.loadFirstPurchase();
        });
    },
    methods: {
        loadFirstPurchase() {
            try {
                const vuex = JSON.parse(localStorage.getItem('vuex'));
                if (vuex && vuex.auth && vuex.auth.authToken) {
                    this.$store.dispatch("frontendCoupon/firstPurchase").catch(() => {});
                }
            } catch (e) {}
        },
        showTarget: function (id, cClass) {
            targetService.showTarget(id, cClass);
        },
        hideTarget: function (id, cClass) {
            this.code = null;
            this.error = "";
            targetService.hideTarget(id, cClass);
        },
        appCouponButton(coupon) {
            this.code = coupon.code;
        },
        applyFirstPurchaseCoupon() {
            if (this.firstPurchaseCoupon) {
                this.code = this.firstPurchaseCoupon.code;
                this.couponChecking();
            }
        },
        couponChecking() {
            this.loading.isActive = true;
            this.$store.dispatch('frontendCoupon/checking', {
                total: this.subtotal,
                code: this.code
            }).then(res => {
                this.error = "";
                this.$store.dispatch("frontendCart/coupon", res.data.data);
                this.loading.isActive = false;
                this.hideTarget('coupon-modal', 'modal-active');
                alertService.success(this.$t('message.coupon_add'));
            }).catch((err) => {
                this.loading.isActive = false;
                this.error = err.response.data.message;
            });
        },
        destroyCoupon() {
            this.loading.isActive = true;
            this.$store.dispatch("frontendCart/destroyCoupon").then(res => {
                this.code = null;
                this.loading.isActive = false;
                alertService.success(this.$t('message.coupon_delete'));
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err);
            });
        }
    }
}
</script>