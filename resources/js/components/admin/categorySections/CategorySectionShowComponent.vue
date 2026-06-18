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
                        <div v-if="categorySection.type === 'categories'" class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Template do Item</span>
                                <span class="db-list-item-text w-full sm:w-1/2">
                                    {{ categorySection.item_template === 'circle' ? 'Círculo' : 'Card' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">Layout da Linha</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ enums.rowLayoutLabels[categorySection.row_layout] || 'Justificado' }}</span>
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

                        <!-- Template do Item (só para tipo Categorias) -->
                        <div class="form-col-12" v-if="editForm.type === 'categories'">
                            <label class="db-field-title">Template do Item</label>
                            <div class="flex gap-4 mt-1">
                                <!-- Card -->
                                <button type="button"
                                    @click="editForm.item_template = 'card'"
                                    :class="[
                                        'flex flex-col items-center gap-2 p-3 rounded-xl border-2 transition cursor-pointer',
                                        editForm.item_template === 'card'
                                            ? 'border-primary bg-primary/5'
                                            : 'border-gray-200 bg-white hover:border-gray-300'
                                    ]"
                                >
                                    <svg width="80" height="72" viewBox="0 0 80 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="2" y="2" width="76" height="52" rx="8" fill="#e5e7eb"/>
                                        <rect x="12" y="60" width="56" height="8" rx="4" fill="#d1d5db"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">Card</span>
                                </button>

                                <!-- Círculo -->
                                <button type="button"
                                    @click="editForm.item_template = 'circle'"
                                    :class="[
                                        'flex flex-col items-center gap-2 p-3 rounded-xl border-2 transition cursor-pointer',
                                        editForm.item_template === 'circle'
                                            ? 'border-primary bg-primary/5'
                                            : 'border-gray-200 bg-white hover:border-gray-300'
                                    ]"
                                >
                                    <svg width="80" height="72" viewBox="0 0 80 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="40" cy="30" r="26" fill="#e5e7eb"/>
                                        <rect x="12" y="62" width="56" height="8" rx="4" fill="#d1d5db"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">Círculo</span>
                                </button>
                            </div>
                        </div>

                        <!-- Layout da Linha -->
                        <div class="form-col-12">
                            <label class="db-field-title">Layout da Linha</label>
                            <div class="flex gap-4 mt-1">
                                <!-- Esquerda -->
                                <button type="button"
                                    @click="editForm.row_layout = 'left'"
                                    :class="[
                                        'flex flex-col items-center gap-2 p-3 rounded-xl border-2 transition cursor-pointer',
                                        editForm.row_layout === 'left'
                                            ? 'border-primary bg-primary/5'
                                            : 'border-gray-200 bg-white hover:border-gray-300'
                                    ]"
                                >
                                    <svg width="80" height="56" viewBox="0 0 80 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="4" y="4" width="28" height="10" rx="4" fill="#9ca3af"/>
                                        <rect x="4" y="19" width="22" height="10" rx="4" fill="#9ca3af"/>
                                        <rect x="4" y="34" width="30" height="10" rx="4" fill="#9ca3af"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">Esquerda</span>
                                </button>

                                <!-- Centralizado -->
                                <button type="button"
                                    @click="editForm.row_layout = 'center'"
                                    :class="[
                                        'flex flex-col items-center gap-2 p-3 rounded-xl border-2 transition cursor-pointer',
                                        editForm.row_layout === 'center'
                                            ? 'border-primary bg-primary/5'
                                            : 'border-gray-200 bg-white hover:border-gray-300'
                                    ]"
                                >
                                    <svg width="80" height="56" viewBox="0 0 80 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="26" y="4" width="28" height="10" rx="4" fill="#9ca3af"/>
                                        <rect x="29" y="19" width="22" height="10" rx="4" fill="#9ca3af"/>
                                        <rect x="25" y="34" width="30" height="10" rx="4" fill="#9ca3af"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">Centralizado</span>
                                </button>

                                <!-- Justificado -->
                                <button type="button"
                                    @click="editForm.row_layout = 'justified'"
                                    :class="[
                                        'flex flex-col items-center gap-2 p-3 rounded-xl border-2 transition cursor-pointer',
                                        editForm.row_layout === 'justified'
                                            ? 'border-primary bg-primary/5'
                                            : 'border-gray-200 bg-white hover:border-gray-300'
                                    ]"
                                >
                                    <svg width="80" height="56" viewBox="0 0 80 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="4" y="4" width="72" height="10" rx="4" fill="#9ca3af"/>
                                        <rect x="4" y="19" width="72" height="10" rx="4" fill="#9ca3af"/>
                                        <rect x="4" y="34" width="72" height="10" rx="4" fill="#9ca3af"/>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">Justificado</span>
                                </button>
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
                item_template: "card",
                row_layout: "justified",
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
                rowLayoutLabels: {
                    left: 'Esquerda',
                    center: 'Centralizado',
                    justified: 'Justificado',
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
                item_template: this.categorySection.item_template || 'card',
                row_layout: this.categorySection.row_layout || 'justified',
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
