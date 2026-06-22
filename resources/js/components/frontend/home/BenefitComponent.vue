<template>
    <LoadingComponent :props="loading" />
    <section v-if="benefits.length > 0" style="margin-top: -30px; margin-bottom: 20px; position: relative; z-index: 10; background: #f5f5f0; border-top: 2px solid #c5c5b8; border-bottom: 2px solid #c5c5b8;">
        <div class="container">
            <div class="flex items-stretch justify-center">
                <div v-for="(benefit, index) in benefits" :key="benefit.id"
                    class="flex items-center gap-3 px-6 py-3"
                    :style="index > 0 ? 'border-left: 1px solid #c5c5b8' : ''">
                    <img :src="benefit.thumb" alt="benefit" class="w-8 h-8 object-contain flex-shrink-0" style="opacity: 0.7;">
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wide text-gray-700 leading-tight">{{ benefit.title }}</h4>
                        <p class="text-xs text-gray-500 leading-tight mt-0.5">{{ benefit.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import statusEnum from "../../../enums/modules/statusEnum";
import LoadingComponent from "../components/LoadingComponent";

export default {
    name: "BenefitComponent",
    components: {
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            }
        }
    },
    computed: {
        benefits: function () {
            return this.$store.getters["frontendBenefit/lists"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendBenefit/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            status: statusEnum.ACTIVE,
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    }
}
</script>

