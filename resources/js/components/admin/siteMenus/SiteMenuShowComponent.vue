<template>
    <div>
        <!-- Back Button -->
        <div class="mb-4">
            <router-link :to="{ name: 'admin.site-menus' }"
                class="db-btn h-[37px] text-white bg-gray-500 hover:bg-gray-600">
                <i class="fa-solid fa-arrow-left text-sm"></i>
                <span>Voltar</span>
            </router-link>
        </div>

        <!-- Menu Header Info -->
        <div class="db-card mb-5" v-if="menu">
            <div class="db-card-header flex items-center justify-between">
                <div>
                    <h3 class="db-card-title">{{ menu.name }}</h3>
                    <span :class="menu.location === 'header' ? 'db-badge db-badge-outline-primary' : 'db-badge db-badge-outline-secondary'" class="mt-1">
                        {{ menu.location === 'header' ? 'Header' : 'Footer' }}
                    </span>
                </div>
                <button @click="addItem(null)" type="button" class="db-btn py-2 text-white bg-primary">
                    <i class="lab lab-add-circle-line"></i>
                    <span>Adicionar Item</span>
                </button>
            </div>
        </div>

        <!-- Items Table -->
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">Itens do Menu</h3>
                <p class="text-sm text-gray-500 mt-1">Use as setas para reordenar. Itens com margem são sub-itens (filhos).</p>
            </div>
            <div class="db-card-body">
                <div class="db-table-responsive">
                    <table class="db-table">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th w-10">Ordem</th>
                                <th class="db-table-head-th">Título</th>
                                <th class="db-table-head-th">Tipo</th>
                                <th class="db-table-head-th">Destino / URL</th>
                                <th class="db-table-head-th">Status</th>
                                <th class="db-table-head-th">{{ $t('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body">
                            <template v-for="item in flatItems" :key="item.id">
                                <tr class="db-table-body-tr" :class="item.parent_id ? 'bg-gray-50' : ''">
                                    <td class="db-table-body-td">
                                        <div class="flex flex-col gap-1">
                                            <button @click="moveUp(item)" type="button" title="Mover para cima"
                                                class="text-xs p-0.5 rounded text-gray-500 hover:text-primary hover:bg-primary/10">
                                                <i class="fa-solid fa-chevron-up"></i>
                                            </button>
                                            <button @click="moveDown(item)" type="button" title="Mover para baixo"
                                                class="text-xs p-0.5 rounded text-gray-500 hover:text-primary hover:bg-primary/10">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="db-table-body-td">
                                        <span :style="item.parent_id ? 'margin-left:1.5rem' : ''">
                                            <i v-if="item.parent_id" class="fa-solid fa-turn-up fa-rotate-90 text-gray-300 mr-1 text-xs"></i>
                                            {{ item.title }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td">
                                        <span class="db-badge" :class="typeClass(item.type)">
                                            {{ typeLabel(item.type) }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td text-sm text-gray-500 max-w-xs truncate">
                                        <template v-if="item.type === 'category' || item.type === 'page'">
                                            {{ item.reference_name || '—' }}
                                        </template>
                                        <template v-else-if="item.type === 'categories_all'">
                                            <em class="text-xs text-blue-400">Dropdown completo</em>
                                        </template>
                                        <template v-else>
                                            {{ item.url || '—' }}
                                        </template>
                                    </td>
                                    <td class="db-table-body-td">
                                        <span :class="item.status === 1 ? 'db-badge db-badge-success' : 'db-badge db-badge-warning'">
                                            {{ item.status === 1 ? $t('label.active') : $t('label.inactive') }}
                                        </span>
                                    </td>
                                    <td class="db-table-body-td">
                                        <div class="flex gap-1.5">
                                            <!-- Add child only for top-level items without parent -->
                                            <button v-if="!item.parent_id" @click="addItem(item.id)" type="button"
                                                title="Adicionar sub-item"
                                                class="db-btn-icon text-green-600 bg-green-100 hover:bg-green-200">
                                                <i class="lab lab-add-circle-line text-xs"></i>
                                            </button>
                                            <button @click="editItem(item)" type="button"
                                                class="db-btn-icon text-amber-500 bg-amber-100 hover:bg-amber-200">
                                                <i class="lab lab-edit"></i>
                                            </button>
                                            <button @click="destroyItem(item)" type="button"
                                                class="db-btn-icon text-red-500 bg-red-100 hover:bg-red-200">
                                                <i class="lab lab-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr v-if="flatItems.length === 0">
                                <td colspan="6" class="text-center py-6 text-gray-400">Nenhum item. Clique em "Adicionar Item" para começar.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Item Create/Edit Drawer -->
    <SiteMenuItemCreateComponent
        :menu-id="menuId"
        :parent-id="newItemParentId"
        :pages="pages"
        :categories="categories"
        @saved="onSaved"
    />
</template>

<script>
import axios from 'axios';
import alertService from "../../../services/alertService";
import SiteMenuItemCreateComponent from "./item/SiteMenuItemCreateComponent";

export default {
    name: "SiteMenuShowComponent",
    components: { SiteMenuItemCreateComponent },
    data() {
        return {
            menu: null,
            newItemParentId: null,
            pages:      [],
            categories: [],
        };
    },
    computed: {
        menuId() { return parseInt(this.$route.params.id); },
        items()  { return this.$store.getters['siteMenuItem/lists']; },
        flatItems() {
            // Render top-level items first, then their children inline
            const result = [];
            const topLevel = this.items.filter(i => !i.parent_id);
            topLevel.forEach(parent => {
                result.push(parent);
                const children = this.items.filter(i => i.parent_id === parent.id);
                children.forEach(c => result.push(c));
            });
            return result;
        },
    },
    mounted() {
        this.loadMenu();
        this.$store.dispatch('siteMenuItem/lists', this.menuId);
        this.loadPages();
        this.loadCategories();
    },
    methods: {
        loadMenu() {
            axios.get(`admin/site-menu/show/${this.menuId}`).then(res => {
                this.menu = res.data.data;
            });
        },
        loadPages() {
            axios.get('admin/page?paginate=0&status=1').then(res => {
                this.pages = res.data.data || [];
            }).catch(() => {});
        },
        loadCategories() {
            axios.get('admin/product-category?paginate=0&status=1&parent_id=null').then(res => {
                this.categories = res.data.data || [];
            }).catch(() => {});
        },
        typeLabel(type) {
            const map = { custom: 'Link', category: 'Categoria', page: 'Página', categories_all: 'Todas Categorias' };
            return map[type] || type;
        },
        typeClass(type) {
            const map = {
                custom:         'db-badge-outline-secondary',
                category:       'db-badge-outline-primary',
                page:           'db-badge-outline-warning',
                categories_all: 'db-badge-outline-success',
            };
            return map[type] || '';
        },
        addItem(parentId) {
            this.newItemParentId = parentId;
            this.$store.dispatch('siteMenuItem/reset');
            this.$nextTick(() => {
                const event = new CustomEvent('open-site-menu-item-drawer');
                window.dispatchEvent(event);
            });
        },
        editItem(item) {
            this.newItemParentId = item.parent_id;
            this.$store.dispatch('siteMenuItem/edit', { id: item.id, menu_id: this.menuId });
            this.$nextTick(() => {
                const event = new CustomEvent('open-site-menu-item-drawer', { detail: item });
                window.dispatchEvent(event);
            });
        },
        destroyItem(item) {
            alertService.questionClass(
                this.$t('message.are_you_sure'),
                this.$t('message.you_wont_be_able_to_revert_this'),
                () => {
                    this.$store.dispatch('siteMenuItem/destroy', { id: item.id, menu_id: this.menuId })
                        .then(() => alertService.success(this.$t('message.deleted_successfully')));
                }
            );
        },
        moveUp(item) {
            this.$store.dispatch('siteMenuItem/moveUp', { id: item.id, menu_id: this.menuId });
        },
        moveDown(item) {
            this.$store.dispatch('siteMenuItem/moveDown', { id: item.id, menu_id: this.menuId });
        },
        onSaved() {
            this.$store.dispatch('siteMenuItem/lists', this.menuId);
        },
    },
}
</script>
