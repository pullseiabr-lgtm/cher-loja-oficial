<template>
    <LoadingComponent :props="loading" />

    <button type="button" @click="add" data-modal="#categorySectionProductModal" class="db-btn h-[37px] text-white bg-primary">
        <i class="lab lab-line-add-circle"></i>
        <span>Adicionar Produto</span>
    </button>

    <div id="categorySectionProductModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Adicionar Produto</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <div class="form-row" v-if="message">
                    <div class="form-col-12 db-field-alert">{{ message }}</div>
                </div>
                <form @submit.prevent="save" class="d-block w-full">
                    <div class="form-row">
                        <div class="form-col-12">
                            <label for="product_id" class="db-field-title required">Produto</label>
                            <vue-select class="db-field-control f-b-custom-select" id="product_id"
                                v-bind:class="errors.product_id ? 'invalid' : ''"
                                v-model="props.form.product_id"
                                :options="products" label-by="name" value-by="id"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="Selecione um produto" search-placeholder="Buscar..." />
                            <small class="db-field-alert" v-if="errors.product_id">
                                {{ errors.product_id[0] }}
                            </small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t("button.save") }}</span>
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
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";

export default {
    name: "CategorySectionProductCreateComponent",
    components: { LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: { isActive: false },
            errors: {},
            message: null,
        };
    },
    computed: {
        products: function () {
            return this.$store.getters['product/lists'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('product/lists', {
            paginate: 0,
            order_column: 'name',
            order_type: 'asc',
            status: statusEnum.ACTIVE,
        });
        this.loading.isActive = false;
    },
    methods: {
        add: function () {
            appService.modalShow();
        },
        reset: function () {
            appService.modalHide();
            this.$store.dispatch("categorySectionProduct/reset").then().catch();
            this.errors = {};
            this.$props.props.form = { product_id: null };
            this.message = null;
        },
        save: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("categorySectionProduct/save", this.props).then(() => {
                    appService.modalHide();
                    this.loading.isActive = false;
                    alertService.successFlip(0, "Produto");
                    this.props.form = { product_id: null };
                    this.errors = {};
                    this.message = null;
                }).catch((err) => {
                    this.loading.isActive = false;
                    if (err.response.data.errors === undefined) {
                        this.errors = {};
                        this.message = err.response.data.message ?? null;
                    } else {
                        this.message = null;
                        this.errors = err.response.data.errors;
                    }
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    }
};
</script>
