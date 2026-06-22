<template>
    <CategorySectionCategoryEditComponent ref="editModal" :sectionId="categorySection" :sectionSearch="sectionProps.search" />

    <div class="db-card-header border-none">
        <h3 class="db-card-title">Categorias</h3>
        <div class="db-card-filter">
            <CategorySectionCategoryCreateComponent :props="sectionProps" />
            <TableLimitComponent :method="list" :search="sectionProps.search" :page="paginationPage" />
        </div>
    </div>

    <div class="db-table-responsive">
        <table class="db-table stripe">
            <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <th class="db-table-head-th">{{ $t("label.name") }}</th>
                    <th class="db-table-head-th">{{ $t("label.status") }}</th>
                    <th class="db-table-head-th">{{ $t("label.action") }}</th>
                </tr>
            </thead>
            <tbody class="db-table-body" v-if="categorySectionCategories.length > 0">
                <tr class="db-table-body-tr" v-for="item in categorySectionCategories" :key="item.id">
                    <td class="db-table-body-td">
                        <div class="flex items-center gap-3">
                            <img v-if="item.category_thumb" :src="item.category_thumb"
                                class="w-10 h-10 object-cover rounded-lg" alt="" />
                            <div v-else
                                class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class="lab lab-line-item-categories text-gray-400"></i>
                            </div>
                            <span>{{ item.category_name }}</span>
                        </div>
                    </td>
                    <td class="db-table-body-td">
                        <span :class="statusClass(item.category_status)">
                            {{ enums.statusEnumArray[item.category_status] }}
                        </span>
                    </td>
                    <td class="db-table-body-td">
                        <div class="flex justify-start items-center gap-1.5">
                            <button type="button" @click="edit(item)" class="db-table-action edit">
                                <i class="lab lab-line-edit"></i>
                                <span class="db-tooltip">{{ $t('button.edit') }}</span>
                            </button>
                            <SmIconDeleteComponent @click="destroy(item.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody class="db-table-body" v-else>
                <tr class="db-table-body-tr">
                    <td class="db-table-body-td text-center" colspan="3">
                        <span class="d-block py-4 text-gray-500">Nenhuma categoria adicionada.</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
        <PaginationSMBox :pagination="pagination" :method="list" />
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <PaginationTextComponent :props="{ page: paginationPage }" />
            <PaginationBox :pagination="pagination" :method="list" />
        </div>
    </div>
</template>

<script>
import alertService from "../../../../services/alertService";
import statusEnum from "../../../../enums/modules/statusEnum";
import appService from "../../../../services/appService";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent";
import CategorySectionCategoryCreateComponent from "./CategorySectionCategoryCreateComponent";
import CategorySectionCategoryEditComponent from "./CategorySectionCategoryEditComponent";
import TableLimitComponent from "../../components/TableLimitComponent";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";

export default {
    name: "CategorySectionCategoryListComponent",
    components: {
        CategorySectionCategoryCreateComponent,
        CategorySectionCategoryEditComponent,
        SmIconDeleteComponent,
        TableLimitComponent,
        PaginationTextComponent,
        PaginationBox,
        PaginationSMBox,
    },
    props: {
        categorySection: { type: Number },
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
            sectionProps: {
                id: this.categorySection,
                form: {
                    product_category_id: null,
                },
                search: {
                    id: this.categorySection,
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    order_type: 'desc',
                }
            },
        }
    },
    mounted() {
        this.list();
    },
    computed: {
        categorySectionCategories: function () {
            return this.$store.getters['categorySectionCategory/lists'];
        },
        pagination: function () {
            return this.$store.getters["categorySectionCategory/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["categorySectionCategory/page"];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.sectionProps.search.page = page;
            this.$store.dispatch("categorySectionCategory/lists", this.sectionProps.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        edit: function (item) {
            this.$refs.editModal.open(item);
        },
        destroy: function (id) {
            appService.destroyConfirmation().then(() => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('categorySectionCategory/destroy', {
                        categorySection: this.categorySection,
                        id: id,
                        search: this.sectionProps.search
                    }).then(() => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, "Categoria");
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
        }
    }
}
</script>
