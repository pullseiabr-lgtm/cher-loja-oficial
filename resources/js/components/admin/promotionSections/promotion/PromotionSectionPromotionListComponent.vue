<template>
    <div class="db-card-header border-none">
        <h3 class="db-card-title">Promoções</h3>
        <div class="db-card-filter">
            <PromotionSectionPromotionCreateComponent :props="sectionProps" />
            <TableLimitComponent :method="list" :search="sectionProps.search" :page="paginationPage" />
        </div>
    </div>

    <div class="db-table-responsive">
        <table class="db-table stripe">
            <thead class="db-table-head">
                <tr class="db-table-head-tr">
                    <th class="db-table-head-th">Imagem</th>
                    <th class="db-table-head-th">{{ $t("label.name") }}</th>
                    <th class="db-table-head-th">Tipo de Link</th>
                    <th class="db-table-head-th">Link</th>
                    <th class="db-table-head-th">{{ $t("label.action") }}</th>
                </tr>
            </thead>
            <tbody class="db-table-body" v-if="items.length > 0">
                <tr class="db-table-body-tr" v-for="item in items" :key="item.id">
                    <td class="db-table-body-td">
                        <img v-if="item.cover" :src="item.cover"
                            class="w-16 h-10 object-cover rounded-lg" alt="" />
                        <div v-else class="w-16 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <i class="lab lab-line-promotion text-gray-400"></i>
                        </div>
                    </td>
                    <td class="db-table-body-td">{{ item.name }}</td>
                    <td class="db-table-body-td">
                        <span v-if="item.link_type === 'category'" class="db-badge db-badge-active">Categoria</span>
                        <span v-else-if="item.link_type === 'custom'" class="db-badge db-badge-upcoming">Personalizado</span>
                        <span v-else class="text-gray-400">—</span>
                    </td>
                    <td class="db-table-body-td">
                        <span v-if="item.link_url" class="text-xs text-gray-500 truncate max-w-[180px] block">{{ item.link_url }}</span>
                        <span v-else class="text-gray-400">—</span>
                    </td>
                    <td class="db-table-body-td">
                        <SmIconDeleteComponent @click="destroy(item.id)" />
                    </td>
                </tr>
            </tbody>
            <tbody class="db-table-body" v-else>
                <tr class="db-table-body-tr">
                    <td class="db-table-body-td text-center" colspan="5">
                        <span class="d-block py-4 text-gray-500">Nenhuma promoção adicionada.</span>
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
import appService from "../../../../services/appService";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent";
import PromotionSectionPromotionCreateComponent from "./PromotionSectionPromotionCreateComponent";
import TableLimitComponent from "../../components/TableLimitComponent";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent";
import PaginationBox from "../../components/pagination/PaginationBox";
import PaginationSMBox from "../../components/pagination/PaginationSMBox";

export default {
    name: "PromotionSectionPromotionListComponent",
    components: {
        PromotionSectionPromotionCreateComponent,
        SmIconDeleteComponent,
        TableLimitComponent,
        PaginationTextComponent,
        PaginationBox,
        PaginationSMBox,
    },
    props: {
        promotionSection: { type: Number },
    },
    data() {
        return {
            loading: { isActive: false },
            sectionProps: {
                id: this.promotionSection,
                form: {
                    promotion_id: null,
                },
                search: {
                    id: this.promotionSection,
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'order',
                    order_type: 'asc',
                }
            },
        }
    },
    mounted() {
        this.list();
    },
    computed: {
        items: function () {
            return this.$store.getters['promotionSectionPromotion/lists'];
        },
        pagination: function () {
            return this.$store.getters["promotionSectionPromotion/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["promotionSectionPromotion/page"];
        },
    },
    methods: {
        list: function (page = 1) {
            this.loading.isActive = true;
            this.sectionProps.search.page = page;
            this.$store.dispatch("promotionSectionPromotion/lists", this.sectionProps.search).then(() => {
                this.loading.isActive = false;
            }).catch(() => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then(() => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('promotionSectionPromotion/destroy', {
                        promotionSection: this.promotionSection,
                        id: id,
                        search: this.sectionProps.search
                    }).then(() => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, "Promoção");
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
