<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">Configuração de Menus</h3>
                <div class="db-card-filter">
                    <SiteMenuCreateComponent :props="props" />
                </div>
            </div>

            <div class="db-table-responsive">
                <table class="db-table stripe">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ $t('label.name') }}</th>
                            <th class="db-table-head-th">Local</th>
                            <th class="db-table-head-th">{{ $t('label.status') }}</th>
                            <th class="db-table-head-th">{{ $t('label.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="lists.length > 0">
                        <tr class="db-table-body-tr" v-for="menu in lists" :key="menu.id">
                            <td class="db-table-body-td font-medium">{{ menu.name }}</td>
                            <td class="db-table-body-td">
                                <span :class="menu.location === 'header'
                                    ? 'db-badge db-badge-outline-primary'
                                    : 'db-badge db-badge-outline-secondary'">
                                    {{ menu.location === 'header' ? 'Header' : 'Footer' }}
                                </span>
                            </td>
                            <td class="db-table-body-td">
                                <span :class="statusClass(menu.status)">
                                    {{ menu.status === 1 ? $t('label.active') : $t('label.inactive') }}
                                </span>
                            </td>
                            <td class="db-table-body-td">
                                <div class="flex justify-start items-center gap-1.5">
                                    <SmIconViewComponent link="admin.site-menus.show" :id="menu.id" />
                                    <SmIconSidebarModalEditComponent @click="edit(menu)" />
                                    <SmIconDeleteComponent @click="destroy(menu.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="db-table-body" v-else>
                        <tr class="db-table-body-tr">
                            <td class="db-table-body-td text-center" colspan="4">
                                <div class="p-4">
                                    <span class="d-block mt-3 text-lg">{{ $t('message.no_data_found') }}</span>
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
import LoadingComponent from "../components/LoadingComponent";
import SiteMenuCreateComponent from "./SiteMenuCreateComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";

export default {
    name: "SiteMenuListComponent",
    components: {
        LoadingComponent,
        SiteMenuCreateComponent,
        SmIconViewComponent,
        SmIconSidebarModalEditComponent,
        SmIconDeleteComponent,
    },
    data() {
        return {
            loading: { isActive: false },
            props: {
                form:   { name: '', location: 'header', status: 1 },
                search: { paginate: 0, order_column: 'id', order_type: 'asc' },
            },
        };
    },
    computed: {
        lists() { return this.$store.getters['siteMenu/lists']; },
    },
    mounted() {
        this.list();
    },
    methods: {
        statusClass(status) { return appService.statusClass(status); },
        list() {
            this.loading.isActive = true;
            this.$store.dispatch('siteMenu/lists', this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => { this.loading.isActive = false; });
        },
        edit(menu) {
            appService.sideDrawerShow();
            this.$store.dispatch('siteMenu/edit', menu.id);
            this.props.form = {
                name:     menu.name,
                location: menu.location,
                status:   menu.status,
            };
        },
        destroy(id) {
            appService.destroyConfirmation().then(() => {
                this.loading.isActive = true;
                this.$store.dispatch('siteMenu/destroy', { id, search: this.props.search }).then(() => {
                    this.loading.isActive = false;
                    alertService.successFlip(null, 'Menu');
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response?.data?.message);
                });
            }).catch(() => {});
        },
    },
};
</script>
