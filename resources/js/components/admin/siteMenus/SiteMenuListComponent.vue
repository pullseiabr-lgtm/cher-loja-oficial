<template>
    <div class="db-card">
        <div class="db-card-header flex items-center justify-between">
            <h3 class="db-card-title">{{ $t('menu.site_menus') }}</h3>
            <button @click="add" type="button" class="db-btn py-2 text-white bg-primary">
                <i class="lab lab-add-circle-line"></i>
                <span>{{ $t('button.add') }}</span>
            </button>
        </div>

        <div class="db-card-body">
            <div class="db-table-responsive">
                <table class="db-table">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ $t('label.name') }}</th>
                            <th class="db-table-head-th">Local</th>
                            <th class="db-table-head-th">{{ $t('label.status') }}</th>
                            <th class="db-table-head-th">{{ $t('label.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body">
                        <tr v-for="menu in lists" :key="menu.id" class="db-table-body-tr">
                            <td class="db-table-body-td font-semibold">{{ menu.name }}</td>
                            <td class="db-table-body-td">
                                <span :class="menu.location === 'header' ? 'db-badge db-badge-outline-primary' : 'db-badge db-badge-outline-secondary'">
                                    {{ menu.location === 'header' ? 'Header' : 'Footer' }}
                                </span>
                            </td>
                            <td class="db-table-body-td">
                                <span :class="menu.status === 1 ? 'db-badge db-badge-success' : 'db-badge db-badge-warning'">
                                    {{ menu.status === 1 ? $t('label.active') : $t('label.inactive') }}
                                </span>
                            </td>
                            <td class="db-table-body-td">
                                <div class="flex gap-1.5">
                                    <router-link :to="{ name: 'admin.site-menus.show', params: { id: menu.id } }"
                                        class="db-btn-icon text-blue-500 bg-blue-100 hover:bg-blue-200">
                                        <i class="lab lab-eye"></i>
                                    </router-link>
                                    <button @click="edit(menu.id)" type="button"
                                        class="db-btn-icon text-amber-500 bg-amber-100 hover:bg-amber-200">
                                        <i class="lab lab-edit"></i>
                                    </button>
                                    <button @click="destroy(menu)" type="button"
                                        class="db-btn-icon text-red-500 bg-red-100 hover:bg-red-200">
                                        <i class="lab lab-delete"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="lists.length === 0">
                            <td colspan="4" class="text-center py-6 text-gray-400">Nenhum menu encontrado.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Drawer -->
    <div id="site-menu-drawer" class="db-sidebar-canvas">
        <div class="db-sidebar-canvas-head">
            <h3>{{ temp.isEditing ? 'Editar Menu' : 'Novo Menu' }}</h3>
            <button @click="closeDrawer" type="button" class="fa-solid fa-xmark"></button>
        </div>
        <div class="db-sidebar-canvas-body">
            <div class="form-row">
                <label class="db-field-title required">{{ $t('label.name') }}</label>
                <input v-model="form.name" type="text" class="db-field-control" />
                <small v-if="errors.name" class="db-field-alert">{{ errors.name[0] }}</small>
            </div>
            <div class="form-row mt-4">
                <label class="db-field-title required">Local</label>
                <select v-model="form.location" class="db-field-control">
                    <option value="header">Header (Cabeçalho)</option>
                    <option value="footer">Footer (Rodapé)</option>
                </select>
                <small v-if="errors.location" class="db-field-alert">{{ errors.location[0] }}</small>
            </div>
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
    <div id="site-menu-drawer-overlay" class="db-sidebar-canvas-overlay" @click="closeDrawer"></div>
</template>

<script>
import targetService from "../../../services/targetService";
import alertService from "../../../services/alertService";

export default {
    name: "SiteMenuListComponent",
    data() {
        return {
            form: { name: '', location: 'header', status: 1 },
            errors: {},
            search: { paginate: 0, order_column: 'id', order_type: 'asc' },
        };
    },
    computed: {
        lists() { return this.$store.getters['siteMenu/lists']; },
        temp()  { return this.$store.getters['siteMenu/temp']; },
    },
    mounted() {
        this.$store.dispatch('siteMenu/lists', this.search);
    },
    methods: {
        add() {
            this.$store.dispatch('siteMenu/reset');
            this.form   = { name: '', location: 'header', status: 1 };
            this.errors = {};
            targetService.showTarget('site-menu-drawer', 'canvas-active');
        },
        edit(id) {
            this.$store.dispatch('siteMenu/edit', id);
            const menu  = this.lists.find(m => m.id === id);
            this.form   = { name: menu.name, location: menu.location, status: menu.status };
            this.errors = {};
            targetService.showTarget('site-menu-drawer', 'canvas-active');
        },
        closeDrawer() {
            targetService.hideTarget('site-menu-drawer', 'canvas-active');
        },
        save() {
            this.errors = {};
            this.$store.dispatch('siteMenu/save', { form: this.form, search: this.search })
                .then(() => {
                    alertService.successFlip(null, this.$t('label.menu'));
                    this.closeDrawer();
                })
                .catch(err => {
                    if (err.response?.data?.errors) this.errors = err.response.data.errors;
                    else alertService.error(err.response?.data?.message || 'Erro');
                });
        },
        destroy(menu) {
            alertService.questionClass(
                this.$t('message.are_you_sure'),
                this.$t('message.you_wont_be_able_to_revert_this'),
                () => {
                    this.$store.dispatch('siteMenu/destroy', { id: menu.id, search: this.search })
                        .then(() => alertService.success(this.$t('message.deleted_successfully')));
                }
            );
        },
    },
}
</script>
