<template>
    <LoadingComponent :props="loading" />

    <!-- Drawer: Create / Edit -->
    <div id="sidebar" @click.self="resetDrawer"
        class="fixed inset-0 z-50 bg-black/50 duration-500 transition-all invisible opacity-0">
        <div class="w-full max-w-xl h-screen overflow-y-auto thin-scrolling bg-white ms-auto ltr:translate-x-full rtl:-translate-x-full">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ drawerTitle }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="resetDrawer"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="saveTestimonial" enctype="multipart/form-data">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-col-12">
                            <label for="t-name" class="db-field-title required">Nome</label>
                            <input v-model="form.name" id="t-name" type="text" class="db-field-control"
                                :class="errors.name ? 'invalid' : ''" placeholder="Ex: Maria Silva" />
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <!-- Content -->
                        <div class="form-col-12">
                            <label for="t-content" class="db-field-title required">Depoimento</label>
                            <textarea v-model="form.content" id="t-content" rows="4" class="db-field-control"
                                :class="errors.content ? 'invalid' : ''"
                                placeholder="Escreva o depoimento..."></textarea>
                            <small class="db-field-alert" v-if="errors.content">{{ errors.content[0] }}</small>
                        </div>

                        <!-- Rating -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">Avaliação (estrelas)</label>
                            <div class="flex items-center gap-2 mt-1">
                                <button v-for="star in 5" :key="star" type="button"
                                    @click="form.rating = star"
                                    :class="star <= form.rating ? 'text-yellow-400' : 'text-gray-300'"
                                    class="text-2xl leading-none transition-colors duration-150 hover:text-yellow-400">
                                    &#9733;
                                </button>
                                <span class="text-sm text-gray-500 ml-1">({{ form.rating }}/5)</span>
                            </div>
                            <small class="db-field-alert" v-if="errors.rating">{{ errors.rating[0] }}</small>
                        </div>

                        <!-- Status -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ $t('label.status') }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.status" id="t-active"
                                            :value="statusEnum.ACTIVE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="t-active" class="db-field-label">{{ $t('label.active') }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.status" id="t-inactive"
                                            :value="statusEnum.INACTIVE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="t-inactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="form-col-12">
                            <label class="db-field-title">Foto (opcional)</label>
                            <div class="flex items-start gap-4">
                                <div v-if="imagePreview || (editItem && editItem.image)"
                                    class="w-16 h-16 rounded-full overflow-hidden border-2 border-primary flex-shrink-0">
                                    <img :src="imagePreview || editItem.image" alt="foto"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div v-else
                                    class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 border-2 border-dashed border-gray-300">
                                    <i class="fa-solid fa-user text-gray-400 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="t-image" accept="image/*" ref="imageInput"
                                        class="db-field-control" @change="handleImageChange" />
                                    <small class="text-gray-500 text-xs mt-1 block">JPG, PNG, WEBP — máx 2MB</small>
                                    <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="t-sort" class="db-field-title">Ordem</label>
                            <input v-model.number="form.sort_order" id="t-sort" type="number" min="0"
                                class="db-field-control" placeholder="0" />
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-2">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t('label.save') }}</span>
                                </button>
                                <button type="button" class="modal-btn-outline modal-close" @click="resetDrawer">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t('button.close') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Page -->
    <div class="col-12">
        <!-- Header breadcrumb -->
        <div class="flex items-center gap-2 mb-4 text-sm text-gray-500">
            <router-link :to="{ name: 'admin.settings.siteSection' }" class="hover:text-primary transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i>Seções do Site
            </router-link>
            <span>/</span>
            <span class="text-gray-700 font-medium">Depoimentos</span>
        </div>

        <!-- Global Settings Card -->
        <div class="db-card mb-5">
            <div class="db-card-header">
                <h3 class="db-card-title">Configurações da Seção</h3>
            </div>
            <div class="db-card-body">
                <form @submit.prevent="saveSetting">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">Exibir seção na Home</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="setting.testimonials_section_status"
                                            id="s-enable" :value="activityEnum.ENABLE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="s-enable" class="db-field-label">Ativo</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="setting.testimonials_section_status"
                                            id="s-disable" :value="activityEnum.DISABLE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="s-disable" class="db-field-label">Inativo</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="s-perpage" class="db-field-title required">Cards por página</label>
                            <input v-model.number="setting.testimonials_section_per_page" id="s-perpage"
                                type="number" min="1" max="12" class="db-field-control" />
                            <small class="text-gray-500 text-xs mt-1 block">Número de depoimentos visíveis por vez no carrossel (1-12)</small>
                        </div>

                        <div class="form-col-12">
                            <button type="submit" class="db-btn py-2 text-white bg-primary">
                                <i class="lab lab-fill-save mr-1"></i>
                                Salvar Configurações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Testimonials List -->
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">Depoimentos</h3>
                <div class="db-card-filter">
                    <button class="db-btn py-2 text-white bg-primary" @click="openCreateDrawer">
                        <i class="lab lab-fill-add-circle lab-font-size-22"></i>
                        <span>Adicionar</span>
                    </button>
                </div>
            </div>

            <div class="db-table-responsive">
                <table class="db-table stripe" id="print">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">Foto</th>
                            <th class="db-table-head-th">Nome</th>
                            <th class="db-table-head-th">Avaliação</th>
                            <th class="db-table-head-th">Status</th>
                            <th class="db-table-head-th hidden-print">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="testimonials.length > 0">
                        <tr class="db-table-body-tr" v-for="item in testimonials" :key="item.id">
                            <td class="db-table-body-td">
                                <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                                    <img :src="item.image" :alt="item.name"
                                        class="w-full h-full object-cover" />
                                </div>
                            </td>
                            <td class="db-table-body-td">
                                <div class="font-medium text-gray-800">{{ item.name }}</div>
                                <div class="text-xs text-gray-500 max-w-xs truncate">{{ item.content }}</div>
                            </td>
                            <td class="db-table-body-td">
                                <div class="flex items-center gap-0.5">
                                    <span v-for="star in 5" :key="star"
                                        :class="star <= item.rating ? 'text-yellow-400' : 'text-gray-200'"
                                        class="text-base leading-none">&#9733;</span>
                                </div>
                            </td>
                            <td class="db-table-body-td">
                                <span :class="statusClass(item.status)">
                                    {{ item.status === statusEnum.ACTIVE ? $t('label.active') : $t('label.inactive') }}
                                </span>
                            </td>
                            <td class="db-table-body-td hidden-print">
                                <div class="flex justify-start items-center gap-1.5">
                                    <SmIconSidebarModalEditComponent @click="openEditDrawer(item)" />
                                    <SmIconDeleteComponent @click="deleteTestimonial(item.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="db-table-body" v-else>
                        <tr class="db-table-body-tr">
                            <td class="db-table-body-td text-center" colspan="5">
                                <div class="p-8">
                                    <div class="max-w-[200px] mx-auto mt-2 opacity-50">
                                        <img class="w-full h-full"
                                            :src="ENV.API_URL+'/images/default/not-found/not_found.png'"
                                            alt="Nenhum depoimento" />
                                    </div>
                                    <span class="d-block mt-3 text-base text-gray-500">Nenhum depoimento cadastrado</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6"
                v-if="testimonials.length > 0">
                <PaginationSMBox :pagination="pagination" :method="loadList" />
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <PaginationTextComponent :props="{ page: paginationPage }" />
                    <PaginationBox :pagination="pagination" :method="loadList" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from '../../components/LoadingComponent';
import SmIconSidebarModalEditComponent from '../../components/buttons/SmIconSidebarModalEditComponent';
import SmIconDeleteComponent from '../../components/buttons/SmIconDeleteComponent';
import PaginationSMBox from '../../components/pagination/PaginationSMBox';
import PaginationBox from '../../components/pagination/PaginationBox';
import PaginationTextComponent from '../../components/pagination/PaginationTextComponent';
import alertService from '../../../../services/alertService';
import appService from '../../../../services/appService';
import statusEnum from '../../../../enums/modules/statusEnum';
import activityEnum from '../../../../enums/modules/activityEnum';
import { useCanvas } from '../../../../composables/canvas';
import ENV from '../../../../config/env';

export default {
    name: 'TestimonialsComponent',
    components: {
        LoadingComponent,
        SmIconSidebarModalEditComponent,
        SmIconDeleteComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
    },
    data() {
        return {
            loading: { isActive: false },
            statusEnum,
            activityEnum,
            ENV,
            search: {
                paginate: 1,
                page: 1,
                per_page: 10,
                order_column: 'sort_order',
                order_type: 'asc',
            },
            setting: {
                testimonials_section_status: activityEnum.DISABLE,
                testimonials_section_per_page: 3,
            },
            form: {
                name: '',
                content: '',
                rating: 5,
                status: statusEnum.ACTIVE,
                sort_order: 0,
                image: null,
            },
            imagePreview: null,
            editItem: null,
            errors: {},
        };
    },
    computed: {
        testimonials() { return this.$store.getters['testimonial/lists']; },
        pagination() { return this.$store.getters['testimonial/pagination']; },
        paginationPage() { return this.$store.getters['testimonial/page']; },
        isEditing() { return this.$store.getters['testimonial/temp'].isEditing; },
        drawerTitle() { return this.isEditing ? 'Editar Depoimento' : 'Novo Depoimento'; },
    },
    mounted() {
        this.loadList();
        this.loadSetting();
    },
    methods: {
        statusClass(status) { return appService.statusClass(status); },

        loadList(page = 1) {
            this.loading.isActive = true;
            this.search.page = page;
            this.$store.dispatch('testimonial/lists', this.search)
                .then(() => { this.loading.isActive = false; })
                .catch(() => { this.loading.isActive = false; });
        },

        loadSetting() {
            this.$store.dispatch('testimonial/getSetting').then((res) => {
                this.setting = { ...this.setting, ...res.data.data };
            }).catch(() => {});
        },

        saveSetting() {
            this.loading.isActive = true;
            this.$store.dispatch('testimonial/updateSetting', this.setting).then(() => {
                this.loading.isActive = false;
                alertService.successFlip(1, 'Configurações');
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err?.response?.data?.message || 'Erro ao salvar');
            });
        },

        openCreateDrawer() {
            this.editItem = null;
            this.imagePreview = null;
            this.errors = {};
            this.form = {
                name: '',
                content: '',
                rating: 5,
                status: statusEnum.ACTIVE,
                sort_order: 0,
                image: null,
            };
            this.$store.dispatch('testimonial/reset');
            useCanvas().openCanvas('sidebar');
        },

        openEditDrawer(item) {
            this.editItem = { ...item };
            this.imagePreview = null;
            this.errors = {};
            this.form = {
                name: item.name,
                content: item.content,
                rating: item.rating,
                status: item.status,
                sort_order: item.sort_order,
                image: null,
            };
            this.$store.dispatch('testimonial/edit', item.id);
            useCanvas().openCanvas('sidebar');
        },

        resetDrawer() {
            useCanvas().closeCanvas('sidebar');
            this.$store.dispatch('testimonial/reset');
            this.editItem = null;
            this.imagePreview = null;
            this.errors = {};
        },

        handleImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.form.image = file;
            const reader = new FileReader();
            reader.onload = (e) => { this.imagePreview = e.target.result; };
            reader.readAsDataURL(file);
        },

        saveTestimonial() {
            try {
                const formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('content', this.form.content);
                formData.append('rating', this.form.rating);
                formData.append('status', this.form.status);
                formData.append('sort_order', this.form.sort_order || 0);
                if (this.form.image) {
                    formData.append('image', this.form.image);
                }

                const tempId = this.$store.getters['testimonial/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('testimonial/save', {
                    form: formData,
                    search: this.search,
                }).then(() => {
                    useCanvas().closeCanvas('sidebar');
                    this.loading.isActive = false;
                    alertService.successFlip(tempId === null ? 0 : 1, 'Depoimento');
                    this.editItem = null;
                    this.imagePreview = null;
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err?.response?.data?.errors || {};
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },

        deleteTestimonial(id) {
            appService.destroyConfirmation().then(() => {
                this.loading.isActive = true;
                this.$store.dispatch('testimonial/destroy', { id, search: this.search })
                    .then(() => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, 'Depoimento');
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err?.response?.data?.message || 'Erro ao excluir');
                    });
            }).catch(() => {});
        },
    },
};
</script>

<style scoped>
@media print {
    .hidden-print { display: none !important; }
}
</style>
