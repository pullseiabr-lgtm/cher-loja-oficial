<template>
    <LoadingComponent :props="loading" />

    <div class="col-12">
        <!-- Back Button -->
        <div class="mb-4">
            <router-link :to="{ name: 'admin.site-menus' }"
                class="db-btn h-[37px] text-white bg-gray-500 hover:bg-gray-600">
                <i class="fa-solid fa-arrow-left text-sm"></i>
                <span>Voltar</span>
            </router-link>
        </div>

        <!-- Menu Info Card -->
        <div class="db-card mb-5" v-if="menu">
            <div class="db-card-header border-none">
                <div>
                    <h3 class="db-card-title">{{ menu.name }}</h3>
                    <span class="mt-1 text-sm font-medium"
                        :class="menu.location === 'header' ? 'text-primary' : 'text-secondary'">
                        {{ menu.location === 'header' ? 'Header (Cabeçalho)' : 'Footer (Rodapé)' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Items Card -->
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">Itens do Menu</h3>
                <div class="db-card-filter">
                    <SiteMenuItemCreateComponent
                        :menu-id="menuId"
                        :pages="pages"
                        :categories="categories"
                        @saved="loadItems"
                    />
                </div>
            </div>
            <p class="px-5 pb-2 text-sm text-gray-400">
                Use ▲ ▼ para reordenar. Clique em <strong>+</strong> para adicionar sub-item a um item pai.
            </p>

            <div class="db-table-responsive">
                <table class="db-table stripe">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th w-16">Ordem</th>
                            <th class="db-table-head-th">Título</th>
                            <th class="db-table-head-th">Tipo</th>
                            <th class="db-table-head-th">Destino</th>
                            <th class="db-table-head-th">{{ $t('label.status') }}</th>
                            <th class="db-table-head-th">{{ $t('label.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="flatItems.length > 0">
                        <tr class="db-table-body-tr" v-for="item in flatItems" :key="item.id"
                            :class="item.parent_id ? 'bg-gray-50' : ''">

                            <!-- Order arrows -->
                            <td class="db-table-body-td">
                                <div class="flex flex-col items-center gap-0.5">
                                    <button @click="moveUp(item)" type="button"
                                        class="db-table-action"
                                        title="Mover para cima">
                                        <i class="fa-solid fa-chevron-up text-xs"></i>
                                    </button>
                                    <button @click="moveDown(item)" type="button"
                                        class="db-table-action"
                                        title="Mover para baixo">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Title with indent for children -->
                            <td class="db-table-body-td">
                                <span :style="item.parent_id ? 'padding-left:1.5rem' : ''">
                                    <i v-if="item.parent_id"
                                        class="fa-solid fa-turn-up fa-rotate-90 text-gray-300 mr-1 text-xs"></i>
                                    {{ item.title }}
                                </span>
                            </td>

                            <!-- Type badge -->
                            <td class="db-table-body-td">
                                <span :class="typeClass(item.type)" class="db-badge">
                                    {{ typeLabel(item.type) }}
                                </span>
                            </td>

                            <!-- Destination -->
                            <td class="db-table-body-td text-sm text-gray-500 max-w-[200px] truncate">
                                <span v-if="item.type === 'categories_all'" class="text-xs text-blue-400 italic">
                                    Dropdown completo
                                </span>
                                <span v-else-if="item.type === 'category' || item.type === 'page'">
                                    {{ item.reference_name || '—' }}
                                </span>
                                <span v-else>{{ item.url || '—' }}</span>
                            </td>

                            <!-- Status -->
                            <td class="db-table-body-td">
                                <span :class="statusClass(item.status)">
                                    {{ item.status === 1 ? $t('label.active') : $t('label.inactive') }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="db-table-body-td">
                                <div class="flex justify-start items-center gap-1.5">
                                    <!-- Add child button (only top-level) -->
                                    <button v-if="!item.parent_id" @click="addChild(item.id)" type="button"
                                        title="Adicionar sub-item"
                                        class="db-table-action view">
                                        <i class="lab lab-add-circle-line"></i>
                                        <span class="db-tooltip">Sub-item</span>
                                    </button>
                                    <!-- Edit -->
                                    <SmIconSidebarModalEditComponent @click="editItem(item)" />
                                    <!-- Delete -->
                                    <SmIconDeleteComponent @click="destroyItem(item)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="db-table-body" v-else>
                        <tr class="db-table-body-tr">
                            <td class="db-table-body-td text-center" colspan="6">
                                <div class="p-4">
                                    <span class="d-block mt-3 text-lg">{{ $t('message.no_data_found') }}</span>
                                    <p class="text-sm text-gray-400 mt-1">Clique em "Adicionar Item" para começar.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import LoadingComponent from "../components/LoadingComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SiteMenuItemCreateComponent from "./item/SiteMenuItemCreateComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "SiteMenuShowComponent",
    components: {
        LoadingComponent,
        SmIconSidebarModalEditComponent,
        SmIconDeleteComponent,
        SiteMenuItemCreateComponent,
    },
    data() {
        return {
            loading: { isActive: false },
            menu:       null,
            pages:      [],
            categories: [],
        };
    },
    computed: {
        menuId() { return parseInt(this.$route.params.id); },
        items()  { return this.$store.getters['siteMenuItem/lists']; },
        flatItems() {
            const top      = this.items.filter(i => !i.parent_id);
            const result   = [];
            top.forEach(parent => {
                result.push(parent);
                this.items.filter(i => i.parent_id === parent.id).forEach(c => result.push(c));
            });
            return result;
        },
    },
    mounted() {
        this.loadMenu();
        this.loadItems();
        this.loadPages();
        this.loadCategories();
    },
    methods: {
        loadMenu() {
            axios.get(`admin/site-menu/show/${this.menuId}`).then(res => {
                this.menu = res.data.data;
            });
        },
        loadItems() {
            this.loading.isActive = true;
            this.$store.dispatch('siteMenuItem/lists', this.menuId).then(() => {
                this.loading.isActive = false;
            }).catch(() => { this.loading.isActive = false; });
        },
        loadPages() {
            axios.get('admin/page?paginate=0&status=1').then(res => {
                this.pages = res.data.data || [];
            }).catch(() => {});
        },
        loadCategories() {
            axios.get('admin/product-category?paginate=0&status=1').then(res => {
                this.categories = res.data.data || [];
            }).catch(() => {});
        },
        typeLabel(type) {
            return { custom: 'Link', category: 'Categoria', page: 'Página', categories_all: 'Todas Categorias' }[type] || type;
        },
        typeClass(type) {
            return {
                custom:         'db-badge-outline-secondary',
                category:       'db-badge-outline-primary',
                page:           'db-badge-outline-warning',
                categories_all: 'db-badge-outline-success',
            }[type] || '';
        },
        statusClass(status) { return appService.statusClass(status); },
        editItem(item) {
            window.dispatchEvent(new CustomEvent('open-item-for-edit', { detail: item }));
        },
        addChild(parentId) {
            window.dispatchEvent(new CustomEvent('open-item-as-child', { detail: { parentId } }));
        },
        moveUp(item) {
            this.$store.dispatch('siteMenuItem/moveUp', { id: item.id, menu_id: this.menuId });
        },
        moveDown(item) {
            this.$store.dispatch('siteMenuItem/moveDown', { id: item.id, menu_id: this.menuId });
        },
        destroyItem(item) {
            appService.destroyConfirmation().then(() => {
                this.loading.isActive = true;
                this.$store.dispatch('siteMenuItem/destroy', { id: item.id, menu_id: this.menuId }).then(() => {
                    this.loading.isActive = false;
                    alertService.successFlip(null, 'Item');
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response?.data?.message);
                });
            }).catch(() => {});
        },
    },
};
</script>
