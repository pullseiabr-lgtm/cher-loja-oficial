<template>
    <div class="db-card-header border-none">
        <h3 class="db-card-title">Banners / Promoções</h3>
        <div class="db-card-filter">
            <CategorySectionPromotionCreateComponent :props="sectionProps" />
            <TableLimitComponent :method="list" :search="sectionProps.search" :page="paginationPage" />
        </div>
    </div>

    <div class="db-table-responsive">
        <table class="db-table stripe">
            <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <th class="db-table-head-th">Imagem</th>
                    <th class="db-table-head-th">{{ $t("label.name") }}</th>
                    <th class="db-table-head-th">{{ $t("label.status") }}</th>
                    <th class="db-table-head-th">{{ $t("label.action") }}</th>
                </tr>
            </thead>
            <tbody class="db-table-body" v-if="items.length > 0">
                <tr class="db-table-body-tr" v-for="item in items" :key="item.id">
                    <td class="db-table-body-td">
                        <img v-if="item.promotion_cover" :src="item.promotion_cover"
                            class="w-16 h-10 object-cover rounded-lg" alt="" />
                        <div v-else class="w-16 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <i class="lab lab-line-promotion text-gray-400"></i>
                        </div>
                    </td>
                    <td class="db-table-body-td">{{ item.promotion_name }}</td>
                    <td class="db-table-body-td">
                        <span :class="statusClass(item.promotion_status)">
                            {{ enums.statusEnumArray[item.promotion_status] }}
                        </span>
                    </td>
                    <td class="db-table-body-td">
                        <SmIconDeleteComponent @click="destroy(item.id)" />
                    </td>
                </tr>
            </tbody>
            <tbody class="db-table-body" v-else>
                <tr class="db-table-body-tr">
                    <td class="db-table-body-td text-center" colspan="4">
                        <span class="d-block py-4 text-gray-500">Nenhum banner adicionado.</span>
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
import CategorySectionPromotionCreateComponent from "./CategorySectionPromotionCreateComponent";
import TableLimitComponent from "../../components/TableLimitComponent";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";

export default {
    name: "CategorySectionPromotionListComponent",
    components: {
        CategorySectionPromotionCreateComponent,
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
                    promotion_id: null,
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
        items: function () {
            return this.$store.getters['categorySectionPromotion/lists'];
        },
        pagination: function () {
            return this.$store.getters["categorySectionPromotion/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["categorySectionPromotion/page"];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.sectionProps.search.page = page;
            this.$store.dispatch("categorySectionPromotion/lists", this.sectionProps.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then(() => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('categorySectionPromotion/destroy', {
                        categorySection: this.categorySection,
                        id: id,
                        search: this.sectionProps.search
                    }).then(() => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, "Banner");
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
