<template>
    <LoadingComponent :props="loading" />

    <div class="col-12">
        <div id="categorySection" class="db-tab-div active">
            <div class="mb-4">
                <router-link :to="{ name: 'admin.category-sections' }"
                    class="db-btn h-[37px] text-white bg-gray-500 hover:bg-gray-600">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                    <span>Voltar</span>
                </router-link>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">
                <button
                    @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'categorySectionInformation')"
                    class="tab-action active w-full flex items-center gap-3 h-10 px-4 rounded-lg bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-info lab-font-size-16"></i>
                    {{ $t("label.information") }}
                </button>

                <button v-if="categorySection.type === 'categories'" type="button"
                    @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'categorySectionCategories')"
                    class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-line-item-categories lab-font-size-16"></i>
                    Categorias
                </button>

                <button v-if="categorySection.type === 'products'" type="button"
                    @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'categorySectionProducts')"
                    class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-line-shopping-bag lab-font-size-16"></i>
                    Produtos
                </button>

                <button v-if="categorySection.type === 'banner'" type="button"
                    @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'categorySectionBanners')"
                    class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-line-promotion lab-font-size-16"></i>
                    Banners
                </button>
            </div>

            <div class="db-card tab-content active" id="categorySectionInformation">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.information') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.name') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ categorySection.name }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.slug') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ categorySection.slug }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Tipo</span>
                                <span class="db-list-item-text w-full sm:w-1/2">
                                    <span v-if="categorySection.type === 'categories'" class="db-badge" style="background:#dbeafe;color:#1d4ed8">Categorias</span>
                                    <span v-else-if="categorySection.type === 'products'" class="db-badge" style="background:#dcfce7;color:#15803d">Produtos</span>
                                    <span v-else-if="categorySection.type === 'banner'" class="db-badge" style="background:#f3e8ff;color:#7e22ce">Banner</span>
                                    <span v-else class="text-gray-400">—</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t('label.status') }}</span>
                                <span class="db-list-item-text">
                                    <span :class="statusClass(categorySection.status)">
                                        {{ enums.statusEnumArray[categorySection.status] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="categorySection.type === 'categories'" class="db-card tab-content" id="categorySectionCategories">
                <CategorySectionCategoryListComponent :categorySection="parseInt($route.params.id)" />
            </div>

            <div v-if="categorySection.type === 'products'" class="db-card tab-content" id="categorySectionProducts">
                <CategorySectionProductListComponent :categorySection="parseInt($route.params.id)" />
            </div>

            <div v-if="categorySection.type === 'banner'" class="db-card tab-content" id="categorySectionBanners">
                <CategorySectionPromotionListComponent :categorySection="parseInt($route.params.id)" />
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import appService from "../../../services/appService";
import targetService from "../../../services/targetService";
import statusEnum from "../../../enums/modules/statusEnum";
import CategorySectionCategoryListComponent from "./category/CategorySectionCategoryListComponent";
import CategorySectionProductListComponent from "./product/CategorySectionProductListComponent";
import CategorySectionPromotionListComponent from "./promotion/CategorySectionPromotionListComponent";

export default {
    name: "CategorySectionShowComponent",
    components: {
        LoadingComponent,
        CategorySectionCategoryListComponent,
        CategorySectionProductListComponent,
        CategorySectionPromotionListComponent,
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
        categorySection: function () {
            return this.$store.getters['categorySection/show'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('categorySection/show', this.$route.params.id).then(() => {
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
