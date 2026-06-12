<template>
    <LoadingComponent :props="loading" />

    <div class="col-12">
        <div id="promotionSection" class="db-tab-div active">
            <div class="mb-4">
                <router-link :to="{ name: 'admin.promotion-sections' }"
                    class="db-btn h-[37px] text-white bg-gray-500 hover:bg-gray-600">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    <span>Voltar</span>
                </router-link>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">
                <button
                    @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'promotionSectionInformation')"
                    class="tab-action active w-full flex items-center gap-3 h-10 px-4 rounded-lg bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-info lab-font-size-16"></i>
                    {{ $t("label.information") }}
                </button>

                <button type="button"
                    @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'promotionSectionPromotions')"
                    class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-line-promotion lab-font-size-16"></i>
                    Promoções
                </button>
            </div>

            <div class="db-card tab-content active" id="promotionSectionInformation">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.information') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.name') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ section.name }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.slug') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ section.slug }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Layout</span>
                                <span class="db-list-item-text w-full sm:w-1/2">
                                    {{ section.layout_type === 'carousel' ? 'Carrossel' : 'Grade' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5" v-if="section.layout_type === 'grid'">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Colunas por linha</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ section.columns || 'Auto' }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.status') }}</span>
                                <span class="db-list-item-text">
                                    <span :class="statusClass(section.status)">
                                        {{ enums.statusEnumArray[section.status] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="db-card tab-content" id="promotionSectionPromotions">
                <PromotionSectionPromotionListComponent :promotionSection="parseInt($route.params.id)" />
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import appService from "../../../services/appService";
import targetService from "../../../services/targetService";
import statusEnum from "../../../enums/modules/statusEnum";
import PromotionSectionPromotionListComponent from "./promotion/PromotionSectionPromotionListComponent";

export default {
    name: "PromotionSectionShowComponent",
    components: {
        LoadingComponent,
        PromotionSectionPromotionListComponent
    },
    data() {
        return {
            loading: { isActive: false },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
        }
    },
    computed: {
        section: function () {
            return this.$store.getters['promotionSection/show'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('promotionSection/show', this.$route.params.id).then(() => {
            this.loading.isActive = false;
        }).catch(() => {
            this.loading.isActive = false;
        });
    },
    methods: {
        multiTargets: function (event, commonBtnClass, commonDivClass, targetID) {
            targetService.multiTargets(event, commonBtnClass, commonDivClass, targetID);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
    }
}
</script>
