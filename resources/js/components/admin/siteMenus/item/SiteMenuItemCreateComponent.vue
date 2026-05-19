<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" @click.self="reset"
        class="fixed inset-0 z-50 bg-black/50 duration-500 transition-all invisible opacity-0">
        <div class="w-full max-w-xl h-screen overflow-x-hidden thin-scrolling bg-white ms-auto ltr:translate-x-full rtl:-translate-x-full">
            <div class="drawer-header">
                <h3 class="drawer-title">
                    {{ isEditing ? 'Editar Item' : 'Novo Item' }}
                    <span v-if="currentParentId" class="text-sm font-normal text-gray-400 ml-1">(sub-item)</span>
                </h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">

                        <!-- Title -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">Título</label>
                            <input v-model="form.title" :class="errors.title ? 'invalid' : ''"
                                type="text" class="db-field-control" placeholder="Ex: Início" />
                            <small class="db-field-alert" v-if="errors.title">{{ errors.title[0] }}</small>
                        </div>

                        <!-- Type -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">Tipo</label>
                            <select v-model="form.type" class="db-field-control" @change="onTypeChange">
                                <option value="custom">Link Personalizado</option>
                                <option value="category">Categoria Específica</option>
                                <option value="page">Página</option>
                                <option v-if="!currentParentId" value="categories_all">Todas as Categorias (Dropdown)</option>
                            </select>
                        </div>

                        <!-- URL (custom) -->
                        <div v-if="form.type === 'custom'" class="form-col-12">
                            <label class="db-field-title">URL</label>
                            <input v-model="form.url" :class="errors.url ? 'invalid' : ''"
                                type="text" class="db-field-control" placeholder="Ex: /offers ou https://..." />
                            <small class="db-field-alert" v-if="errors.url">{{ errors.url[0] }}</small>
                        </div>

                        <!-- Category -->
                        <div v-if="form.type === 'category'" class="form-col-12">
                            <label class="db-field-title required">Categoria</label>
                            <select v-model="form.reference_id" class="db-field-control">
                                <option :value="null">— Selecione —</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <small class="db-field-alert" v-if="errors.reference_id">{{ errors.reference_id[0] }}</small>
                        </div>

                        <!-- Page -->
                        <div v-if="form.type === 'page'" class="form-col-12">
                            <label class="db-field-title required">Página</label>
                            <select v-model="form.reference_id" class="db-field-control">
                                <option :value="null">— Selecione —</option>
                                <option v-for="pg in pages" :key="pg.id" :value="pg.id">{{ pg.title }}</option>
                            </select>
                            <small class="db-field-alert" v-if="errors.reference_id">{{ errors.reference_id[0] }}</small>
                        </div>

                        <!-- Target -->
                        <div v-if="form.type !== 'categories_all'" class="form-col-12 sm:form-col-6">
                            <label class="db-field-title">Abrir em</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.target" value="_self"
                                            id="targetSelf" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="targetSelf" class="db-field-label">Mesma aba</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.target" value="_blank"
                                            id="targetBlank" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="targetBlank" class="db-field-label">Nova aba</label>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ $t('label.status') }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.status" :value="1"
                                            id="itemActive" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="itemActive" class="db-field-label">{{ $t('label.active') }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.status" :value="0"
                                            id="itemInactive" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="itemInactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t('label.save') }}</span>
                                </button>
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
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
</template>

<script>
import SmSidebarModalCreateComponent from "../../components/buttons/SmSidebarModalCreateComponent";
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import { useCanvas } from "../../../../composables/canvas";

export default {
    name: "SiteMenuItemCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent },
    props: {
        menuId:     { type: Number, required: true },
        parentId:   { type: Number, default: null },
        pages:      { type: Array, default: () => [] },
        categories: { type: Array, default: () => [] },
    },
    emits: ['saved'],
    data() {
        return {
            loading: { isActive: false },
            form: {
                title: '', type: 'custom', reference_id: null,
                url: '', target: '_self', status: 1,
            },
            currentParentId: null,
            errors: {},
        };
    },
    computed: {
        temp()      { return this.$store.getters['siteMenuItem/temp']; },
        isEditing() { return this.temp.isEditing; },
        addButton() { return { title: 'Adicionar Item' }; },
    },
    mounted() {
        window.addEventListener('open-item-for-edit', this.onEditItem);
        window.addEventListener('open-item-as-child', this.onAddChild);
    },
    beforeUnmount() {
        window.removeEventListener('open-item-for-edit', this.onEditItem);
        window.removeEventListener('open-item-as-child', this.onAddChild);
    },
    methods: {
        onEditItem(e) {
            const item = e.detail;
            this.currentParentId = item.parent_id;
            this.form = {
                title:        item.title,
                type:         item.type,
                reference_id: item.reference_id,
                url:          item.url || '',
                target:       item.target || '_self',
                status:       item.status,
            };
            this.errors = {};
            this.$store.dispatch('siteMenuItem/edit', { id: item.id, menu_id: this.menuId });
            appService.sideDrawerShow();
        },
        onAddChild(e) {
            this.currentParentId = e.detail.parentId;
            this.$store.dispatch('siteMenuItem/reset');
            this.form = { title: '', type: 'custom', reference_id: null, url: '', target: '_self', status: 1 };
            this.errors = {};
            appService.sideDrawerShow();
        },
        onTypeChange() {
            this.form.reference_id = null;
            this.form.url = '';
        },
        reset() {
            useCanvas().closeCanvas('sidebar');
            this.$store.dispatch('siteMenuItem/reset');
            this.currentParentId = null;
            this.errors = {};
            this.form = { title: '', type: 'custom', reference_id: null, url: '', target: '_self', status: 1 };
        },
        save() {
            try {
                const isEdit = this.temp.isEditing;
                this.loading.isActive = true;
                const payload = {
                    form: {
                        ...this.form,
                        parent_id: this.currentParentId,
                    },
                    menu_id: this.menuId,
                };
                this.$store.dispatch('siteMenuItem/save', payload).then(() => {
                    useCanvas().closeCanvas('sidebar');
                    this.loading.isActive = false;
                    alertService.successFlip(isEdit ? 1 : 0, 'Item');
                    this.reset();
                    this.$emit('saved');
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response?.data?.errors || {};
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
