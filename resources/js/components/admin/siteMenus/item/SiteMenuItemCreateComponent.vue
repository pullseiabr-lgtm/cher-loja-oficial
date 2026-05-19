<template>
    <div id="site-menu-item-drawer" class="db-sidebar-canvas">
        <div class="db-sidebar-canvas-head">
            <h3>{{ isEditing ? 'Editar Item' : 'Novo Item' }}{{ parentId ? ' (sub-item)' : '' }}</h3>
            <button @click="close" type="button" class="fa-solid fa-xmark"></button>
        </div>
        <div class="db-sidebar-canvas-body">

            <!-- Title -->
            <div class="form-row">
                <label class="db-field-title required">Título</label>
                <input v-model="form.title" type="text" class="db-field-control" placeholder="Ex: Início" />
                <small v-if="errors.title" class="db-field-alert">{{ errors.title[0] }}</small>
            </div>

            <!-- Type -->
            <div class="form-row mt-4">
                <label class="db-field-title required">Tipo</label>
                <select v-model="form.type" class="db-field-control" @change="onTypeChange">
                    <option value="custom">Link Personalizado</option>
                    <option value="category">Categoria Específica</option>
                    <option value="page">Página</option>
                    <option v-if="!parentId" value="categories_all">Todas as Categorias (Dropdown)</option>
                </select>
            </div>

            <!-- URL (custom) -->
            <div v-if="form.type === 'custom'" class="form-row mt-4">
                <label class="db-field-title">URL</label>
                <input v-model="form.url" type="text" class="db-field-control" placeholder="Ex: /offers ou https://..." />
                <small v-if="errors.url" class="db-field-alert">{{ errors.url[0] }}</small>
            </div>

            <!-- Category (category) -->
            <div v-if="form.type === 'category'" class="form-row mt-4">
                <label class="db-field-title required">Categoria</label>
                <select v-model="form.reference_id" class="db-field-control">
                    <option :value="null">— Selecione —</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <small v-if="errors.reference_id" class="db-field-alert">{{ errors.reference_id[0] }}</small>
            </div>

            <!-- Page (page) -->
            <div v-if="form.type === 'page'" class="form-row mt-4">
                <label class="db-field-title required">Página</label>
                <select v-model="form.reference_id" class="db-field-control">
                    <option :value="null">— Selecione —</option>
                    <option v-for="pg in pages" :key="pg.id" :value="pg.id">{{ pg.title }}</option>
                </select>
                <small v-if="errors.reference_id" class="db-field-alert">{{ errors.reference_id[0] }}</small>
            </div>

            <!-- Target -->
            <div v-if="form.type !== 'categories_all'" class="form-row mt-4">
                <label class="db-field-title">Abrir em</label>
                <div class="db-field-radio-group">
                    <label class="db-field-radio">
                        <input type="radio" v-model="form.target" value="_self" class="db-field-radio-input" />
                        <span class="db-field-radio-label">Mesma aba</span>
                    </label>
                    <label class="db-field-radio">
                        <input type="radio" v-model="form.target" value="_blank" class="db-field-radio-input" />
                        <span class="db-field-radio-label">Nova aba</span>
                    </label>
                </div>
            </div>

            <!-- Status -->
            <div class="form-row mt-4">
                <label class="db-field-title">{{ $t('label.status') }}</label>
                <div class="db-field-radio-group">
                    <label class="db-field-radio">
                        <input type="radio" v-model="form.status" :value="1" class="db-field-radio-input" />
                        <span class="db-field-radio-label">{{ $t('label.active') }}</span>
                    </label>
                    <label class="db-field-radio">
                        <input type="radio" v-model="form.status" :value="0" class="db-field-radio-input" />
                        <span class="db-field-radio-label">{{ $t('label.inactive') }}</span>
                    </label>
                </div>
            </div>

            <div class="mt-6">
                <button @click="save" type="button" class="db-btn h-[37px] text-white bg-primary">
                    {{ $t('button.save') }}
                </button>
            </div>
        </div>
    </div>
    <div id="site-menu-item-drawer-overlay" class="db-sidebar-canvas-overlay" @click="close"></div>
</template>

<script>
import targetService from "../../../../services/targetService";
import alertService from "../../../../services/alertService";

export default {
    name: "SiteMenuItemCreateComponent",
    props: {
        menuId:    { type: Number, required: true },
        parentId:  { type: Number, default: null },
        pages:     { type: Array, default: () => [] },
        categories:{ type: Array, default: () => [] },
    },
    emits: ['saved'],
    data() {
        return {
            form: {
                title: '', type: 'custom', reference_id: null,
                url: '', target: '_self', status: 1,
            },
            errors: {},
        };
    },
    computed: {
        temp()      { return this.$store.getters['siteMenuItem/temp']; },
        isEditing() { return this.temp.isEditing; },
    },
    mounted() {
        window.addEventListener('open-site-menu-item-drawer', this.onOpen);
    },
    beforeUnmount() {
        window.removeEventListener('open-site-menu-item-drawer', this.onOpen);
    },
    methods: {
        onOpen(event) {
            this.errors = {};
            const item = event.detail;
            if (item && this.temp.isEditing) {
                this.form = {
                    title:        item.title,
                    type:         item.type,
                    reference_id: item.reference_id,
                    url:          item.url || '',
                    target:       item.target || '_self',
                    status:       item.status,
                };
            } else {
                this.form = { title: '', type: 'custom', reference_id: null, url: '', target: '_self', status: 1 };
            }
            targetService.showTarget('site-menu-item-drawer', 'canvas-active');
        },
        onTypeChange() {
            this.form.reference_id = null;
            this.form.url          = '';
        },
        close() {
            targetService.hideTarget('site-menu-item-drawer', 'canvas-active');
            this.$store.dispatch('siteMenuItem/reset');
        },
        save() {
            this.errors = {};
            const payload = {
                ...this.form,
                parent_id: this.parentId,
                menu_id:   this.menuId,
            };
            this.$store.dispatch('siteMenuItem/save', { form: payload, menu_id: this.menuId })
                .then(() => {
                    alertService.successFlip(null, 'Item');
                    this.close();
                    this.$emit('saved');
                })
                .catch(err => {
                    if (err.response?.data?.errors) this.errors = err.response.data.errors;
                    else alertService.error(err.response?.data?.message || 'Erro');
                });
        },
    },
}
</script>
