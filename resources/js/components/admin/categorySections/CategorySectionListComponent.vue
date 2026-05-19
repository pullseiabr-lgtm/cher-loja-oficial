<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">Seções de Categorias</h3>
                <div class="db-card-filter">
                    <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                    <FilterComponent @click.prevent="handleSlide('categorysection-filter')" />
                    <CategorySectionCreateComponent :props="props" />
                </div>
            </div>

            <div class="table-filter-div" id="categorysection-filter">
                <form class="p-4 sm:p-5 mb-5 d-block w-full" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchName" class="db-field-title after:hidden">{{ $t("label.name") }}</label>
                            <input id="searchName" v-model="props.search.name" type="text" class="db-field-control" />
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStatus" class="db-field-title after:hidden">{{ $t("label.status") }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchStatus"
                                v-model="props.search.status"
                                :options="[
                                    { id: enums.statusEnum.ACTIVE, name: $t('label.active') },
                                    { id: enums.statusEnum.INACTIVE, name: $t('label.inactive') },
                                ]"
                                label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>
                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-line-search lab-font-size-16"></i>
                                    <span>{{ $t("button.search") }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-line-cross lab-font-size-22"></i>
                                    <span>{{ $t("button.clear") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="db-table-responsive">
                <table class="db-table stripe" id="print">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ $t("label.name") }}</th>
                            <th class="db-table-head-th">{{ $t("label.status") }}</th>
                            <th class="db-table-head-th">{{ $t("label.action") }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="categorySections.length > 0">
                        <tr class="db-table-body-tr" v-for="categorySection in categorySections" :key="categorySection.id">
                            <td class="db-table-body-td">
                                <div v-if="categorySection.name.length < 40">{{ categorySection.name }}</div>
                                <div v-else>{{ categorySection.name.substring(0, 40) + ".." }}</div>
                            </td>
                            <td class="db-table-body-td">
                                <span :class="statusClass(categorySection.status)">
                                    {{ enums.statusEnumArray[categorySection.status] }}
                                </span>
                            </td>
                            <td class="db-table-body-td">
                                <div class="flex justify-start items-center gap-1.5">
                                    <SmIconViewComponent :link="'admin.category-sections.show'" :id="categorySection.id" />
                                    <SmIconSidebarModalEditComponent @click="edit(categorySection)" />
                                    <SmIconDeleteComponent @click="destroy(categorySection.id)" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="db-table-body" v-else>
                        <tr class="db-table-body-tr">
                            <td class="db-table-body-td text-center" colspan="3">
                                <div class="p-4">
                                    <div class="max-w-[300px] mx-auto mt-2">
                                        <img class="w-full h-full" :src="ENV.API_URL + '/images/default/not-found/not_found.png'" alt="Not Found">
                                    </div>
                                    <span class="d-block mt-3 text-lg">{{ $t('message.no_data_found') }}</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6"
                v-if="categorySections.length > 0">
                <PaginationSMBox :pagination="pagination" :method="list" />
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <PaginationTextComponent :props="{ page: paginationPage }" />
                    <PaginationBox :pagination="pagination" :method="list" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import CategorySectionCreateComponent from "./CategorySectionCreateComponent";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent";
import PaginationBox from "../components/pagination/PaginationBox";
import PaginationSMBox from "../components/pagination/PaginationSMBox";
import appService from "../../../services/appService";
import statusEnum from "../../../enums/modules/statusEnum";
import TableLimitComponent from "../components/TableLimitComponent";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent";
import FilterComponent from "../components/buttons/collapse/FilterComponent";
import ENV from "../../../config/env";

export default {
    name: "CategorySectionListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        CategorySectionCreateComponent,
        LoadingComponent,
        SmIconDeleteComponent,
        SmIconSidebarModalEditComponent,
        SmIconViewComponent,
        FilterComponent,
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
            props: {
                form: {
                    name: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                    name: "",
                    status: null,
                },
            },
            ENV: ENV,
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        categorySections: function () {
            return this.$store.getters["categorySection/lists"];
        },
        pagination: function () {
            return this.$store.getters["categorySection/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["categorySection/page"];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        handleSlide: function (id) {
            return appService.handleSlide(id);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.status = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("categorySection/lists", this.props.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        edit: function (categorySection) {
            appService.sideDrawerShow();
            this.loading.isActive = true;
            this.$store.dispatch("categorySection/edit", categorySection.id).then(() => {
                this.loading.isActive = false;
                this.props.errors = {};
                this.props.form = {
                    name: categorySection.name,
                    status: categorySection.status,
                };
            }).catch((err) => {
                alertService.error(err.response.data.message);
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then(() => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("categorySection/destroy", { id: id, search: this.props.search }).then(() => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, "Seção de Categorias");
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
    },
};
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
