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

            <!-- Aba Informações -->
            <div class="db-card tab-content active" id="categorySectionInformation">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.information') }}</h3>
                    <div class="db-card-filter">
                        <button v-if="!editing" @click="startEdit" type="button"
                            class="db-btn h-[37px] text-white bg-primary">
                            <i class="lab lab-line-edit lab-font-size-16"></i>
                            <span>{{ $t('button.edit') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Visualização -->
                <div v-if="!editing" class="db-card-body">
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
                                <span class="db-list-item-title w-full sm:w-1/2">Tag do Título</span>
                                <span class="db-list-item-text w-full sm:w-1/2 uppercase">{{ categorySection.title_tag || 'h2' }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Posição do Título</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ enums.positionLabels[categorySection.title_position] || 'Esquerda' }}</span>
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

                <!-- Formulário de Edição -->
                <div v-else class="db-card-body">
                    <div class="form-row">
                        <!-- Nome -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="editName" class="db-field-title required">{{ $t('label.name') }}</label>
                            <input v-model="editForm.name" id="editName" type="text"
                                class="db-field-control" :class="editErrors.name ? 'invalid' : ''" />
                            <small class="db-field-alert" v-if="editErrors.name">{{ editErrors.name[0] }}</small>
                        </div>

                        <!-- Tipo -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">Tipo</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.type" id="edit_type_categories"
                                            value="categories" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_type_categories" class="db-field-label">Categorias</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.type" id="edit_type_products"
                                            value="products" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_type_products" class="db-field-label">Produtos</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.type" id="edit_type_banner"
                                            value="banner" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_type_banner" class="db-field-label">Banner</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tag do Título -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="edit_title_tag" class="db-field-title">Tag do Título</label>
                            <select v-model="editForm.title_tag" id="edit_title_tag" class="db-field-control">
                                <option value="h1">H1</option>
                                <option value="h2">H2</option>
                                <option value="custom">Custom (sem tag semântica)</option>
                            </select>
                        </div>

                        <!-- Posição do Título -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title">Posição do Título</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.title_position" id="edit_pos_left"
                                            value="left" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_pos_left" class="db-field-label">Esquerda</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.title_position" id="edit_pos_center"
                                            value="center" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_pos_center" class="db-field-label">Centro</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.title_position" id="edit_pos_right"
                                            value="right" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_pos_right" class="db-field-label">Direita</label>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ $t('label.status') }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.status" id="edit_active"
                                            :value="enums.statusEnum.ACTIVE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_active" class="db-field-label">{{ $t('label.active') }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="editForm.status" id="edit_inactive"
                                            :value="enums.statusEnum.INACTIVE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="edit_inactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="form-col-12">
                            <div class="flex gap-3 mt-2">
                                <button type="button" @click="cancelEdit"
                                    class="db-btn py-2 text-white bg-gray-500">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t('button.cancel') }}</span>
                                </button>
                                <button type="button" @click="saveEdit"
                                    class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t('button.save') }}</span>
                                </button>
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
import alertService from "../../../services/alertService";
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
            editing: false,
            editForm: {
                name: "",
                type: "categories",
                title_tag: "h2",
                title_position: "left",
                status: statusEnum.ACTIVE,
            },
            editErrors: {},
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
                positionLabels: {
                    left: 'Esquerda',
                    center: 'Centro',
                    right: 'Direita',
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
        startEdit: function () {
            this.editForm = {
                name: this.categorySection.name,
                type: this.categorySection.type || 'categories',
                title_tag: this.categorySection.title_tag || 'h2',
                title_position: this.categorySection.title_position || 'left',
                status: this.categorySection.status,
            };
            this.editErrors = {};
            this.editing = true;
        },
        cancelEdit: function () {
            this.editing = false;
            this.editErrors = {};
        },
        saveEdit: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch('categorySection/edit', this.$route.params.id).then(() => {
                    this.$store.dispatch('categorySection/save', {
                        form: this.editForm,
                        search: { paginate: 0 },
                    }).then(() => {
                        this.$store.dispatch('categorySection/show', this.$route.params.id).then(() => {
                            this.loading.isActive = false;
                            this.editing = false;
                            this.editErrors = {};
                            alertService.successFlip(1, "Seção");
                        }).catch(() => {
                            this.loading.isActive = false;
                        });
                    }).catch((err) => {
                        this.loading.isActive = false;
                        if (err.response?.data?.errors) {
                            this.editErrors = err.response.data.errors;
                        } else {
                            alertService.error(err.response?.data?.message ?? "Erro ao salvar.");
                        }
                    });
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    }
}
</script>
